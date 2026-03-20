<?php

namespace App\Http\Controllers;

use App\Models\OcrResult;

class HistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = OcrResult::where('user_id', $user->id)->latest();

        if (!$user->isPro()) {
            $query->take(30);
        }

        $results = $query->paginate(15);
        $historyLimited = !$user->isPro();

        return view('pages.history', compact('results', 'historyLimited'));
    }
}
