<header class="app-header">
    <nav class="app-navbar">
        <div class="app-navbar-left">
            <a href="{{ route('extract.index') }}" class="app-navbar-brand">
                <img src="/images/logo-full.svg" alt="Notafy" style="display:block;height:32px;width:auto;">
            </a>
            <div class="app-nav-links">
                <a href="{{ route('extract.index') }}"
                    class="app-nav-link {{ request()->routeIs('extract.*') ? 'active' : '' }}">Extract</a>
                <a href="{{ route('history') }}"
                    class="app-nav-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a>
            </div>
        </div>
        <div class="app-navbar-right">
            @auth
                <span class="credits-badge">{{ auth()->user()->credits ?? 0 }} credits</span>
            @endauth
            <a href="{{ route('account') }}" class="app-user" style="text-decoration:none;">
                <div class="app-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="app-user-name">{{ auth()->user()->email }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="app-logout">Sign out</button>
            </form>
        </div>
    </nav>

    <nav class="mobile-navbar">
        <a href="{{ route('extract.index') }}" class="navbar-brand">
            <img src="/images/logo-icon.svg" alt="Notafy" style="display:block;height:32px;width:auto;">
        </a>
        <button class="mobile-menu-btn" id="app-menu-toggle" aria-label="Menu">
            <span class="menu-icon">
                <span></span><span></span>
            </span>
        </button>
    </nav>
</header>

<div class="mobile-overlay" id="app-overlay">
    <div class="mobile-overlay-inner">
        <nav class="overlay-nav">
            <a href="{{ route('extract.index') }}"
                class="overlay-link {{ request()->routeIs('extract.*') ? 'active' : '' }}">Extract</a>
            <a href="{{ route('history') }}"
                class="overlay-link {{ request()->routeIs('history') ? 'active' : '' }}">History</a>
            <a href="{{ route('account') }}"
                class="overlay-link {{ request()->routeIs('account') ? 'active' : '' }}">Account</a>
        </nav>
        <div class="overlay-actions">
            <div class="overlay-user">
                <div class="app-user-avatar" style="width:40px;height:40px;font-size:0.9rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:0.9rem;color:var(--text);font-weight:500;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.78rem;color:var(--muted);">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="width:100%;">
                @csrf
                <button type="submit" class="overlay-signout">Sign out</button>
            </form>
        </div>
    </div>
</div>

<script>
    const appToggle = document.getElementById('app-menu-toggle');
    const appOverlay = document.getElementById('app-overlay');
    appToggle.addEventListener('click', () => {
        const open = appOverlay.classList.toggle('open');
        appToggle.classList.toggle('open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });
    appOverlay.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            appOverlay.classList.remove('open');
            appToggle.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
</script>
