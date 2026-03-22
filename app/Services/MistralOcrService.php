<?php

namespace App\Services;

use App\Models\OcrResult;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class MistralOcrService
{
    /**
     * Process an uploaded receipt file through Mistral OCR and structured extraction.
     *
     * Stores the file, runs mistral-ocr-latest for raw text, then
     * mistral-small-latest for the full Notafy expense receipt schema.
     * Creates and returns an OcrResult record owned by the given user.
     *
     * @param  UploadedFile $file    The uploaded receipt file.
     * @param  int          $userId  Owner user ID.
     * @return OcrResult             The persisted result record.
     *
     * @throws \RuntimeException if Mistral OCR API returns a non-2xx response.
     */
    public function extract(UploadedFile $file, int $userId): OcrResult
    {
        $filename = $file->getClientOriginalName();
        $fileType = strtolower($file->getClientOriginalExtension()) === 'pdf' ? 'pdf' : 'image';
        $path     = $file->store('ocr_uploads', 'local');

        $previewPath = null;
        if ($fileType === 'pdf') {
            try {
                $fullPath    = Storage::disk('local')->path($path);
                $previewName = 'ocr_previews/' . pathinfo($path, PATHINFO_FILENAME) . '_preview.jpg';
                $previewFull = Storage::disk('local')->path($previewName);
                Storage::disk('local')->makeDirectory('ocr_previews');
                $pdf = new Pdf($fullPath);
                $pdf->resolution(200)->selectPage(1)->save($previewFull);
                $previewPath = $previewName;
            } catch (\Throwable) {
                $previewPath = null;
            }
        }

        $ocr = OcrResult::create([
            'user_id'      => $userId,
            'filename'     => $filename,
            'file_path'    => $path,
            'file_type'    => $fileType,
            'preview_path' => $previewPath,
            'ocr_engine'   => 'mistral',
            'status'       => 'processing',
        ]);

        try {
            $apiKey   = config('services.mistral.key');
            $fullPath = Storage::disk('local')->path($path);
            $rawText  = $this->runMistralOcrWithRetry($fullPath, $fileType);
            $schema   = $this->extractReceiptSchema($rawText, $apiKey);
            $columns  = $this->mapSchemaToColumns($schema);

            $ocr->update(array_merge([
                'extracted_text' => $rawText,
                'status'         => 'done',
            ], $columns));
        } catch (\Throwable $e) {
            // Clean up stored file to prevent storage accumulation
            Storage::disk('local')->delete($path);
            if ($previewPath) {
                Storage::disk('local')->delete($previewPath);
            }
            $ocr->delete();
            throw $e;
        }

        return $ocr->fresh();
    }

    /**
     * Re-process an existing file (by storage path) and return updated columns.
     * Does not create a new OcrResult or move any file.
     */
    public function reprocess(string $storagePath, string $fileType): array
    {
        $apiKey   = config('services.mistral.key');
        $fullPath = Storage::disk('local')->path($storagePath);
        $rawText  = $this->runMistralOcrWithRetry($fullPath, $fileType);
        $schema   = $this->extractReceiptSchema($rawText, $apiKey);
        $columns  = $this->mapSchemaToColumns($schema);

        return array_merge(['extracted_text' => $rawText, 'status' => 'done'], $columns);
    }

    /**
     * Run Mistral OCR with one retry on failure (exponential backoff).
     */
    private function runMistralOcrWithRetry(string $filePath, string $fileType): string
    {
        $lastException = null;
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                return $this->runMistralOcr($filePath, $fileType);
            } catch (\RuntimeException $e) {
                $lastException = $e;
                if ($attempt < 2) {
                    sleep(2 ** $attempt);
                }
            }
        }
        throw $lastException;
    }

    /**
     * Extract raw text from a receipt file using mistral-ocr-latest.
     */
    private function runMistralOcr(string $filePath, string $fileType): string
    {
        $apiKey   = config('services.mistral.key');
        $mimeType = $fileType === 'pdf' ? 'application/pdf' : 'image/jpeg';
        $base64   = base64_encode(file_get_contents($filePath));
        $dataUrl  = "data:{$mimeType};base64,{$base64}";

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ])->timeout(60)->post('https://api.mistral.ai/v1/ocr', [
            'model'    => 'mistral-ocr-latest',
            'document' => [
                'type' => $fileType === 'pdf' ? 'document_url' : 'image_url',
                ($fileType === 'pdf' ? 'document_url' : 'image_url') => $dataUrl,
            ],
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Mistral OCR API error: ' . $response->body());
        }

        $pages = $response->json('pages', []);
        return collect($pages)->pluck('markdown')->implode("\n\n");
    }

    /**
     * Extract structured Notafy receipt schema from raw OCR text using mistral-small-latest.
     */
    private function extractReceiptSchema(string $rawText, string $apiKey): array
    {
        $systemPrompt = <<<'PROMPT'
You are an Indonesian expense receipt OCR specialist.
Extract structured data from receipts and return ONLY valid JSON.
No explanation, no markdown, no code blocks — pure JSON only.

Detect the platform from visual cues and text patterns:
- Shopee: orange logo, 'Nota Pesanan', 'No. Pesanan'
- Tokopedia: green logo, 'INV/', 'DITERBITKAN ATAS NAMA'
- GoFood: red/dark header, 'Thanks for using GoFood', 'Transaction ID: F-'
- GoCar/GoRide: green header, 'Thanks for ordering GoCar/GoRide', 'Order ID: RB-'
- GrabFood/GrabBike/GrabCar: green Grab logo, 'Kode booking: A-', 'Rincian'
- Indomaret: thermal font, 'TOTAL BELANJA', 'NON TUNAI', 'PPN'
- Nota warung: handwritten or mixed print, no standard platform markers

For transport receipts (GoCar, GoRide, GrabBike, GrabCar):
- employee_name = the DRIVER (from 'pengemudi [Name]', driver profile, or 'Hi [Name]' greeting to driver)
- 'Penumpang' means PASSENGER — this is the customer, never put them in employee_name
- set category = 'transport'
- vendor_name = platform name (e.g. 'GoCar', 'GrabBike')

For food receipts (GoFood, GrabFood):
- extract all line items with qty and price
- set category = 'food'

For ecommerce (Shopee, Tokopedia):
- extract all products with qty and unit price
- set category = 'belanja'

Set confidence_score based on:
- 0.9-1.0: digital receipt, all fields clearly readable
- 0.7-0.89: digital but some fields missing or ambiguous
- 0.5-0.69: photo/scan, partially readable
- 0.0-0.49: handwritten or very low quality

Always set needs_review = true if confidence_score < 0.75
Return null for any field that cannot be determined.
PROMPT;

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ])->timeout(30)->post('https://api.mistral.ai/v1/chat/completions', [
            'model'       => 'mistral-small-latest',
            'messages'    => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user',   'content' => "Receipt text:\n{$rawText}"],
            ],
            'max_tokens'  => 2000,
            'temperature' => 0,
        ]);

        if (!$response->successful()) return [];

        $content = $response->json('choices.0.message.content', '');
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Map schema array to typed OcrResult column values.
     */
    private function mapSchemaToColumns(array $schema): array
    {
        return [
            'platform'         => $schema['platform'] ?? null,
            'category'         => $schema['category'] ?? null,
            'transaction_id'   => $schema['transaction_id'] ?? null,
            'transaction_date' => $schema['transaction_date'] ?? null,
            'transaction_time' => $schema['transaction_time'] ?? null,
            'vendor_name'      => $schema['vendor_name'] ?? null,
            'employee_name'    => $schema['employee_name'] ?? null,
            'subtotal'         => isset($schema['subtotal'])      ? (int) $schema['subtotal']      : null,
            'discount'         => isset($schema['discount'])      ? (int) $schema['discount']      : null,
            'delivery_fee'     => isset($schema['delivery_fee'])  ? (int) $schema['delivery_fee']  : null,
            'service_fee'      => isset($schema['service_fee'])   ? (int) $schema['service_fee']   : null,
            'tax'              => isset($schema['tax'])           ? (int) $schema['tax']           : null,
            'total_amount'     => isset($schema['total_amount'])  ? (int) $schema['total_amount']  : null,
            'payment_method'   => $schema['payment_method'] ?? null,
            'source_type'      => $schema['source_type'] ?? null,
            'confidence_score' => isset($schema['confidence_score']) ? (float) $schema['confidence_score'] : null,
            'needs_review'     => (bool) ($schema['needs_review'] ?? false),
        ];
    }
}
