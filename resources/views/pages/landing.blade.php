@extends('layouts.app')

@section('title', 'Notafy — Read Any Receipt in Seconds')
@section('description', 'Upload a receipt photo or PDF and get clean structured text in seconds. Free to start, no card needed.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="text-align:center;margin-bottom:3.5rem;">
        <h1 style="font-family:'DM Serif Display',serif;font-size:clamp(2.2rem,5vw,3.4rem);line-height:1.15;margin-bottom:1.25rem;color:var(--text);">
            Your receipts,<br><em>readable in seconds</em>
        </h1>
        <p style="font-size:1.1rem;color:var(--muted);max-width:520px;margin:0 auto 2rem;">
            Take a photo of any receipt or upload a PDF. Notafy pulls out the text for you, clean and ready to use.
        </p>
        <a href="{{ route('register') }}" class="hero-cta" style="font-size:1rem;padding:0.85rem 2rem;">
            Start free, get 10 credits
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
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">Any format works</div>
            <div style="font-size:0.85rem;color:var(--muted);">JPG, PNG, or multi-page PDF. If you can photograph it, we can read it.</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;">
            <div style="margin-bottom:0.75rem;color:var(--accent);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">Results in seconds</div>
            <div style="font-size:0.85rem;color:var(--muted);">Structured text lands in under 10 seconds. No waiting, no guessing.</div>
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
            <div style="font-weight:500;margin-bottom:0.35rem;color:var(--text);">Nothing gets lost</div>
            <div style="font-size:0.85rem;color:var(--muted);">Every past result stays in your account, searchable and ready to copy.</div>
        </div>
    </div>

    <div style="text-align:center;padding:2.5rem;background:var(--surface);border:1px solid var(--border);border-radius:16px;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1rem;">Pick the size that fits</div>
        <div style="display:flex;justify-content:center;gap:3rem;flex-wrap:wrap;margin-bottom:1.5rem;">
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Free</div>
                <div style="font-size:0.85rem;color:var(--muted);">10 credits/month</div>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Starter</div>
                <div style="font-size:0.85rem;color:var(--muted);">200 credits</div>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:var(--text);">Pro</div>
                <div style="font-size:0.85rem;color:var(--muted);">1000 credits</div>
            </div>
        </div>
        <a href="{{ route('pricing') }}" style="font-size:0.85rem;color:var(--accent);text-decoration:none;">See full pricing</a>
    </div>

</main>
@endsection
