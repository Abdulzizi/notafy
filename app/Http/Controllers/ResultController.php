<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\OcrResult;
use App\Services\MistralOcrService;

class ResultController extends Controller
{
    public function show(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        return view('pages.result', compact('ocr'));
    }

    public function destroy(OcrResult $ocr)
    {
        $ocr->authorizeOwner();
        $ocr->deleteFiles();
        $ocr->delete();
        return redirect()->route('history')->with('status', 'Nota dihapus.');
    }

    public function download(OcrResult $ocr, string $format)
    {
        $ocr->authorizeOwner();
        abort_unless(auth()->user()->isStarter(), 403);
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
        $ocr->authorizeOwner();
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
