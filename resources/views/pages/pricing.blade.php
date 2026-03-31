@extends('layouts.app')

@section('title', 'Pricing — Notafy')
@section('description', 'Start free with 10 credits a month. Upgrade to Starter or Pro for more credits, exports, and unlimited history.')

@section('content')
<div class="pricing-page">
    <div class="pricing-header">
        <h1 class="pricing-title">Simple monthly plans.</h1>
        <p class="pricing-subtitle">One credit per receipt. Starter is for individuals. Pro is for teams.</p>
    </div>

    @unless(config('app.payments_enabled'))
    <div style="margin:0 auto 2rem;max-width:620px;padding:1rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:12px;text-align:center;font-size:0.9rem;color:var(--muted);">
        Paid plans are <strong style="color:var(--text);">coming soon</strong> — our payment system is currently being reviewed. The free plan is fully available right now.
    </div>
    @endunless

    <div class="pricing-cards">

        {{-- Free --}}
        <div class="pricing-card">
            <div class="pricing-plan-name">Free</div>
            <div class="pricing-credits">10</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 0</div>
            <ul class="pricing-features">
                <li>10 extractions per month</li>
                <li>Resets on the 1st of each month</li>
                <li>Last 30 results in history</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <a href="{{ route('register') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Get started free
            </a>
        </div>

        {{-- Starter --}}
        @if(config('app.payments_enabled'))
        <div class="pricing-card">
            <div class="pricing-plan-name">Starter</div>
            <div class="pricing-credits">200</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 29.000 <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">/ mo</span></div>
            <ul class="pricing-features">
                <li>200 extractions per month</li>
                <li>Unlimited history</li>
                <li>Download results (.txt &amp; .pdf)</li>
                <li>Export history as CSV</li>
                <li>Mistral OCR engine</li>
            </ul>
            <a href="{{ route('checkout', 'starter') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Get Starter
            </a>
        </div>
        @else
        <div class="pricing-card" style="opacity:0.5;pointer-events:none;">
            <div class="pricing-plan-name">Starter</div>
            <div class="pricing-credits">200</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 29.000 <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">/ mo</span></div>
            <ul class="pricing-features">
                <li>200 extractions per month</li>
                <li>Unlimited history</li>
                <li>Download results (.txt &amp; .pdf)</li>
                <li>Export history as CSV</li>
                <li>Mistral OCR engine</li>
            </ul>
            <span class="ocr-submit" style="display:block;text-align:center;">Coming Soon</span>
        </div>
        @endif

        {{-- Pro --}}
        @if(config('app.payments_enabled'))
        <div class="pricing-card pricing-card--featured">
            <div class="pricing-plan-name">Pro</div>
            <div class="pricing-credits">1000</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 99.000 <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">/ mo</span></div>
            <ul class="pricing-features">
                <li>1000 extractions per month</li>
                <li>Unlimited history</li>
                <li>Download results (.txt &amp; .pdf)</li>
                <li>Export history as CSV <strong>+ Excel (.xlsx)</strong></li>
                <li>No ads</li>
                <li>Mistral OCR engine</li>
            </ul>
            <a href="{{ route('checkout', 'pro') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Get Pro
            </a>
        </div>
        @else
        <div class="pricing-card pricing-card--featured" style="opacity:0.5;pointer-events:none;">
            <div class="pricing-plan-name">Pro</div>
            <div class="pricing-credits">1000</div>
            <div class="pricing-credits-label">credits / month</div>
            <div class="pricing-price">Rp 99.000 <span style="font-size:0.75rem;font-weight:400;color:var(--muted);">/ mo</span></div>
            <ul class="pricing-features">
                <li>1000 extractions per month</li>
                <li>Unlimited history</li>
                <li>Download results (.txt &amp; .pdf)</li>
                <li>Export history as CSV <strong>+ Excel (.xlsx)</strong></li>
                <li>No ads</li>
                <li>Mistral OCR engine</li>
            </ul>
            <span class="ocr-submit" style="display:block;text-align:center;">Coming Soon</span>
        </div>
        @endif

    </div>

    <p style="text-align:center;color:var(--muted);font-size:0.8rem;margin-top:2rem;">
        Manual renewal — pay monthly via Midtrans (GoPay, QRIS, bank transfer, credit card).
        You will receive a reminder email 3 days before expiry.
    </p>
</div>
@endsection
