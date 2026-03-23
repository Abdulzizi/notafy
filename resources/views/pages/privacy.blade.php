@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('description', 'How Notafy collects, uses, and protects your data.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="margin-bottom:3rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">Legal</div>
        <h1 style="font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;">Privacy Policy</h1>
        <p style="color:var(--muted);font-size:0.9rem;">Last updated: {{ date('F Y') }}</p>
    </div>

    <div style="color:var(--muted);line-height:1.8;font-size:0.95rem;">

        <p>This policy explains what data we collect, how we use it, and your rights. We keep it short and plain.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">1. Data We Collect</h2>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.5rem;">
            <li><strong style="color:var(--text);">Account info</strong> — name and email address when you register</li>
            <li><strong style="color:var(--text);">Uploaded files</strong> — receipt images and PDFs you submit for extraction</li>
            <li><strong style="color:var(--text);">Extracted text</strong> — the OCR output stored in your history</li>
            <li><strong style="color:var(--text);">Usage data</strong> — credit balance, transaction history, and extraction timestamps</li>
            <li><strong style="color:var(--text);">Payment data</strong> — handled entirely by Stripe or Midtrans; we never see your card number or bank details</li>
        </ul>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">2. How We Use It</h2>
        <p>We use your data only to provide the service:</p>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.4rem;">
            <li>To process your receipt files and return extraction results</li>
            <li>To manage your account, credits, and transaction history</li>
            <li>To send transactional emails (email verification, password reset)</li>
        </ul>
        <p>We do not sell your data. We do not use your receipt contents for advertising or model training.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">3. Third-Party Services</h2>
        <p>We use the following processors to deliver the service:</p>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.5rem;">
            <li><strong style="color:var(--text);">Mistral AI</strong> — receives your uploaded files for OCR processing</li>
            <li><strong style="color:var(--text);">Stripe</strong> — handles card payments; subject to <a href="https://stripe.com/privacy" target="_blank" rel="noopener" style="color:var(--accent);">Stripe's Privacy Policy</a></li>
            <li><strong style="color:var(--text);">Midtrans</strong> — handles IDR payments (QRIS, bank transfer)</li>
            <li><strong style="color:var(--text);">Google</strong> — optional OAuth sign-in; subject to <a href="https://policies.google.com/privacy" target="_blank" rel="noopener" style="color:var(--accent);">Google's Privacy Policy</a></li>
        </ul>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">4. Data Storage & Retention</h2>
        <p>Your files and extracted text are stored on our servers for as long as you keep the result in your history. Deleting a result permanently removes the file from our servers. Your account data is retained until you delete your account.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">5. Your Rights</h2>
        <p>You can:</p>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.4rem;">
            <li>Delete individual results from your history at any time</li>
            <li>Request full account deletion by emailing us</li>
            <li>Ask what data we hold about you</li>
        </ul>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">6. Cookies</h2>
        <p>We use a session cookie to keep you logged in. No third-party tracking cookies are set. Google Analytics may be enabled in the future — this policy will be updated if so.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">7. Changes</h2>
        <p>We may update this policy. We will notify you by email for material changes. Continued use of the service constitutes acceptance.</p>

        <div style="margin-top:3rem;padding:1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:12px;">
            <p style="margin:0;">Privacy questions? Email <a href="mailto:support@notafy.id" style="color:var(--accent);">support@notafy.id</a></p>
        </div>

    </div>

</main>
@endsection
