@extends('layouts.app')

@section('title', 'OCR Receipt Extractor')
@section('description', 'Extract text from any receipt instantly with AI. Free to start — no credit card required.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="text-align:center;margin-bottom:3.5rem;">
        <h1 style="font-family:'DM Serif Display',serif;font-size:clamp(2.2rem,5vw,3.4rem);line-height:1.15;margin-bottom:1.25rem;color:var(--text);">
            Extract text from<br><em>any receipt, instantly</em>
        </h1>
        <p style="font-size:1.1rem;color:var(--muted);max-width:520px;margin:0 auto 2rem;">
            Upload a receipt photo or PDF and get structured text in seconds. Powered by Mistral AI.
        </p>
        <a href="{{ route('register') }}" class="hero-cta" style="font-size:1rem;padding:0.85rem 2rem;">
            Start for free — 10 credits
        </a>
        <p style="margin-top:1rem;font-size:0.8rem;color:var(--muted);">No credit card required</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:4rem;">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
            <div style="margin-bottom:0.75rem;color:var(--accent);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">Photo or PDF</div>
            <div style="font-size:0.85rem;color:var(--muted);">Upload any receipt image or multi-page PDF</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
            <div style="margin-bottom:0.75rem;color:var(--accent);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">Instant extraction</div>
            <div style="font-size:0.85rem;color:var(--muted);">Structured text in under 10 seconds</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
            <div style="margin-bottom:0.75rem;color:var(--accent);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <polyline points="10 9 9 9 8 9"/>
                </svg>
            </div>
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">History</div>
            <div style="font-size:0.85rem;color:var(--muted);">All past extractions saved and accessible</div>
        </div>
    </div>

    <div style="text-align:center;padding:2.5rem;background:var(--surface);border:1px solid var(--border);border-radius:16px;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1rem;">Simple pricing</div>
        <div style="display:flex;justify-content:center;gap:3rem;flex-wrap:wrap;margin-bottom:1.5rem;">
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Free</div>
                <div style="font-size:0.85rem;color:var(--muted);">10 credits/week</div>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Starter</div>
                <div style="font-size:0.85rem;color:var(--muted);">200 credits/month</div>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Pro</div>
                <div style="font-size:0.85rem;color:var(--muted);">+100 credits/month</div>
            </div>
        </div>
        <a href="{{ route('pricing') }}" style="font-size:0.85rem;color:var(--accent);text-decoration:none;">See full pricing →</a>
    </div>

</main>
@endsection
