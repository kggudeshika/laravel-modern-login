<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
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
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link nav-link-admin">
                        <x-icon name="shield" /> <span>Admin</span>
                    </a>
                @endif
            </nav>

            <div class="user-menu">
                <a href="{{ route('profile.edit') }}" title="Your profile"><x-avatar :user="auth()->user()" size="sm" /></a>
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
        @include('partials.flash')

        @yield('content')
    </main>

    <script src="{{ asset('js/ui.js') }}" defer></script>
</body>
</html>
