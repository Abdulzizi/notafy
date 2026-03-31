@extends('layouts.app')

@section('title', 'Refund Policy — Notafy')
@section('description', 'Refund and return policy for Notafy credit purchases.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="margin-bottom:3rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">Legal</div>
        <h1 style="font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;">Refund Policy</h1>
        <p style="color:var(--muted);font-size:0.9rem;">Last updated: {{ date('F Y') }}</p>
    </div>

    <div style="color:var(--muted);line-height:1.8;font-size:0.95rem;">

        <p>This policy explains when and how you can request a refund for credit purchases made on Notafy.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">1. Credits Used in Extractions</h2>
        <p>Credits that have already been consumed to process a file are <strong style="color:var(--text);">non-refundable</strong>. Once an extraction is performed, the credit is spent regardless of the output quality. If an extraction fails due to a processing error on our end, the credit is automatically returned to your account.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">2. Unused Credits — 7-Day Refund Window</h2>
        <p>If you have purchased a credit pack and have not used any of those credits, you may request a full refund within <strong style="color:var(--text);">7 days of the purchase date</strong>. Refunds are only available for credits that remain unused at the time of the request.</p>
        <p style="margin-top:0.75rem;">Partial refunds (for partially used packs) are not available. If any credits from a pack have been used, the pack is no longer eligible for a refund.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">3. How to Request a Refund</h2>
        <p>To request a refund, email us at <a href="mailto:support@notafy.id" style="color:var(--accent);">support@notafy.id</a> with:</p>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.4rem;">
            <li>Subject: <em>Refund Request — [your order ID]</em></li>
            <li>Your registered email address</li>
            <li>The order ID or transaction reference from your payment confirmation</li>
            <li>Reason for the refund (optional)</li>
        </ul>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">4. Processing Time</h2>
        <p>Approved refunds are processed within <strong style="color:var(--text);">7–14 business days</strong>. The refund will be returned to the original payment method used at checkout. Processing times may vary depending on your bank or payment provider.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">5. Failed Extractions</h2>
        <p>If a file fails to process due to a server-side error, the credit is automatically refunded to your Notafy balance — no action needed. These are credit refunds, not cash refunds.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">6. Contact</h2>
        <p>For any questions about this policy, contact us at <a href="mailto:support@notafy.id" style="color:var(--accent);">support@notafy.id</a>. We aim to respond within 1–2 business days.</p>

        <div style="margin-top:3rem;padding:1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:12px;">
            <p style="margin:0;">Refund requests: <a href="mailto:support@notafy.id" style="color:var(--accent);">support@notafy.id</a> — include your order ID in the subject line.</p>
        </div>

    </div>

</main>
@endsection
