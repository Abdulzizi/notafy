@extends('layouts.app')

@section('title', 'Pricing — Notafy')
@section('description', 'Start free with 10 credits a month. Buy a credit pack when you need more. No subscriptions.')

@section('content')
<div class="pricing-page">
    <div class="pricing-header">
        <h1 class="pricing-title">One credit per receipt. That is it.</h1>
        <p class="pricing-subtitle">Buy a pack once, use it whenever you need. No subscriptions, no surprise charges.</p>
    </div>

    @unless(config('app.payments_enabled'))
    <div style="margin:0 auto 2rem;max-width:620px;padding:1rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:12px;text-align:center;font-size:0.9rem;color:var(--muted);">
        Paid plans are <strong style="color:var(--text);">coming soon</strong> — our payment system is currently being reviewed. The free plan is fully available right now.
    </div>
    @endunless

    <div class="pricing-cards">

        <div class="pricing-card">
            <div class="pricing-plan-name">Free</div>
            <div class="pricing-credits">10</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 0</div>
            <ul class="pricing-features">
                <li>10 extractions per month</li>
                <li>Resets on the 1st of each month</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <a href="{{ route('register') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Get started free
            </a>
        </div>

        <div class="pricing-card" style="{{ config('app.payments_enabled') ? '' : 'opacity:0.5;pointer-events:none;' }}">
            <div class="pricing-plan-name">Starter Pack</div>
            <div class="pricing-credits">200</div>
            <div class="pricing-credits-label">credits</div>
            <div class="pricing-price">Rp 29.000</div>
            <ul class="pricing-features">
                <li>200 receipt extractions</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <span class="ocr-submit" style="display:block;text-align:center;">
                {{ config('app.payments_enabled') ? 'Get Starter' : 'Coming Soon' }}
            </span>
        </div>

        <div class="pricing-card pricing-card--featured" style="{{ config('app.payments_enabled') ? '' : 'opacity:0.5;pointer-events:none;' }}">
            <div class="pricing-plan-name">Pro Pack</div>
            <div class="pricing-credits">1000</div>
            <div class="pricing-credits-label">credits</div>
            <div class="pricing-price">Rp 99.000</div>
            <ul class="pricing-features">
                <li>1000 receipt extractions</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <span class="ocr-submit" style="display:block;text-align:center;">
                {{ config('app.payments_enabled') ? 'Get Pro' : 'Coming Soon' }}
            </span>
        </div>

    </div>
</div>
@endsection
