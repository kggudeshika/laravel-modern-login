@extends('layouts.guest')

@section('title', 'Verify your email')

@section('content')
    <div class="verify-illustration" aria-hidden="true">
        <x-icon name="mail" />
    </div>

    <header class="auth-header">
        <h1>Check your inbox</h1>
        <p class="muted">
            We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
            Click it to activate your account.
        </p>
    </header>

    <ul class="steps">
        <li><span>1</span> Open the email from {{ config('app.name') }}</li>
        <li><span>2</span> Click <strong>Verify Email Address</strong></li>
        <li><span>3</span> You’ll land on your dashboard</li>
    </ul>

    <p class="tip">
        <strong>Running locally?</strong> With <code>MAIL_MAILER=log</code> the email is saved in
        <code>storage/logs/laravel.log</code>. Copy the link from there.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary btn-block">
            <span>Resend verification email</span>
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="footnote">
        @csrf
        Wrong email? <button type="submit" class="link link-button">Sign out</button> and register again.
    </form>
@endsection
