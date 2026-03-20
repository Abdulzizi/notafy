@extends('layouts.dashboard')

@section('title', 'Account')

@section('content')
<div class="account-page" style="max-width:680px;margin:0 auto;padding:2rem 1rem;">

    <h1 class="dash-title" style="margin-bottom:2rem;">Account</h1>

    {{-- Credits & Plan --}}
    <div class="account-card" style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.25rem;">Credits</div>
                <div style="font-size:2rem;font-weight:700;color:var(--text);">{{ number_format($credits) }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.25rem;">Plan</div>
                @if($isPro)
                    <span class="pro-badge" style="font-size:0.95rem;">Pro</span>
                @else
                    <span style="font-size:0.95rem;color:var(--muted);">Free</span>
                @endif
            </div>
        </div>
        @if(!$isPro)
            <a href="{{ route('pricing') }}" class="hero-cta" style="width:100%;justify-content:center;display:flex;">Upgrade to Pro</a>
        @endif
    </div>

    {{-- Profile --}}
    <div class="account-card" style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Profile</div>
        <div style="display:grid;gap:0.75rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--muted);">Name</span>
                <span style="color:var(--text);font-weight:500;">{{ $user->name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--muted);">Email</span>
                <span style="color:var(--text);">{{ $user->email }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--muted);">Member since</span>
                <span style="color:var(--text);">{{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Billing --}}
    @if($isPro)
    <div class="account-card" style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Billing</div>
        <div style="display:grid;gap:0.75rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--muted);">Gateway</span>
                <span style="color:var(--text);font-weight:500;text-transform:capitalize;">{{ $user->billing_gateway ?? '—' }}</span>
            </div>
            @if($user->billing_gateway === 'mayar' && $user->pro_until)
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--muted);">Active until</span>
                <span style="color:var(--text);">{{ $user->pro_until->format('M d, Y') }}</span>
            </div>
            @endif
            @if($user->billing_gateway === 'stripe')
            <div style="margin-top:0.5rem;">
                <a href="{{ route('billing.portal') }}" class="hero-cta" style="width:100%;justify-content:center;display:flex;background:transparent;border:1px solid var(--border);color:var(--text);">Manage subscription</a>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Transaction history --}}
    @if($transactions->count())
    <div class="account-card" style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Credit History</div>
        <div style="display:grid;gap:0;">
            @foreach($transactions as $tx)
            @php
                $isPositive = $tx->credits > 0;
                $icon = match($tx->type) {
                    'purchase'  => '<path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z"/><path d="M9 12l2 2 4-4"/>',
                    'bonus'     => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                    'refill'    => '<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>',
                    'refund'    => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.87"/>',
                    default     => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
                };
            @endphp
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0;border-bottom:1px solid var(--border);last-child:border:none;">
                <div style="width:28px;height:28px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ $isPositive ? 'rgba(112,200,152,0.1)' : 'rgba(120,120,140,0.1)' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="{{ $isPositive ? '#70c898' : 'var(--muted)' }}" stroke-width="2" width="14" height="14">{!! $icon !!}</svg>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:0.85rem;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $tx->description }}</div>
                    <div style="font-size:0.75rem;color:var(--muted);">{{ $tx->created_at->format('M d, Y · H:i') }}</div>
                </div>
                <div style="font-size:0.9rem;font-weight:600;flex-shrink:0;color:{{ $isPositive ? '#70c898' : 'var(--muted)' }}">
                    {{ $isPositive ? '+' : '' }}{{ $tx->credits }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Credits refill info --}}
    @if($user->plan === 'pro')
    <p style="font-size:0.8rem;color:var(--muted);text-align:center;">
        +100 credits added monthly as a Pro member.
        @if($user->credits_last_refilled_at)
            Last refill: {{ $user->credits_last_refilled_at->format('M d, Y') }}.
        @endif
    </p>
    @elseif($user->plan === 'starter')
    <p style="font-size:0.8rem;color:var(--muted);text-align:center;">
        Credits reset to 200 on the 1st of each month.
        @if($user->credits_last_refilled_at)
            Last reset: {{ $user->credits_last_refilled_at->format('M d, Y') }}.
        @endif
    </p>
    @else
    <p style="font-size:0.8rem;color:var(--muted);text-align:center;">
        Free plan resets to 10 credits every Monday. <a href="{{ route('pricing') }}" style="color:var(--accent);">Upgrade</a> for more.
    </p>
    @endif

</div>
@endsection
