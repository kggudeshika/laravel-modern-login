<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard · {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth">
    <main class="dashboard">
        <div class="auth-card">
            <h1>Hello, {{ auth()->user()->name }} 👋</h1>
            <p class="muted">You are signed in as {{ auth()->user()->email }}.</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-primary"><span>Sign out</span></button>
            </form>
        </div>
    </main>
</body>
</html>
