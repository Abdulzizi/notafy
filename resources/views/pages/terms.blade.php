@extends('layouts.app')

@section('title', 'Terms of Service')
@section('description', 'Terms of Service for Notafy. Read before using our receipt OCR service.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="margin-bottom:3rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">Legal</div>
        <h1 style="font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;">Terms of Service</h1>
        <p style="color:var(--muted);font-size:0.9rem;">Last updated: {{ date('F Y') }}</p>
    </div>

    <div style="color:var(--muted);line-height:1.8;font-size:0.95rem;">

        <p>By creating an account or using Notafy, you agree to these terms. Please read them carefully.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">1. The Service</h2>
        <p>Notafy is a receipt OCR tool that extracts text from uploaded images and PDF files using AI. We provide the platform and processing infrastructure; you provide the files.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">2. Accounts</h2>
        <p>You must provide a valid email address and keep your account credentials secure. You are responsible for all activity that occurs under your account. You must be at least 13 years old to use this service.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">3. Credits</h2>
        <p>Credits are consumed when you process a file (1 credit per extraction). Free accounts receive 10 credits each month. Purchased credit packs are one-time payments with no expiry date.</p>
        <p style="margin-top:0.75rem;"><strong style="color:var(--text);">No refunds.</strong> All credit purchases are final. We do not offer refunds for unused credits under any circumstances.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">4. Acceptable Use</h2>
        <p>You agree not to:</p>
        <ul style="margin:0.75rem 0 0.75rem 1.5rem;display:flex;flex-direction:column;gap:0.4rem;">
            <li>Upload files containing illegal content</li>
            <li>Attempt to reverse-engineer, scrape, or abuse the API</li>
            <li>Use the service to process other people's private documents without their consent</li>
            <li>Circumvent rate limits or credit checks</li>
        </ul>
        <p>We reserve the right to suspend or terminate accounts that violate these rules.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">5. Your Data</h2>
        <p>Files you upload remain yours. We store them solely to provide the extraction service and display results in your history. You can delete any result at any time, which permanently removes the file from our servers.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">6. Service Availability</h2>
        <p>We aim for high availability but do not guarantee uninterrupted service. We may perform maintenance, update features, or modify pricing with reasonable notice.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">7. Limitation of Liability</h2>
        <p>Notafy is provided "as is." We are not liable for any damages arising from use of the service, including loss of data or inaccurate extraction results. OCR output should always be reviewed before relying on it.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">8. Governing Law</h2>
        <p>These terms are governed by the laws of the Republic of Indonesia. Any disputes shall be resolved in Indonesian courts.</p>

        <h2 style="font-size:1.1rem;font-weight:600;color:var(--text);margin:2.5rem 0 0.75rem;">9. Changes</h2>
        <p>We may update these terms from time to time. Continued use of the service after changes constitutes acceptance. We will notify registered users of material changes by email.</p>

        <div style="margin-top:3rem;padding:1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:12px;">
            <p style="margin:0;">Questions? Email us at <a href="mailto:support@notafy.id" style="color:var(--accent);">support@notafy.id</a></p>
        </div>

    </div>

</main>
@endsection
