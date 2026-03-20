<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') &mdash; {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    {{-- <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0c0c0e;
            --surface:   #131316;
            --border:    #222228;
            --muted:     #444450;
            --subtle:    #888896;
            --text:      #e8e8f0;
            --accent:    #c8b89a;
            --accent-dim:#7a6f5e;
            --danger:    #e07070;
            --success:   #70c898;
            --radius:    10px;
        }

        html, body {
            height: 100%;
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-weight: 300;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.6;
        }

        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .panel {
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .panel::after {
            content: '';
            position: absolute;
            bottom: -120px;
            left: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(200,184,154,0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .panel-brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            color: var(--accent);
            letter-spacing: -0.01em;
        }

        .panel-quote {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            line-height: 1.35;
            color: var(--text);
            opacity: 0.85;
            max-width: 360px;
        }

        .panel-quote em {
            font-style: italic;
            color: var(--accent);
        }

        .panel-footer {
            font-size: 0.75rem;
            color: var(--muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .form-side {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .card {
            width: 100%;
            max-width: 400px;
            animation: fadeUp 0.5s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-heading { margin-bottom: 2rem; }

        .card-heading h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            letter-spacing: -0.02em;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .card-heading p {
            font-size: 0.875rem;
            color: var(--subtle);
            line-height: 1.5;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            border: 1px solid;
        }

        .alert-error {
            background: rgba(224,112,112,0.08);
            border-color: rgba(224,112,112,0.25);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(112,200,152,0.08);
            border-color: rgba(112,200,152,0.25);
            color: var(--success);
        }

        .field { margin-bottom: 1.1rem; }

        label {
            display: block;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--subtle);
            margin-bottom: 0.4rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.75rem 1rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 300;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: var(--accent-dim);
            box-shadow: 0 0 0 3px rgba(200,184,154,0.08);
        }

        input.is-invalid { border-color: rgba(224,112,112,0.5); }

        .field-error {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 0.3rem;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .check-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .check-row label {
            margin: 0;
            font-size: 0.82rem;
            text-transform: none;
            letter-spacing: 0;
            color: var(--subtle);
            cursor: pointer;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.15s;
            text-align: center;
            text-decoration: none;
        }

        .btn:active { transform: scale(0.98); }

        .btn-primary {
            background: var(--accent);
            color: #0c0c0e;
        }

        .btn-primary:hover { opacity: 0.88; }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            margin-top: 0.65rem;
        }

        .btn-ghost:hover { border-color: var(--muted); }

        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
            color: var(--muted);
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .alt-link {
            text-align: center;
            margin-top: 1.75rem;
            font-size: 0.83rem;
            color: var(--subtle);
        }

        .alt-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .alt-link a:hover { text-decoration: underline; }

        .google-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }

        @media (max-width: 720px) {
            .page { grid-template-columns: 1fr; }
            .panel { display: none; }
            .form-side { padding: 2rem 1.25rem; }
        }
    </style> --}}

    @vite(['resources/css/app.css'])
</head>
<body>
<div class="page">
    <div class="panel">
        <div class="panel-brand">
            <img src="/images/logo-full.svg" alt="Notafy" height="22" style="display:block;">
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