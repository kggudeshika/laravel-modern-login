@extends('layouts.guest')

@section('title', 'Sign in')

@section('content')
    <header class="auth-header">
        <h1>Welcome back</h1>
        <p class="muted">Sign in to your account to continue.</p>
    </header>

    <form method="POST" action="{{ route('login.store') }}" novalidate>
        @csrf

        <x-field name="email" label="Email address" type="email" icon="mail"
                 placeholder="you@company.com" autocomplete="username" required autofocus />

        <x-field name="password" label="Password" type="password" icon="lock"
                 placeholder="••••••••" autocomplete="current-password" required>
            <x-slot:labelAction>
                <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
            </x-slot:labelAction>
        </x-field>

        <label class="checkbox">
            <input type="checkbox" name="remember" @checked(old('remember'))>
            <span>Keep me signed in</span>
        </label>

        <button type="submit" class="btn-primary btn-block">
            <span>Sign in</span>
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="footnote">Don’t have an account? <a class="link" href="{{ route('register') }}">Create one</a></p>
@endsection
