<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="auth">
    {{--
        Layout for pages people see BEFORE signing in:
        login, register, forgot password and reset password.
        Each page fills the "content" section with its form.
    --}}
    <main class="auth-shell">
        <section class="auth-showcase" aria-hidden="true">
            <div class="orb orb-a"></div>
            <div class="orb orb-b"></div>
            <div class="orb orb-c"></div>

            <a href="{{ url('/') }}" class="brand">
                <span class="brand-mark"><x-icon name="logo" /></span>
                <span class="brand-name">{{ config('app.name', 'Laravel') }}</span>
            </a>

            {{-- Pages can replace this text with @section('showcase') --}}
            <div class="showcase-copy">
                @hasSection('showcase')
                    @yield('showcase')
                @else
                    <h2>Build faster.<br>Ship with confidence.</h2>
                    <p>Everything your team needs, in one secure place. Sign in to pick up right where you left off.</p>
                @endif
            </div>

            <ul class="glass-card feature-list">
                <li><span class="feature-icon"><x-icon name="lock" /></span><span><strong>Secure by default</strong><small>Hashed passwords, CSRF protection and login rate limiting.</small></span></li>
                <li><span class="feature-icon"><x-icon name="mail" /></span><span><strong>Email verification</strong><small>New accounts confirm their email before getting in.</small></span></li>
                <li><span class="feature-icon"><x-icon name="shield" /></span><span><strong>Admin panel</strong><small>Manage users and content from one place.</small></span></li>
            </ul>
        </section>

        <section class="auth-panel">
            <div class="auth-card">
                <a href="{{ url('/') }}" class="brand brand-compact">
                    <span class="brand-mark"><x-icon name="logo" /></span>
                    <span class="brand-name">{{ config('app.name', 'Laravel') }}</span>
                </a>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif

                @yield('content')
            </div>

            <p class="legal">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </section>
    </main>

    <script src="{{ asset('js/ui.js') }}" defer></script>
</body>
</html>
