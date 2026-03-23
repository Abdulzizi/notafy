@extends('layouts.app')

@section('title', 'FAQ')
@section('description', 'Frequently asked questions about Notafy — credits, files, payments, and more.')

@section('content')
<main style="max-width:760px;margin:0 auto;padding:6rem 1.5rem 4rem;">

    <div style="margin-bottom:3rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem;">Help</div>
        <h1 style="font-size:2rem;font-weight:700;color:var(--text);margin-bottom:0.5rem;">Frequently Asked Questions</h1>
        <p style="color:var(--muted);">Can't find an answer? <a href="{{ route('contact') }}" style="color:var(--accent);">Contact us</a>.</p>
    </div>

    @php
    $sections = [
        [
            'title' => 'Credits',
            'items' => [
                ['q' => 'How do credits work?', 'a' => 'Each extraction costs 1 credit. Free accounts get 10 credits every Monday. Purchased credits never expire and are added on top of your current balance.'],
                ['q' => 'What happens to my credits when they reset?', 'a' => 'The weekly reset on the free plan sets your balance back to 10 — it does not add 10 on top. If you have purchased credits, those are not affected by the reset; the reset only applies to free-plan users.'],
                ['q' => 'What if an extraction fails?', 'a' => 'If the extraction fails due to a processing error on our end, your credit is automatically refunded. You can see this in your account transaction history.'],
                ['q' => 'Can I get a refund for unused credits?', 'a' => 'No. All credit purchases are final. Please refer to our Terms of Service.'],
            ],
        ],
        [
            'title' => 'Files',
            'items' => [
                ['q' => 'What file types are supported?', 'a' => 'We support JPEG, PNG, and PDF files. Multi-page PDFs are processed page by page.'],
                ['q' => 'What is the maximum file size?', 'a' => 'Files must be under 10MB. For large PDFs, try splitting them into smaller files first.'],
                ['q' => 'Are my uploaded files private?', 'a' => 'Yes. Files are stored privately in your account and are never shared with other users. Only you can see your results. Deleting a result permanently removes the file from our servers.'],
                ['q' => 'How accurate is the extraction?', 'a' => 'Accuracy depends on the quality of the image. Clear, well-lit photos of printed receipts work best. Handwritten notes or very dark images may produce lower-quality results. You can re-run an extraction if the first result is unsatisfactory.'],
            ],
        ],
        [
            'title' => 'Payments',
            'items' => [
                ['q' => 'What payment methods are accepted?', 'a' => 'We accept credit/debit cards via Stripe (Visa, Mastercard), and Indonesian payment methods via Midtrans including QRIS, bank transfer, and e-wallets.'],
                ['q' => 'Is this a subscription?', 'a' => 'No. Credit packs are one-time purchases. There are no recurring charges unless you explicitly buy again.'],
                ['q' => 'Is my payment information secure?', 'a' => 'Yes. We never store your card or bank details. Payments are handled entirely by Stripe and Midtrans, which are PCI-compliant payment processors.'],
                ['q' => 'I paid but my credits didn\'t arrive. What do I do?', 'a' => 'Credits are usually added within a few seconds of payment confirmation. If they haven\'t arrived after 5 minutes, please email us at support@notafy.id with your order details.'],
            ],
        ],
        [
            'title' => 'Account',
            'items' => [
                ['q' => 'Can I sign in with Google?', 'a' => 'Yes. You can register and sign in with your Google account. If you originally registered with email and password, you can also link Google from your account settings.'],
                ['q' => 'Why do I need to verify my email?', 'a' => 'Email verification confirms your identity and ensures we can reach you for important account updates or password resets.'],
                ['q' => 'How do I delete my account?', 'a' => 'Email us at support@notafy.id and we will permanently delete your account and all associated data within 7 business days.'],
            ],
        ],
        [
            'title' => 'Results & History',
            'items' => [
                ['q' => 'How long are my results stored?', 'a' => 'Results are stored until you delete them. There is no automatic expiry.'],
                ['q' => 'How many results can I view in history?', 'a' => 'Free plan users can see the 30 most recent results. Starter and Pro plan users have unlimited history access.'],
                ['q' => 'Can I download my results?', 'a' => 'Yes — Starter and Pro plan users can download results as a .txt file or PDF. Free plan users can copy the extracted text directly from the result page.'],
                ['q' => 'What does "re-run" do?', 'a' => 'Re-run reprocesses an existing result with the latest AI model. This costs 1 credit. Use it if you are unhappy with the initial extraction quality.'],
            ],
        ],
    ];
    @endphp

    <div style="display:flex;flex-direction:column;gap:2.5rem;">
        @foreach($sections as $section)
        <div>
            <h2 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--accent);margin-bottom:1rem;">{{ $section['title'] }}</h2>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach($section['items'] as $item)
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem 1.5rem;">
                    <div style="font-weight:600;color:var(--text);margin-bottom:0.4rem;font-size:0.95rem;">{{ $item['q'] }}</div>
                    <div style="color:var(--muted);font-size:0.9rem;line-height:1.7;">{{ $item['a'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:3rem;text-align:center;padding:2rem;background:var(--surface);border:1px solid var(--border);border-radius:16px;">
        <p style="color:var(--muted);margin:0 0 0.75rem;">Still have questions?</p>
        <a href="{{ route('contact') }}" class="hero-cta" style="display:inline-flex;font-size:0.9rem;padding:0.65rem 1.5rem;">Get in touch</a>
    </div>

</main>
@endsection
