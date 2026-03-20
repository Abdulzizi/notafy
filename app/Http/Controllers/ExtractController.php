<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Services\MistralOcrService;
use Illuminate\Http\Request;

class ExtractController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->refillCreditsIfDue();

        return view('pages.extract', [
            'credits' => $user->fresh()->credits ?? 0,
        ]);
    }

    public function extract(Request $request)
    {
        $request->validate([
            'receipt' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $user = auth()->user();

        if ($user->credits <= 0) {
            return redirect()->route('credits.insufficient');
        }

        $user->decrement('credits');
        CreditTransaction::record($user->id, 'extraction', -1, 'Receipt extraction');

        try {
            $result = app(MistralOcrService::class)->extract(
                $request->file('receipt'),
                $user->id
            );
            return view('pages.extract', [
                'result'  => $result,
                'credits' => $user->fresh()->credits,
            ]);
        } catch (\Exception $e) {
            $user->increment('credits');
            CreditTransaction::record($user->id, 'refund', 1, 'Extraction failed — credit refunded');
            return back()->withErrors([
                'extract' => 'Failed to process receipt. Credit refunded.',
            ]);
        }
    }
}
