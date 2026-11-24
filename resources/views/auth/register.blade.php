@extends('layouts.guest')

@section('title', 'Create account')

@section('showcase')
    <h2>Start learning<br>in under a minute.</h2>
    <p>Create a free account to write posts, manage your profile and explore every part of this Laravel starter.</p>
@endsection

@section('content')
    <nav class="auth-tabs" aria-label="Account">
        <a href="{{ route('login') }}">Sign in</a>
        <a href="{{ route('register') }}" class="is-active" aria-current="page">Create account</a>
    </nav>

    <header class="auth-header">
        <h1>Create your account</h1>
        <p class="muted">It’s free. We’ll send a link to confirm your email.</p>
    </header>

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <x-field name="name" label="Full name" icon="user"
                 placeholder="Jane Doe" autocomplete="name" required autofocus />

        <x-field name="email" label="Email address" type="email" icon="mail"
                 placeholder="you@company.com" autocomplete="username" inputmode="email" required />

        <x-field name="password" label="Password" type="password" icon="lock"
                 placeholder="Create a password" autocomplete="new-password" required
                 data-strength="#password-strength" />

        {{-- Live strength meter + checklist (filled in by public/js/ui.js). Matches Password::defaults(). --}}
        <div id="password-strength" class="strength" aria-live="polite">
            <div class="strength-bar"><span></span><span></span><span></span><span></span></div>
            <p class="strength-label">Password strength: <strong>—</strong></p>
            <ul class="checklist">
                <li data-rule="length"><x-icon name="check" /> At least 8 characters</li>
                <li data-rule="letter"><x-icon name="check" /> Contains a letter</li>
                <li data-rule="number"><x-icon name="check" /> Contains a number</li>
                <li data-rule="mixed" class="optional"><x-icon name="check" /> Upper and lower case <small>(stronger)</small></li>
                <li data-rule="symbol" class="optional"><x-icon name="check" /> A symbol like ! ? # <small>(stronger)</small></li>
            </ul>
        </div>

        <x-field name="password_confirmation" label="Confirm password" type="password" icon="lock"
                 placeholder="Repeat your password" autocomplete="new-password" required
                 data-match="password" />
        <p class="match-hint" id="match-hint" hidden></p>

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
