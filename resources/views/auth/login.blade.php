@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    {{-- Switch between sign in / sign up --}}
    <nav class="auth-tabs" aria-label="Account">
        <a href="{{ route('login') }}" class="is-active" aria-current="page">Sign in</a>
        <a href="{{ route('register') }}">Create account</a>
    </nav>

    <header class="auth-header">
        <h1>Welcome back</h1>
        <p class="muted">Sign in to your account to continue.</p>
    </header>

    {{-- Handy for learners: one click fills the demo accounts (local environment only). --}}
    @if (app()->environment('local'))
        <div class="demo-box">
            <span class="demo-title"><x-icon name="key" /> Demo accounts</span>
            <div class="demo-buttons">
                <button type="button" class="demo-fill" data-email="test@example.com" data-password="password">User</button>
                <button type="button" class="demo-fill" data-email="admin@example.com" data-password="password">Admin</button>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <x-field name="email" label="Email address" type="email" icon="mail"
                 placeholder="you@company.com" autocomplete="username" inputmode="email" required autofocus />

        <x-field name="password" label="Password" type="password" icon="lock"
                 placeholder="Enter your password" autocomplete="current-password" required>
            <x-slot:labelAction>
                <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
            </x-slot:labelAction>
        </x-field>

        <div class="form-row">
            <label class="checkbox">
                <input type="checkbox" name="remember" @checked(old('remember'))>
                <span>Keep me signed in</span>
            </label>
        </div>

        <button type="submit" class="btn-primary btn-block">
            <span>Sign in</span>
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="secure-note"><x-icon name="shield" /> Protected by rate limiting: 5 attempts per minute.</p>

    <p class="footnote">New here? <a class="link" href="{{ route('register') }}">Create a free account</a></p>
@endsection
