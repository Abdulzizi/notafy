<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(string $plan)
    {
        if (! config('app.payments_enabled')) {
            return redirect()->route('pricing')->with('info', 'Paid plans are coming soon. Enjoy the free plan for now!');
        }

        return view('pages.checkout', ['plan' => $plan]);
    }
}
