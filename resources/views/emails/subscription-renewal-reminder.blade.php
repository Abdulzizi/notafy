<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; color: #333; max-width: 560px; margin: 0 auto; padding: 2rem 1rem; }
        .btn { display: inline-block; padding: 0.75rem 1.5rem; background: #6366f1; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .muted { color: #888; font-size: 0.85rem; }
    </style>
</head>
<body>
    <h2>Your Notafy subscription is expiring soon</h2>

    <p>Hi {{ $user->name ?? $user->email }},</p>

    <p>
        Your <strong>{{ ucfirst($user->plan) }} subscription</strong> expires on
        <strong>{{ $user->subscription_expires_at->format('d M Y') }}</strong> — that's in 3 days.
    </p>

    <p>To keep your access to all {{ ucfirst($user->plan) }} features (unlimited history, exports, and more), renew your subscription before it expires.</p>

    <p style="margin-top: 2rem;">
        <a href="{{ route('pricing') }}" class="btn">Renew Now</a>
    </p>

    <p class="muted" style="margin-top: 2rem;">
        If you don't renew, your account will revert to the Free plan (10 credits/month, last 30 results).
        Your existing extraction history will not be deleted.
    </p>

    <p class="muted">— The Notafy Team</p>
</body>
</html>
