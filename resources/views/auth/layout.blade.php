<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notafy') &mdash; Extract receipts instantly</title>
    <meta name="description" content="@yield('description', 'Notafy extracts structured data from any receipt photo or PDF using AI. Free to start.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Notafy') — Notafy">
    <meta property="og:description" content="@yield('description', 'Extract receipts instantly with AI.')">
    <meta property="og:image" content="{{ asset('images/og-image.svg') }}">
    <meta property="og:site_name" content="Notafy">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body>
<div class="page">
    <div class="panel">
        <div class="panel-brand">
            <a href="{{ route('home') }}" style="display:block;">
                <img src="/images/logo-full.svg" alt="Notafy" height="22" style="display:block;">
            </a>
        </div>
        {{-- <div class="panel-quote">@yield('panel-quote')</div> --}}
        <div class="panel-quote">{!! $__env->yieldContent('panel-quote') !!}</div>
        <div class="panel-footer">&copy; {{ date('Y') }} Notafy</div>
    </div>
    <div class="form-side">
        <div class="card">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>