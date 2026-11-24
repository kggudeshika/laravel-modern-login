{{-- Shared <head> tags: meta, favicon, fonts and styles. --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="@yield('description', 'Laravel starter with modern login, sign up, password reset, CRUD and an admin panel.')">
<meta name="theme-color" content="#f53003">
<meta name="color-scheme" content="light dark">
<meta name="robots" content="noindex">
<meta property="og:title" content="@yield('title') · {{ config('app.name', 'Laravel') }}">
<meta property="og:description" content="@yield('description', 'Laravel starter with modern login, sign up, password reset, CRUD and an admin panel.')">
<meta property="og:type" content="website">
<title>@yield('title') · {{ config('app.name', 'Laravel') }}</title>
<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
