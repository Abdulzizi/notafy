@extends('auth.layout')

@section('title', 'Reset Password')

@section('panel-quote')
    Almost done. <em>Choose something strong.</em>
@endsection

@section('content')
    <div class="card-heading">
        <h1>Set new password</h1>
        <p>Your new password must be at least 8 characters.</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}"
                autocomplete="email" autofocus class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" autocomplete="new-password"
                class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="field" style="margin-bottom:1.5rem;">
            <label for="password_confirmation">Confirm new password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary">Reset password</button>
    </form>

    <div class="alt-link">
        <a href="{{ route('login') }}">Back to sign in</a>
    </div>
@endsection
