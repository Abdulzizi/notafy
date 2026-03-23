<footer class="footer">
    <div class="footer-brand">
        <img src="/images/logo-full.svg" alt="Notafy" height="28" style="display:block;">
    </div>
    <div class="footer-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('pricing') }}">Pricing</a>
        <a href="{{ route('faq') }}">FAQ</a>
        <a href="{{ route('contact') }}">Contact</a>
    </div>
    <div class="footer-links" style="margin-top:0.25rem;">
        <a href="{{ route('terms') }}" style="font-size:0.8rem;color:var(--muted);">Terms</a>
        <a href="{{ route('privacy') }}" style="font-size:0.8rem;color:var(--muted);">Privacy</a>
    </div>
    <div class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
</footer>
