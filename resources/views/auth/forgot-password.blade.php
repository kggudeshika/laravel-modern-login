@extends('layouts.guest')

@section('title', 'Forgot password')

@section('content')
    <header class="auth-header">
        <h1>Forgot your password?</h1>
        <p class="muted">Enter the email you signed up with and we’ll send you a link to choose a new one.</p>
    </header>

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <x-field name="email" label="Email address" type="email" icon="mail"
                 placeholder="you@company.com" autocomplete="username" required autofocus />

        <button type="submit" class="btn-primary btn-block">
            <span>Email me a reset link</span>
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="footnote">
        <a class="link link-inline" href="{{ route('login') }}"><x-icon name="arrow-left" /> Back to sign in</a>
    </p>
@endsection
