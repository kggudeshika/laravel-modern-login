<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="admin">
    {{-- Layout for the admin panel: sidebar on the left, content on the right. --}}
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand sidebar-brand">
            <span class="brand-mark"><x-icon name="logo" /></span>
            <span class="brand-name">{{ config('app.name', 'Laravel') }} <small>Admin</small></span>
        </a>

        <nav class="sidebar-nav" aria-label="Admin">
            <p class="sidebar-label">Overview</p>
            <a href="{{ route('admin.dashboard') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.dashboard')])>
                <x-icon name="grid" /> Dashboard
            </a>

            <p class="sidebar-label">Manage</p>
            <a href="{{ route('admin.users.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.users.*')])>
                <x-icon name="users" /> Users
            </a>
            <a href="{{ route('admin.posts.index') }}" @class(['sidebar-link', 'is-active' => request()->routeIs('admin.posts.*')])>
                <x-icon name="file" /> Posts
            </a>

            <p class="sidebar-label">Site</p>
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <x-icon name="home" /> Back to app
            </a>
        </nav>

        <div class="sidebar-user">
            <x-avatar :user="auth()->user()" size="sm" />
            <span class="sidebar-user-name">{{ auth()->user()->name }}<small>{{ auth()->user()->email }}</small></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-icon" title="Sign out" aria-label="Sign out"><x-icon name="logout" /></button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button type="button" class="btn-icon sidebar-toggle" data-sidebar-toggle aria-label="Toggle menu"><x-icon name="grid" /></button>
            <span class="admin-crumb">@yield('title')</span>
        </header>

        <main class="admin-content">
            @include('partials.flash')

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/ui.js') }}" defer></script>
</body>
</html>
