<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DowngradeExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:downgrade-expired';
    protected $description = 'Downgrade users whose subscriptions have expired and send renewal reminders';

    public function handle(): void
    {
        $this->downgradeExpired();
        $this->sendRenewalReminders();
    }

    private function downgradeExpired(): void
    {
        $expired = User::whereIn('plan', ['starter', 'pro'])
            ->where('subscription_expires_at', '<', now())
            ->get();

        foreach ($expired as $user) {
            $user->update([
                'plan'                    => 'free',
                'billing_gateway'         => null,
                'subscription_expires_at' => null,
            ]);

            \Log::info("Downgraded user {$user->id} to free (subscription expired)");
        }

        if ($expired->count() > 0) {
            $this->info("Downgraded {$expired->count()} expired subscription(s).");
        }
    }

    private function sendRenewalReminders(): void
    {
        $expiringSoon = User::whereIn('plan', ['starter', 'pro'])
            ->whereBetween('subscription_expires_at', [now(), now()->addDays(3)])
            ->get();

        foreach ($expiringSoon as $user) {
            try {
                Mail::to($user->email)->send(new \App\Mail\SubscriptionRenewalReminder($user));
                \Log::info("Renewal reminder sent to user {$user->id}");
            } catch (\Throwable $e) {
                \Log::error("Failed to send renewal reminder to user {$user->id}: " . $e->getMessage());
            }
        }

        if ($expiringSoon->count() > 0) {
            $this->info("Sent {$expiringSoon->count()} renewal reminder(s).");
        }
    }
}
