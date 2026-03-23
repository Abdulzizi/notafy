@extends('auth.layout')
@section('title', 'Sign In')
@section('description', 'Sign in to your Notafy account to extract and manage your receipts.')
{{-- @section('panel-quote', 'Welcome back. <em>Pick up where you left off.</em>') --}}

@section('panel-quote')
    Good to see you again. <em>Let us get you in.</em>
@endsection

@section('content')
    <div class="card-heading">
        <h1>Sign in</h1>
        <p>Enter your email and password below.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
        </div>

        <div class="check-row">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Remember me for 30 days</label>
        </div>

        <button type="submit" class="btn btn-primary">Continue</button>
    </form>

    <div class="alt-link" style="margin-top:1rem;">
        <a href="{{ route('password.request') }}">Forgot your password?</a>
    </div>

    <div class="divider">or</div>

    <x-google-btn />

    <div class="alt-link">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </div>
@endsection
