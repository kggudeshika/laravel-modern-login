<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
