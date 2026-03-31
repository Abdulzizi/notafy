@extends('layouts.dashboard')

@section('title', 'Checkout — ' . ucfirst($plan) . ' Plan')

@section('content')
<div class="extract-page">
    <div class="extract-empty" style="min-height:60vh;max-width:480px;margin:0 auto;">

        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;color:var(--accent);">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
            <line x1="1" y1="10" x2="23" y2="10" />
        </svg>

        <h2 class="dash-title" style="margin-top:1rem;">
            {{ $plan === 'starter' ? 'Starter — 200 credits/month' : 'Pro — 1000 credits/month' }}
        </h2>
        <p class="dash-subtitle">
            {{ $plan === 'starter' ? 'Rp 29.000' : 'Rp 99.000' }} &mdash; per month
        </p>

        <div style="display:grid;gap:0.75rem;width:100%;margin-top:2rem;">
            <a href="{{ route('billing.checkout.midtrans', ['pack' => $plan]) }}"
               class="hero-cta"
               style="justify-content:center;gap:0.6rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                Subscribe with Midtrans
            </a>
        </div>

        <p style="margin-top:1rem;font-size:0.8rem;color:var(--muted);text-align:center;">
            Manual renewal — you will receive a reminder email 3 days before your subscription expires.
        </p>

        <a href="{{ route('pricing') }}" style="margin-top:1.5rem;font-size:0.85rem;color:var(--muted);text-decoration:none;">
            ← Back to pricing
        </a>

    </div>
</div>

@endsection
