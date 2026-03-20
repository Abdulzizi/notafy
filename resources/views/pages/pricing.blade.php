@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<div class="pricing-page">
    <div class="pricing-header">
        <h1 class="pricing-title">Simple, credit-based pricing</h1>
        <p class="pricing-subtitle">Every extraction costs 1 credit. Credits reset automatically each period.</p>
    </div>

    <div class="pricing-cards">

        <div class="pricing-card">
            <div class="pricing-plan-name">Free</div>
            <div class="pricing-credits">10</div>
            <div class="pricing-credits-label">credits / week</div>
            <div class="pricing-price">Rp 0</div>
            <ul class="pricing-features">
                <li>10 extractions per week</li>
                <li>Resets every Monday</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <a href="{{ route('register') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Get started free
            </a>
        </div>

        <div class="pricing-card">
            <div class="pricing-plan-name">Starter Pack</div>
            <div class="pricing-credits">200</div>
            <div class="pricing-credits-label">credits</div>
            <div class="pricing-price">Rp 29.000</div>
            <ul class="pricing-features">
                <li>200 receipt extractions</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <a href="{{ route('checkout', 'starter') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Buy Now
            </a>
        </div>

        <div class="pricing-card pricing-card--featured">
            <div class="pricing-plan-name">Pro Pack</div>
            <div class="pricing-credits">1000</div>
            <div class="pricing-credits-label">credits</div>
            <div class="pricing-price">Rp 99.000</div>
            <ul class="pricing-features">
                <li>1000 receipt extractions</li>
                <li>Mistral OCR engine</li>
                <li>Full structured JSON output</li>
            </ul>
            <a href="{{ route('checkout', 'pro') }}" class="ocr-submit" style="display:block;text-align:center;text-decoration:none;">
                Buy Now
            </a>
        </div>

    </div>
</div>
@endsection
