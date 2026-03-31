<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function checkoutMidtrans(Request $request)
    {
        $pack   = $request->query('pack', 'pro');
        $amount = match ($pack) {
            'starter' => 29000,
            default   => 99000,
        };

        $user      = $request->user();
        $orderId   = 'INV-' . strtoupper($pack) . '-' . time();

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id'       => $pack,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => ucfirst($pack) . ' — 1 Month',
                ],
            ],
            'customer_details' => [
                'first_name' => $user->name ?? $user->email,
                'email'      => $user->email,
            ],
            'callbacks' => [
                'finish' => route('billing.success'),
            ],
            'notification_url' => route('midtrans.webhook'),
        ];

        $serverKey = config('services.midtrans.server_key');
        $baseUrl   = config('services.midtrans.env') === 'production'
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                ->post($baseUrl, $payload);
        } catch (\Throwable $e) {
            \Log::error('Midtrans checkout HTTP error', ['error' => $e->getMessage()]);
            return redirect()->route('checkout', $pack)
                ->with('warning', 'Payment service unavailable. Please try again later.');
        }

        $redirectUrl = $response->json('redirect_url');

        if (!$redirectUrl) {
            \Log::error('Midtrans checkout: no redirect_url returned', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return redirect()->route('checkout', $pack)
                ->with('warning', 'Could not create Midtrans payment. Please try again later.');
        }

        return redirect($redirectUrl);
    }

    public function webhookMidtrans(Request $request)
    {
        $orderId           = $request->input('order_id') ?? '';
        $statusCode        = $request->input('status_code') ?? '';
        $grossAmount       = $request->input('gross_amount') ?? '';
        $incomingSignature = $request->input('signature_key') ?? '';
        $transactionStatus = $request->input('transaction_status') ?? '';

        $serverKey        = config('services.midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($serverKey && !hash_equals($expectedSignature, $incomingSignature)) {
            \Log::warning('Midtrans webhook signature mismatch');
            return response('Unauthorized', 401);
        }

        if (!in_array($transactionStatus, ['settlement', 'capture'])) {
            return response('ok');
        }

        if ($orderId && CreditTransaction::where('midtrans_transaction_id', $orderId)->exists()) {
            \Log::info('Midtrans webhook: duplicate transaction ignored', ['order_id' => $orderId]);
            return response('ok');
        }

        $email = $request->input('customer_details.email');
        $user  = $email ? User::where('email', $email)->first() : null;

        if (!$user) {
            \Log::info('Midtrans webhook: no user found for email');
            return response('ok');
        }

        $amount = (int) round((float) $grossAmount);
        $pack   = $this->packFromAmount($amount);

        if (!$pack) {
            \Log::warning('Midtrans webhook: unrecognised amount', ['amount' => $amount]);
            return response('ok');
        }

        $credits = $pack === 'starter' ? 200 : 1000;

        $user->update([
            'plan'                    => $pack,
            'billing_gateway'         => 'midtrans',
            'subscription_expires_at' => now()->addMonth(),
            'credits'                 => $credits,
            'credits_last_refilled_at' => now(),
        ]);

        CreditTransaction::record(
            $user->id,
            'purchase',
            $credits,
            ucfirst($pack) . ' subscription — Midtrans',
            $orderId,
        );

        \Log::info("Midtrans: activated {$pack} subscription for user {$user->id}");

        return response('ok');
    }

    private function packFromAmount(int $amount): ?string
    {
        // Rp 29.000 → starter, Rp 99.000 → pro (±10% tolerance for gateway fees)
        if ($amount >= 26100 && $amount <= 31900) return 'starter';
        if ($amount >= 89100 && $amount <= 108900) return 'pro';
        return null;
    }

    public function success(Request $request)
    {
        return redirect()->route('extract.index')
            ->with('status', 'Payment successful! Your subscription is now active.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('pricing')
            ->with('warning', 'Payment cancelled — you have not been charged.');
    }
}
