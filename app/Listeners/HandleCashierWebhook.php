<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Cashier\Events\WebhookReceived;

class HandleCashierWebhook
{
    public function handle(WebhookReceived $event): void
    {
        $type    = $event->payload['type'] ?? '';
        $data    = $event->payload['data']['object'] ?? [];
        $stripeId = $data['customer'] ?? null;

        if (!$stripeId) {
            return;
        }

        $user = User::where('stripe_id', $stripeId)->first();

        if (!$user) {
            return;
        }

        if (in_array($type, ['customer.subscription.created', 'customer.subscription.updated'])) {
            $status = $data['status'] ?? '';
            if (in_array($status, ['active', 'trialing'])) {
                $user->update([
                    'plan'            => 'pro',
                    'billing_gateway' => 'stripe',
                ]);
            } else {
                $user->update([
                    'plan'            => 'free',
                    'billing_gateway' => null,
                ]);
            }
        } elseif (in_array($type, ['customer.subscription.deleted', 'invoice.payment_failed'])) {
            $user->update([
                'plan'            => 'free',
                'billing_gateway' => null,
            ]);
        }
    }
}
