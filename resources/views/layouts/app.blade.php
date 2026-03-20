<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notafy') &mdash; Extract receipts instantly</title>
    <meta name="description" content="@yield('description', 'Notafy extracts structured data from any receipt photo or PDF using AI. Free to start.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Notafy') — Receipt OCR">
    <meta property="og:description" content="@yield('description', 'Extract receipts instantly with AI.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.svg') }}">
    <meta property="og:site_name" content="Notafy">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Notafy')">
    <meta name="twitter:description" content="@yield('description', 'Extract receipts instantly with AI.')">
    <meta name="twitter:image" content="{{ asset('images/og-image.svg') }}">

    <!-- Favicon -->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="any">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/notafy.css') }}">

    <!-- JSON-LD Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "Notafy",
      "applicationCategory": "BusinessApplication",
      "description": "AI-powered receipt OCR for Indonesian expense tracking",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "IDR"
      }
    }
    </script>
</head>

<body>
    <x-navbar />
    <div class="page-body">
        <main>@yield('content')</main>
        <x-footer />
    </div>

    @if(session('status') || session('error') || session('warning'))
    <div id="toast-container">
        @if(session('status'))
            <div class="toast toast--success" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast--error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="toast toast--warning" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                {{ session('warning') }}
            </div>
        @endif
    </div>
    <script>
        (function() {
            const toasts = document.querySelectorAll('#toast-container .toast');
            toasts.forEach((t, i) => {
                setTimeout(() => t.classList.add('toast--visible'), 50 + i * 150);
                setTimeout(() => { t.classList.remove('toast--visible'); setTimeout(() => t.remove(), 400); }, 4000 + i * 150);
            });
        })();
    </script>
    @endif

    <script src="{{ asset('js/notafy.js') }}" defer></script>
</body>

</html>
