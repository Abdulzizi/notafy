@extends('layouts.app')

@section('title', 'Contact Us')
@section('description', 'Get in touch with the Notafy team.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="margin-bottom:3rem;text-align:center;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">Support</div>
        <h1 style="font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.75rem;">Contact Us</h1>
        <p style="color:var(--muted);max-width:480px;margin:0 auto;">We're a small team and we read every message. Expect a reply within 1–2 business days.</p>
    </div>

    <div style="display:grid;gap:1rem;max-width:480px;margin:0 auto;">

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:2rem;text-align:center;">
            <div style="color:var(--accent);margin-bottom:1rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>
            <div style="font-weight:600;color:var(--text);margin-bottom:0.4rem;">Email</div>
            <a href="mailto:support@notafy.id" style="color:var(--accent);font-size:1rem;text-decoration:none;">support@notafy.id</a>
        </div>

        <div style="background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:1.5rem;">
            <div style="font-weight:600;color:var(--text);margin-bottom:0.75rem;font-size:0.9rem;">Before reaching out, check:</div>
            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                <a href="{{ route('faq') }}" style="display:flex;align-items:center;gap:0.6rem;color:var(--muted);text-decoration:none;font-size:0.9rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Frequently Asked Questions
                </a>
                <a href="{{ route('pricing') }}" style="display:flex;align-items:center;gap:0.6rem;color:var(--muted);text-decoration:none;font-size:0.9rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2"/>
                        <line x1="1" y1="10" x2="23" y2="10"/>
                    </svg>
                    Pricing & Credits
                </a>
            </div>
        </div>

    </div>

</main>
@endsection
