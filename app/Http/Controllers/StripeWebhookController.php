<?php

namespace App\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\User;
use Laravel\Cashier\Http\Controllers\WebhookController;

class StripeWebhookController extends WebhookController
{
    /**
     * Handle checkout.session.completed — fires after a one-time payment succeeds.
     * Metadata on the session carries user_id and pack (starter|pro).
     */
    public function handleCheckoutSessionCompleted(array $payload): \Symfony\Component\HttpFoundation\Response
    {
        $session  = $payload['data']['object'];
        $metadata = $session['metadata'] ?? [];

        $userId = $metadata['user_id'] ?? null;
        $pack   = $metadata['pack']    ?? null;

        if (!$userId || !$pack) {
            return response('ok');
        }

        $user = User::find($userId);
        if (!$user) {
            return response('ok');
        }

        $credits = match ($pack) {
            'starter' => 200,
            'pro'     => 1000,
            default   => 0,
        };

        if ($credits > 0) {
            $user->increment('credits', $credits);
            CreditTransaction::record($userId, 'purchase', $credits, ucfirst($pack) . ' Pack — Stripe');
            \Log::info("Stripe: added {$credits} credits to user {$userId} ({$pack} pack)");
        }

        return response('ok');
    }
}
