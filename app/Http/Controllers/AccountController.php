<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $transactions = CreditTransaction::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        return view('pages.account', [
            'user'         => $user,
            'isPro'        => $user->isPro(),
            'credits'      => $user->credits ?? 0,
            'transactions' => $transactions,
        ]);
    }
}
