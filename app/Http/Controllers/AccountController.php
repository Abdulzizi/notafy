<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function destroy(Request $request)
    {
        $user = $request->user();

        foreach ($user->ocrResults as $ocr) {
            $ocr->deleteFiles();
        }

        $user->ocrResults()->delete();
        CreditTransaction::where('user_id', $user->id)->delete();
        $user->identities()->delete();

        if ($user->stripe_id && $user->subscribed()) {
            $user->subscription()->cancelNow();
        }

        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Your account has been permanently deleted.');
    }
}
