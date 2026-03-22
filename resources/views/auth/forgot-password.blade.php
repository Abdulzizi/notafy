@extends('auth.layout')

@section('title', 'Forgot Password')
@section('description', 'Reset your Notafy account password. Enter your email and we\'ll send you a reset link.')

@section('panel-quote')
    Locked out? <em>Not for long.</em>
@endsection

@section('content')
    <div class="card-heading">
        <h1>Forgot password?</h1>
        <p>Enter your email and we'll send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="field" style="margin-bottom:1.5rem;">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Send reset link</button>
    </form>

    <div class="alt-link">
        Remembered it? <a href="{{ route('login') }}">Back to sign in</a>
    </div>
@endsection
