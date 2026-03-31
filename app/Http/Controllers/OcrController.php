<?php

namespace App\Http\Controllers;

use App\Models\OcrResult;
use App\Services\MistralOcrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OcrController extends Controller
{
    public function index()
    {
        $recent = auth()->user()->ocrResults()
            ->latest()
            ->take(5)
            ->get();

        return view('ocr.index', compact('recent'));
    }

    public function upload(Request $request)
    {
        return redirect()->route('extract.index');
    }

    public function result(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        return view('ocr.result', compact('ocr'));
    }

    public function rerun(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        abort_if($ocr->status !== 'done', 422);

        $user = auth()->user();

        if ($user->credits <= 0) {
            return redirect()->route('credits.insufficient');
        }

        $user->decrement('credits');
        $ocr->update(['status' => 'processing']);

        try {
            $service = app(MistralOcrService::class);
            $fullPath = Storage::disk('local')->path($ocr->file_path);

            $tmpFile = new \Illuminate\Http\UploadedFile(
                $fullPath,
                $ocr->filename,
                null,
                null,
                true
            );
            $updated = $service->extract($tmpFile, $user->id);

            $ocr->update(array_merge(
                $updated->only([
                    'extracted_text', 'platform', 'category', 'transaction_id',
                    'transaction_date', 'transaction_time', 'vendor_name', 'employee_name',
                    'subtotal', 'discount', 'delivery_fee', 'service_fee', 'tax',
                    'total_amount', 'payment_method', 'source_type', 'confidence_score',
                    'needs_review', 'status',
                ])->toArray()
            ));
            $updated->delete();
        } catch (\Throwable $e) {
            $user->increment('credits');
            $ocr->update(['status' => 'done']);
            \Log::error('Rerun failed: ' . $e->getMessage());
            return back()->withErrors(['rerun' => 'Processing failed. Credit refunded.']);
        }

        return redirect()->route('ocr.result', $ocr->id)
            ->with('status', 'Receipt re-processed successfully.');
    }

    public function destroy(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        $ocr->deleteFiles();
        $ocr->delete();
        return redirect()->route('ocr.index')->with('status', 'Nota dihapus.');
    }

    public function history()
    {
        $user = auth()->user();
        $query = OcrResult::where('user_id', $user->id)->latest();
        if (!$user->isPro()) {
            $query->take(30);
        }
        $results = $query->paginate(15);
        $historyLimited = !$user->isPro();
        return view('ocr.history', compact('results', 'historyLimited'));
    }

    public function serveFile(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        $path = Storage::disk('local')->path($ocr->file_path);
        abort_if(!file_exists($path), 404);
        return response()->file($path);
    }

    public function servePreview(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        abort_if(!$ocr->preview_path, 404);
        $path = Storage::disk('local')->path($ocr->preview_path);
        abort_if(!file_exists($path), 404);
        return response()->file($path);
    }

    public function download(OcrResult $ocr, string $format)
    {
        $ocr->authorizeOwner();
        abort_unless(auth()->user()->isPro(), 403);
        abort_unless(in_array($format, ['txt', 'pdf']), 404);

        $filename = pathinfo($ocr->filename, PATHINFO_FILENAME);

        if ($format === 'txt') {
            return response($ocr->extracted_text ?? '')
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}.txt\"");
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ocr.download-pdf', compact('ocr'));
        return $pdf->download("{$filename}.pdf");
    }
}
