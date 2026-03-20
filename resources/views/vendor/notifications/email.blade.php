@component('mail::message')
# {{ $greeting ?? 'Hello!' }}

@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($actionText)
@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
{{ $actionText }}
@endcomponent
@endisset

@foreach ($outroLines as $line)
{{ $line }}

@endforeach

Thanks,
{{ config('app.name') }}

@isset($actionText)
@component('mail::subcopy')
If you're having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below into your web browser:
[{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent