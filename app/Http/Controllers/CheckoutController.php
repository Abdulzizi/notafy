<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(string $plan)
    {
        return view('pages.checkout', ['plan' => $plan]);
    }
}
