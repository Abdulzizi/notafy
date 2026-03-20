@extends('layouts.dashboard')

@section('title', 'Checkout — ' . ucfirst($plan) . ' Pack')

@section('content')
<div class="extract-page">
    <div class="extract-empty" style="min-height:60vh;max-width:480px;margin:0 auto;">

        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:40px;height:40px;color:var(--accent);">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
            <line x1="1" y1="10" x2="23" y2="10" />
        </svg>

        <h2 class="dash-title" style="margin-top:1rem;">
            {{ $plan === 'starter' ? 'Starter Pack — 200 credits' : 'Pro Pack — 1000 credits' }}
        </h2>
        <p class="dash-subtitle">
            {{ $plan === 'starter' ? 'Rp 29.000' : 'Rp 99.000' }} &mdash; one-time purchase
        </p>

        <div style="display:grid;gap:0.75rem;width:100%;margin-top:2rem;">

            {{-- Stripe --}}
            <a href="{{ route('billing.checkout.stripe', ['pack' => $plan]) }}"
               class="hero-cta"
               style="justify-content:center;gap:0.6rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="1" y="4" width="22" height="16" rx="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
                Pay with Card (Stripe)
            </a>

            {{-- Mayar popup --}}
            <button id="mayar-btn"
                    class="hero-cta"
                    style="justify-content:center;gap:0.6rem;background:var(--surface);border:1px solid var(--border);color:var(--text);cursor:pointer;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                Pay with Mayar (IDR)
            </button>

        </div>

        <a href="{{ route('pricing') }}" style="margin-top:1.5rem;font-size:0.85rem;color:var(--muted);text-decoration:none;">
            ← Back to pricing
        </a>

    </div>
</div>

{{-- Mayar popup script --}}
<script src="https://app.mayar.id/payment/popup.js" defer></script>
<script>
    document.getElementById('mayar-btn').addEventListener('click', function () {
        const pack = @json($plan);

        fetch(`/billing/checkout/mayar-url?pack=${pack}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (window.MayarPopup) {
                window.MayarPopup.open(data.url);
            } else {
                // Fallback: direct redirect if popup script didn't load
                window.location.href = data.url;
            }
        })
        .catch(() => alert('Could not load payment. Please try again.'));
    });
</script>
@endsection
