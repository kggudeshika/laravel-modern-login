@extends('layouts.guest')

@section('title', 'Create account')

@section('content')
    <header class="auth-header">
        <h1>Create your account</h1>
        <p class="muted">It only takes a minute. No credit card needed.</p>
    </header>

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <x-field name="name" label="Full name" icon="user"
                 placeholder="Jane Doe" autocomplete="name" required autofocus />

        <x-field name="email" label="Email address" type="email" icon="mail"
                 placeholder="you@company.com" autocomplete="username" required />

        <x-field name="password" label="Password" type="password" icon="lock"
                 placeholder="At least 8 characters" autocomplete="new-password" required
                 hint="Use 8 or more characters." />

        <x-field name="password_confirmation" label="Confirm password" type="password" icon="lock"
                 placeholder="Repeat your password" autocomplete="new-password" required />

        <label class="checkbox">
            <input type="checkbox" name="terms" value="1" @checked(old('terms'))>
            <span>I agree to the <a class="link" href="#">Terms</a> and <a class="link" href="#">Privacy Policy</a></span>
        </label>
        @error('terms')
            <p class="field-error field-error-tight">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-primary btn-block">
            <span>Create account</span>
            <x-icon name="arrow-right" />
        </button>
    </form>

    <p class="footnote">Already have an account? <a class="link" href="{{ route('login') }}">Sign in</a></p>
@endsection
