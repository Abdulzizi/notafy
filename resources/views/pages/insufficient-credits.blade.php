@extends('layouts.dashboard')

@section('title', 'Out of Credits')

@section('content')
<div class="extract-page">
    <div class="extract-empty" style="min-height:60vh;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 8v4M12 16h.01" />
        </svg>
        <h2 class="dash-title" style="margin-top:1rem;">You are out of credits</h2>
        <p class="dash-subtitle">Your credit balance hit zero. Pick up a pack and get back to extracting in under a minute.</p>
        <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('pricing') }}" class="hero-cta">Get more credits</a>
            <a href="{{ route('extract.index') }}" class="hero-cta" style="background:transparent;border:1px solid var(--border);color:var(--text);">Go back</a>
        </div>
    </div>
</div>
@endsection
