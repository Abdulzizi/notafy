<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\OcrResult;
use App\Services\MistralOcrService;
use Illuminate\Support\Facades\Storage;

class ResultController extends Controller
{
    public function show(OcrResult $ocr)
    {
        abort_if($ocr->user_id !== auth()->id(), 403);
        return view('pages.result', compact('ocr'));
    }

    public function destroy(OcrResult $ocr)
    {
        abort_if($ocr->user_id !== auth()->id(), 403);
        Storage::disk('local')->delete($ocr->file_path);
        if ($ocr->preview_path) {
            Storage::disk('local')->delete($ocr->preview_path);
        }
        $ocr->delete();
        return redirect()->route('history')->with('status', 'Nota dihapus.');
    }

    public function download(OcrResult $ocr, string $format)
    {
        abort_if($ocr->user_id !== auth()->id(), 403);
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

    public function rerun(OcrResult $ocr)
    {
        abort_if($ocr->user_id !== auth()->id(), 403);
        abort_if($ocr->status !== 'done', 422);

        $user = auth()->user();

        if ($user->credits <= 0) {
            return redirect()->route('credits.insufficient');
        }

        $user->decrement('credits');
        CreditTransaction::record($user->id, 'extraction', -1, 'Re-run: ' . $ocr->filename);
        $ocr->update(['status' => 'processing']);

        try {
            $service = app(MistralOcrService::class);
            $columns = $service->reprocess($ocr->file_path, $ocr->file_type);
            $ocr->update($columns);
        } catch (\Throwable $e) {
            $user->increment('credits');
            CreditTransaction::record($user->id, 'refund', 1, 'Re-run failed — credit refunded');
            $ocr->update(['status' => 'done']);
            \Log::error('Rerun failed: ' . $e->getMessage());
            return back()->withErrors(['rerun' => 'Processing failed. Credit refunded.']);
        }

        return redirect()->route('result.show', $ocr->id)
            ->with('status', 'Receipt re-processed successfully.');
    }
}
