<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth">
    <main class="auth-shell">
        {{-- Brand / showcase panel --}}
        <section class="auth-showcase" aria-hidden="true">
            <div class="orb orb-a"></div>
            <div class="orb orb-b"></div>
            <div class="orb orb-c"></div>

            <div class="brand">
                <span class="brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                </span>
                <span class="brand-name">{{ config('app.name', 'Laravel') }}</span>
            </div>

            <div class="showcase-copy">
                <h2>Build faster.<br>Ship with confidence.</h2>
                <p>Everything your team needs, in one secure place. Sign in to pick up right where you left off.</p>
            </div>

            <figure class="glass-card">
                <blockquote>“The cleanest dashboard we’ve used. Onboarding took minutes, not days.”</blockquote>
                <figcaption>
                    <span class="avatar">AK</span>
                    <span><strong>Alex Kim</strong><small>Product Lead</small></span>
                </figcaption>
            </figure>
        </section>

        {{-- Form panel --}}
        <section class="auth-panel">
            <div class="auth-card">
                <header>
                    <div class="brand brand-compact">
                        <span class="brand-mark">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5Z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
                        </span>
                        <span class="brand-name">{{ config('app.name', 'Laravel') }}</span>
                    </div>
                    <h1>Welcome back</h1>
                    <p class="muted">Sign in to your account to continue.</p>
                </header>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" novalidate>
                    @csrf

                    <div class="field">
                        <label for="email">Email address</label>
                        <div class="input-wrap @error('email') has-error @enderror">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   placeholder="you@company.com" autocomplete="username" required autofocus
                                   @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                        </div>
                        @error('email')
                            <p class="field-error" id="email-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <div class="label-row">
                            <label for="password">Password</label>
                            @if (Route::has('password.request'))
                                <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
                            @endif
                        </div>
                        <div class="input-wrap @error('password') has-error @enderror">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            <input id="password" type="password" name="password" placeholder="••••••••"
                                   autocomplete="current-password" required
                                   @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            <button type="button" class="toggle-password" aria-label="Show password" aria-pressed="false">
                                <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.53 13.53 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><path d="m2 2 20 20"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error" id="password-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="checkbox">
                        <input type="checkbox" name="remember" @checked(old('remember'))>
                        <span>Keep me signed in</span>
                    </label>

                    <button type="submit" class="btn-primary">
                        <span>Sign in</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="footnote">Don’t have an account? <a class="link" href="{{ route('register') }}">Create one</a></p>
                @endif
            </div>

            <p class="legal">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </section>
    </main>

    <script>
        document.querySelectorAll('.toggle-password').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var input = btn.parentElement.querySelector('input');
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.setAttribute('aria-pressed', String(show));
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });
        document.querySelector('.auth-card form').addEventListener('submit', function (e) {
            e.currentTarget.querySelector('.btn-primary').classList.add('is-loading');
        });
    </script>
</body>
</html>
