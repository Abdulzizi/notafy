@extends('auth.layout')

@section('title', 'Verify Email')

@section('panel-quote')
    Your inbox is waiting. <em>Go take a look.</em>
@endsection

@section('content')
    <div class="card-heading">
        <h1>Verify your email</h1>
        <p>We sent a verification link to <strong style="color:var(--accent)">{{ auth()->user()->email }}</strong>. Click the
            link to activate your account.</p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success">A new verification link has been sent to your email.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend verification email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top:0.65rem;">
        @csrf
        <button type="submit" class="btn btn-ghost">Sign out</button>
    </form>
@endsection
