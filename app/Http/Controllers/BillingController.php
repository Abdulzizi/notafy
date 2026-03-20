<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    // ──────────────────────────────────────────
    // Stripe (one-time payment per credit pack)
    // ──────────────────────────────────────────

    public function checkoutStripe(Request $request)
    {
        $pack = $request->query('pack', 'pro');

        $priceId = match ($pack) {
            'starter' => config('cashier.starter_price_id'),
            default   => config('cashier.pro_price_id'),
        };

        abort_unless($priceId, 500, 'Stripe price ID not configured for this pack.');

        $user = $request->user();

        // One-time payment session with metadata so the webhook knows who paid and for what
        return $user->checkout($priceId, [
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('billing.cancel'),
            'payment_intent_data' => [
                'metadata' => [
                    'user_id' => $user->id,
                    'pack'    => $pack,
                ],
            ],
            'metadata' => [
                'user_id' => $user->id,
                'pack'    => $pack,
            ],
        ]);
    }

    public function portal(Request $request)
    {
        return $request->user()->redirectToBillingPortal(route('extract.index'));
    }

    // ──────────────────────────────────────────
    // Mayar (popup payment per credit pack)
    // ──────────────────────────────────────────

    /**
     * Returns the Mayar payment URL for a given pack.
     * Called via JS on the checkout page to get the URL before opening the popup.
     */
    public function mayarUrl(Request $request)
    {
        $pack = $request->query('pack', 'pro');

        $url = match ($pack) {
            'starter' => config('services.mayar.starter_payment_url'),
            default   => config('services.mayar.pro_payment_url'),
        };

        abort_unless($url, 500, 'Mayar payment URL not configured for this pack.');

        return response()->json(['url' => $url]);
    }

    public function webhookMayar(Request $request)
    {
        // Verify HMAC signature
        $secret    = config('services.mayar.webhook_secret');
        $signature = $request->header('X-Mayar-Signature') ?? '';
        $expected  = hash_hmac('sha256', $request->getContent(), $secret);

        if ($secret && !hash_equals($expected, $signature)) {
            \Log::warning('Mayar webhook signature mismatch');
            return response('Unauthorized', 401);
        }

        \Log::info('Mayar webhook received', $request->all());

        $event = $request->input('event') ?? $request->input('status');
        $email = $request->input('data.customerEmail')
            ?? $request->input('data.email')
            ?? $request->input('customerEmail')
            ?? $request->input('email');

        $user = $email ? User::where('email', $email)->first() : null;

        if (!$user) {
            \Log::info('Mayar webhook: no user found for email', ['email' => $email]);
            return response('ok');
        }

        $paidEvents = ['payment.success', 'paid', 'payment_link.paid'];

        if (in_array($event, $paidEvents)) {
            // Identify which pack by amount (Rp 29.000 = starter, Rp 99.000 = pro)
            $amount  = (int) ($request->input('data.amount') ?? $request->input('amount') ?? 0);
            $credits = $this->creditsFromAmount($amount);

            if ($credits > 0) {
                $user->increment('credits', $credits);
                $pack = $credits === 200 ? 'Starter' : 'Pro';
                CreditTransaction::record($user->id, 'purchase', $credits, "{$pack} Pack — Mayar");
                \Log::info("Mayar: added {$credits} credits to user {$user->id} (amount: {$amount})");
            }
        }

        return response('ok');
    }

    private function creditsFromAmount(int $amount): int
    {
        // Rp 29.000 → 200 credits (starter), Rp 99.000 → 1000 credits (pro)
        // Allow ±10% tolerance for gateway fees
        if ($amount >= 26100 && $amount <= 31900) return 200;
        if ($amount >= 89100 && $amount <= 108900) return 1000;
        return 0;
    }

    // ──────────────────────────────────────────
    // Shared
    // ──────────────────────────────────────────

    public function success(Request $request)
    {
        return redirect()->route('extract.index')
            ->with('status', 'Payment successful! Credits have been added to your account.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('pricing')
            ->with('warning', 'Payment cancelled — you have not been charged.');
    }
}
