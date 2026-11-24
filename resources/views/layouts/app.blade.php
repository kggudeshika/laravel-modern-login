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
<body class="app">
    {{-- Layout for pages people see AFTER signing in. --}}
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('dashboard') }}" class="brand brand-dark">
                <span class="brand-mark"><x-icon name="logo" /></span>
                <span class="brand-name">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <nav class="nav" aria-label="Main">
                <a href="{{ route('dashboard') }}" @class(['nav-link', 'is-active' => request()->routeIs('dashboard')])>
                    <x-icon name="grid" /> <span>Dashboard</span>
                </a>
                <a href="{{ route('posts.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('posts.*')])>
                    <x-icon name="file" /> <span>Posts</span>
                </a>
                <a href="{{ route('profile.edit') }}" @class(['nav-link', 'is-active' => request()->routeIs('profile.*')])>
                    <x-icon name="user" /> <span>Profile</span>
                </a>
            </nav>

            <div class="user-menu">
                <span class="avatar avatar-sm">{{ str(auth()->user()->name)->explode(' ')->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost" title="Sign out">
                        <x-icon name="logout" /> <span class="hide-sm">Sign out</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="container">
        @if (session('success'))
            <div class="alert alert-success flash" role="status">
                <x-icon name="check" /> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/ui.js') }}" defer></script>
</body>
</html>
