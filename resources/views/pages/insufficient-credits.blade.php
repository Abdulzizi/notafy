@extends('layouts.dashboard')

@section('title', 'Out of Credits')

@section('content')
<div class="extract-page">
    <div class="extract-empty" style="min-height:60vh;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 8v4M12 16h.01" />
        </svg>
        <h2 class="dash-title" style="margin-top:1rem;">Out of Credits</h2>
        <p class="dash-subtitle">You've used all your credits. Purchase more to continue extracting receipts.</p>
        <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('pricing') }}" class="hero-cta">Buy More Credits</a>
            <a href="{{ route('extract.index') }}" class="hero-cta" style="background:transparent;border:1px solid var(--border);color:var(--text);">Back to Extract</a>
        </div>
    </div>
</div>
@endsection
