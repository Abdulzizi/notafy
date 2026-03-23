<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ExtractController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OcrController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StripeWebhookController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('extract.index')
        : view('pages.landing');
})->name('home');

Route::get('/about',   fn() => view('about'))->name('about');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/terms',   fn() => view('pages.terms'))->name('terms');
Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');
Route::get('/contact', fn() => view('pages.contact'))->name('contact');
Route::get('/faq',     fn() => view('pages.faq'))->name('faq');
Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('auth.google.callback');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('extract.index')->with('status', 'Email verified! Welcome to Notafy.');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::prefix('ocr')->name('ocr.')->group(function () {
        Route::get('/file/{ocr}', [OcrController::class, 'serveFile'])->name('file');
        Route::get('/preview/{ocr}', [OcrController::class, 'servePreview'])->name('preview');
    });

    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/checkout/stripe',    [BillingController::class, 'checkoutStripe'])->name('checkout.stripe');
        Route::get('/checkout/midtrans',  [BillingController::class, 'checkoutMidtrans'])->name('checkout.midtrans');
        Route::get('/success',           [BillingController::class, 'success'])->name('success');
        Route::get('/portal',            [BillingController::class, 'portal'])->name('portal');
        Route::get('/cancel',            [BillingController::class, 'cancel'])->name('cancel');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/extract', [ExtractController::class, 'index'])->name('extract.index');
    Route::post('/extract', [ExtractController::class, 'extract'])
        ->middleware('throttle:30,1')
        ->name('extract.submit');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');

    Route::get('/result/{ocr}', [ResultController::class, 'show'])->name('result.show');
    Route::delete('/result/{ocr}', [ResultController::class, 'destroy'])->name('result.destroy');
    Route::get('/result/{ocr}/download/{format}', [ResultController::class, 'download'])
        ->name('result.download')
        ->where('format', 'txt|pdf');
    Route::post('/result/{ocr}/rerun', [ResultController::class, 'rerun'])
        ->middleware('throttle:30,1')
        ->name('result.rerun');

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');

    Route::get('/credits/insufficient', fn() => view('pages.insufficient-credits'))->name('credits.insufficient');
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
Route::post('/midtrans/webhook', [BillingController::class, 'webhookMidtrans'])->name('midtrans.webhook');
