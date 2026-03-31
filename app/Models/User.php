<?php

namespace App\Models;

use App\Models\CreditTransaction;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'plan',
        'credits',
        'credits_last_refilled_at',
        'billing_gateway',
        'subscription_expires_at',
        'onboarding_dismissed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'          => 'datetime',
            'password'                   => 'hashed',
            'subscription_expires_at'    => 'datetime',
            'credits_last_refilled_at'   => 'datetime',
            'onboarding_dismissed_at'    => 'datetime',
        ];
    }

    public function identities()
    {
        return $this->hasMany(UserIdentity::class);
    }

    public function ocrResults()
    {
        return $this->hasMany(OcrResult::class);
    }

    public function isStarter(): bool
    {
        return in_array($this->plan, ['starter', 'pro'])
            && $this->subscription_expires_at
            && $this->subscription_expires_at->isFuture();
    }

    public function isPro(): bool
    {
        return $this->plan === 'pro'
            && $this->subscription_expires_at
            && $this->subscription_expires_at->isFuture();
    }

    public function refillCreditsIfDue(): void
    {
        $startOfMonth = now()->startOfMonth();

        if ($this->credits_last_refilled_at && $this->credits_last_refilled_at->gte($startOfMonth)) {
            return;
        }

        if ($this->plan === 'free') {
            $this->update(['credits' => 10, 'credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 10, 'Monthly credit reset');
            return;
        }

        if ($this->plan === 'starter' && $this->isStarter()) {
            $this->update(['credits' => 200, 'credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 200, 'Monthly credit reset — Starter');
            return;
        }

        if ($this->plan === 'pro' && $this->isPro()) {
            $this->update(['credits' => 1000, 'credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 1000, 'Monthly credit reset — Pro');
        }
    }
}
