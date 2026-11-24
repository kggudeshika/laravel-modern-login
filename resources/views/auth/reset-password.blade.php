@extends('layouts.guest')

@section('title', 'Reset password')

@section('content')
    <header class="auth-header">
        <h1>Choose a new password</h1>
        <p class="muted">Pick something strong that you don’t use anywhere else.</p>
    </header>

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        {{-- The token from the email link proves this request is allowed. --}}
        <input type="hidden" name="token" value="{{ $token }}">

        <x-field name="email" label="Email address" type="email" icon="mail"
                 :value="$email" autocomplete="username" required />

        <x-field name="password" label="New password" type="password" icon="lock"
                 placeholder="At least 8 characters" autocomplete="new-password" required autofocus />

        <x-field name="password_confirmation" label="Confirm new password" type="password" icon="lock"
                 placeholder="Repeat your password" autocomplete="new-password" required />

        <button type="submit" class="btn-primary btn-block">
            <span>Reset password</span>
            <x-icon name="check" />
        </button>
    </form>
@endsection
