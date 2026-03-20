<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &mdash; {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="dash-layout">

        <aside class="sidebar">
            <div class="sidebar-top">
                <div class="sidebar-brand">{{ config('app.name') }}</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="nav-item active">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                        Overview
                    </a>
                    <a href="#" class="nav-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                        Profile
                    </a>
                    <a href="#" class="nav-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        Settings
                    </a>
                </nav>
            </div>

            <div class="sidebar-bottom">
                <div class="sidebar-user">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-email">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <div>
                    <h1 class="dash-title">Good to see you, <em>{{ explode(' ', auth()->user()->name)[0] }}</em></h1>
                    <p class="dash-subtitle">Here's what's going on with your account today.</p>
                </div>
                @if (!auth()->user()->hasVerifiedEmail())
                    <div class="verify-banner">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            style="width:16px;height:16px;flex-shrink:0">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Your email isn't verified.
                        <form method="POST" action="{{ route('verification.send') }}" style="display:inline">
                            @csrf
                            <button type="submit" class="verify-link">Send verification email</button>
                        </form>
                    </div>
                @endif
            </div>

            @if (session('status'))
                <div class="dash-alert">{{ session('status') }}</div>
            @endif

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Account status</div>
                    <div class="stat-value">
                        @if (auth()->user()->hasVerifiedEmail())
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warn">Unverified</span>
                        @endif
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Member since</div>
                    <div class="stat-value">{{ auth()->user()->created_at->format('M Y') }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Auth method</div>
                    <div class="stat-value">{{ auth()->user()->google_id ? 'Google SSO' : 'Password' }}</div>
                </div>
            </div>

            <div class="section-heading">Quick actions</div>
            <div class="actions-grid">
                <a href="#" class="action-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                    </svg>
                    <div>
                        <div class="action-title">Edit profile</div>
                        <div class="action-desc">Update your name and details</div>
                    </div>
                </a>
                <a href="#" class="action-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                    <div>
                        <div class="action-title">Change password</div>
                        <div class="action-desc">Keep your account secure</div>
                    </div>
                </a>
                <a href="#" class="action-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    <div>
                        <div class="action-title">Notifications</div>
                        <div class="action-desc">Manage your preferences</div>
                    </div>
                </a>
            </div>
        </main>

    </div>
</body>

</html>
