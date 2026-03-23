<?php

namespace App\Models;

use App\Models\CreditTransaction;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'plan',
        'credits',
        'credits_last_refilled_at',
        'billing_gateway',
        'pro_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'pro_until'               => 'datetime',
            'credits_last_refilled_at' => 'datetime',
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

    public function isPro(): bool
    {
        if ($this->plan !== 'pro') {
            return false;
        }

        if ($this->billing_gateway === 'mayar') {
            return $this->pro_until && $this->pro_until->isFuture();
        }

        return true;
    }

    public function refillCreditsIfDue(): void
    {
        if ($this->plan === 'free') {
            $startOfWeek = now()->startOfWeek();
            if ($this->credits_last_refilled_at && $this->credits_last_refilled_at->gte($startOfWeek)) {
                return;
            }
            $this->update(['credits' => 10, 'credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 10, 'Weekly credit reset');
            return;
        }

        if ($this->plan === 'starter') {
            $startOfMonth = now()->startOfMonth();
            if ($this->credits_last_refilled_at && $this->credits_last_refilled_at->gte($startOfMonth)) {
                return;
            }
            $this->update(['credits' => 200, 'credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 200, 'Monthly credit reset — Starter');
            return;
        }

        if ($this->plan === 'pro' && $this->isPro()) {
            $startOfMonth = now()->startOfMonth();
            if ($this->credits_last_refilled_at && $this->credits_last_refilled_at->gte($startOfMonth)) {
                return;
            }
            $this->increment('credits', 100);
            $this->update(['credits_last_refilled_at' => now()]);
            CreditTransaction::record($this->id, 'refill', 100, 'Monthly credit top-up — Pro');
        }
    }
}
