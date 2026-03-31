<header class="site-header">
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="/images/logo-full.svg" alt="Notafy" style="display:block;height:32px;width:auto;">
        </a>
        <div class="navbar-links">
            <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="navbar-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('pricing') }}"
                class="navbar-link {{ request()->routeIs('pricing') ? 'active' : '' }}">Pricing</a>
        </div>
        <div class="navbar-actions">
            @auth
                <a href="{{ route('extract.index') }}" class="nav-btn nav-btn-primary">Open App</a>
            @else
                <a href="{{ route('login') }}" class="nav-btn nav-btn-ghost">Sign in</a>
                <a href="{{ route('register') }}" class="nav-btn nav-btn-primary">Get started</a>
            @endauth
        </div>
    </nav>

    <nav class="mobile-navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="/images/logo-icon.svg" alt="Notafy" style="display:block;height:32px;width:auto;">
        </a>
        <button class="mobile-menu-btn" id="mobile-menu-toggle" aria-label="Menu">
            <span class="menu-icon">
                <span></span><span></span>
            </span>
        </button>
    </nav>
</header>

<div class="mobile-overlay" id="mobile-overlay">
    <div class="mobile-overlay-inner">
        <nav class="overlay-nav">
            <a href="{{ route('home') }}"
                class="overlay-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('about') }}"
                class="overlay-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
            <a href="{{ route('pricing') }}"
                class="overlay-link {{ request()->routeIs('pricing') ? 'active' : '' }}">Pricing</a>
        </nav>
        <div class="overlay-actions">
            @auth
                <a href="{{ route('extract.index') }}" class="overlay-cta">Open App</a>
            @else
                <a href="{{ route('register') }}" class="overlay-cta">Get started</a>
                <a href="{{ route('login') }}" class="overlay-secondary">Sign in</a>
            @endauth
        </div>
    </div>
</div>

<script>
    const toggle = document.getElementById('mobile-menu-toggle');
    const overlay = document.getElementById('mobile-overlay');
    toggle.addEventListener('click', () => {
        const open = overlay.classList.toggle('open');
        toggle.classList.toggle('open', open);
        document.body.style.overflow = open ? 'hidden' : '';
    });

    overlay.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            overlay.classList.remove('open');
            toggle.classList.remove('open');
            document.body.style.overflow = '';
        });
    });
</script>
