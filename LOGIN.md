# Modern Login Page (Laravel 13)

A modern split-screen login page built on a fresh Laravel 13 skeleton.

## What's included

| File | Purpose |
| --- | --- |
| `app/Http/Controllers/Auth/LoginController.php` | Show login, authenticate (with rate limiting), logout |
| `routes/web.php` | `/login`, `/dashboard`, `/logout` routes |
| `resources/views/auth/login.blade.php` | Login page markup |
| `resources/views/dashboard.blade.php` | Simple page shown after sign-in |
| `public/css/auth.css` | Styles — plain CSS, no Vite build needed |
| `tests/Feature/LoginTest.php` | Feature tests for the login flow |

**Design features:** animated gradient showcase panel with glass card, icon inputs, show/hide password,
remember me, inline validation errors, loading state on submit, automatic dark mode,
responsive single-column layout on mobile, reduced-motion support.

## Run it

Requires PHP 8.3+ and Composer.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open http://127.0.0.1:8000/login and sign in with the seeded user
`test@example.com` / `password`.

Run the tests with `php artisan test`.
