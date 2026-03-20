@php $show = !auth()->user()?->isPro(); @endphp
@if($show)
    <div class="ad-slot ad-slot--{{ $size ?? 'leaderboard' }}">
        @if(config('services.adsense.client_id'))
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="{{ config('services.adsense.client_id') }}"
                 data-ad-slot="{{ $slot ?? config('services.adsense.default_slot') }}"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
        @else
            <div class="ad-placeholder">Ad</div>
        @endif
    </div>
@endif
