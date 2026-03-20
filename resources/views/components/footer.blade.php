<footer class="footer">
    <div class="footer-brand">
        <img src="/images/logo-full.svg" alt="Notafy" height="28" style="display:block;">
    </div>
    <div class="footer-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('pricing') }}">Pricing</a>
    </div>
    <div class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
</footer>
