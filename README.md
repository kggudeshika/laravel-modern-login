<div align="center">

<img src="public/favicon.svg" width="72" alt="Logo">

# Laravel Modern Login + Admin Panel

**A friendly, fully commented Laravel 13 starter for learning authentication, CRUD and admin panels.**

[![Tests](https://github.com/kggudeshika/laravel-modern-login/actions/workflows/tests.yml/badge.svg)](https://github.com/kggudeshika/laravel-modern-login/actions/workflows/tests.yml)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green)

</div>

---

Everything is written by hand without a starter kit (no Breeze, no Jetstream, no build step),
so you can open any file and see exactly how it works.

## ✨ Features

### Sign in & sign up
- 🔐 **Sign in** with "keep me signed in", show/hide password and a **Caps Lock warning**
- 🛡️ **Brute-force protection**: 5 wrong attempts per minute, then a short lock
- 📝 **Sign up** with a live **password strength meter**, requirement checklist and "passwords match" hint
- ✉️ **Email verification**: new users confirm their email before using the app
- 🔑 **Forgot password → email link → reset password**
- 🧪 One-click **demo accounts** on the login page (local only)

### Profile
- 🖼️ **Profile picture CRUD**: upload, preview, replace and remove (JPG/PNG/WebP, max 2 MB)
- 👤 Update name and email (changing email asks for verification again)
- 🔒 **Change password** (requires the current password, must differ from it)
- 🗑️ Delete account (removes posts and picture too)

### Posts (user CRUD)
- Create, read, update, delete your own posts
- Search, status filter and pagination
- Every common form control: text, select, radio, date, checkbox, textarea
- A policy makes sure users can only touch **their own** posts

### Admin panel (`/admin`)
- 📊 Dashboard with site-wide stats and latest activity
- 👥 **Users CRUD**: search and filter, create, view, edit, delete; set **role**, **email verified**, **password** and **profile picture**
- 📰 **Posts CRUD** for every user's posts, including changing the author
- Safety rules: admins can't delete themselves or remove their own admin role
- Collapsible sidebar on mobile

### Design
- 🎨 Modern UI in **plain CSS**: no Tailwind or Vite build needed
- 🌙 Automatic **dark mode**, 📱 responsive layouts, ♿ accessible labels and focus states

---

## 🚀 Quick start

You need **PHP 8.3+** and **Composer**.

```bash
git clone https://github.com/kggudeshika/laravel-modern-login.git
cd laravel-modern-login

composer install
cp .env.example .env          # Windows PowerShell: copy .env.example .env
php artisan key:generate
php artisan migrate --seed    # SQLite database + demo users and posts
php artisan storage:link      # makes uploaded profile pictures visible
php artisan serve
```

Open <http://127.0.0.1:8000/login>.

### Demo accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| User | `test@example.com` | `password` |

> 💡 **Where do the emails go?** `.env` uses `MAIL_MAILER=log`, so verification and password
> reset emails are written to `storage/logs/laravel.log`. Open it and copy the link.
> To send real email, fill in the `MAIL_*` settings (for example Mailpit or Mailtrap).

### Run the tests

```bash
php artisan test
```

---

## 🗺️ Pages

| URL | Page | Access |
| --- | --- | --- |
| `/login` | Sign in | Guests |
| `/register` | Create account | Guests |
| `/forgot-password` | Request a reset link | Guests |
| `/reset-password/{token}` | Choose a new password | Guests (from email) |
| `/verify-email` | "Check your inbox" | Signed in |
| `/profile` | Picture, details, password, delete account | Signed in |
| `/dashboard` | Your stats and recent posts | Signed in + verified |
| `/posts` … | Your posts (CRUD) | Signed in + verified |
| `/admin` | Admin dashboard | Admins |
| `/admin/users` … | Manage users (CRUD) | Admins |
| `/admin/posts` … | Manage all posts (CRUD) | Admins |

---

## 📁 Project map

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php             sign in / out + rate limiting
│   │   │   ├── RegisterController.php          sign up
│   │   │   ├── EmailVerificationController.php verify email, resend link
│   │   │   ├── ForgotPasswordController.php    send reset link
│   │   │   └── ResetPasswordController.php     save new password
│   │   ├── Admin/
│   │   │   ├── DashboardController.php         admin stats
│   │   │   ├── UserController.php              users CRUD (+ role, password, picture)
│   │   │   └── PostController.php              all posts CRUD
│   │   ├── DashboardController.php
│   │   ├── PostController.php                  user's own posts CRUD
│   │   └── ProfileController.php               picture, details, password, delete
│   ├── Middleware/EnsureUserIsAdmin.php        the "admin" middleware
│   └── Requests/
│       ├── PostRequest.php                     post validation
│       └── Admin/AdminPostRequest.php          + author field
├── Models/  User.php · Post.php
├── Policies/PostPolicy.php                     only owners can edit their posts
└── Providers/AppServiceProvider.php            password rules

database/
├── migrations/   posts table · is_admin + avatar_path on users
├── factories/    UserFactory (admin(), unverified()) · PostFactory
└── seeders/DatabaseSeeder.php                  demo data

resources/views/
├── layouts/      guest (auth pages) · app (signed in) · admin (sidebar)
├── components/   <x-field> · <x-icon> · <x-avatar>
├── partials/     head (meta tags) · flash messages · pagination
├── auth/         login · register · verify-email · forgot-password · reset-password
├── profile/      edit
├── posts/        index · create · edit · show · _form
└── admin/        dashboard · users/* · posts/*

routes/web.php                                  every route, grouped and commented
public/css/style.css                            all styles (plain CSS)
public/js/ui.js                                 password meter, previews, confirms
tests/Feature/                                  tests for every flow
```

---

## 📚 How it works

<details>
<summary><strong>1. Sign in</strong></summary>

1. `GET /login` → `LoginController@create` shows the form.
2. `POST /login` → `LoginController@store` validates and calls `Auth::attempt()`.
3. `RateLimiter` allows 5 failures per email + IP, then locks for a minute.
4. On success the session id is regenerated (prevents session fixation).
</details>

<details>
<summary><strong>2. Sign up and email verification</strong></summary>

1. `RegisterController@store` validates (`unique`, `confirmed`, `accepted`, `Password::defaults()`) and creates the user.
2. `User` implements `MustVerifyEmail`, so the `Registered` event emails a signed verification link.
3. Routes behind the `verified` middleware send unverified users to `/verify-email`.
4. Clicking the link hits `verification.verify`, which checks the signature and marks the email verified.
5. `is_admin` is **not** in `$fillable`, so adding `is_admin=1` to the form does nothing.
</details>

<details>
<summary><strong>3. Forgot / reset password</strong></summary>

1. `Password::sendResetLink()` stores a token and emails a link to the route named `password.reset`.
2. The reset form posts the token, email and new password to `Password::reset()`.
3. Password rules live in one place: `Password::defaults()` in `AppServiceProvider`.
</details>

<details>
<summary><strong>4. Profile picture upload (CRUD)</strong></summary>

- **Create / update**: `$request->file('avatar')->store('avatars', 'public')` saves the file, the path goes in `users.avatar_path`, and the old file is deleted.
- **Read**: `<x-avatar :user="$user" />` shows the picture, or initials when there is none.
- **Delete**: `$user->deleteAvatar()` removes the file and clears the column.
- Validation: `File::image()->types([...])->max(2048)` plus `dimensions:min_width=100`.
- `php artisan storage:link` makes `storage/app/public` reachable at `/storage`.
</details>

<details>
<summary><strong>5. CRUD with resource controllers</strong></summary>

`Route::resource('posts', PostController::class)` creates 7 routes:

| Method | URL | Action |
| --- | --- | --- |
| GET | `/posts` | index |
| GET | `/posts/create` | create |
| POST | `/posts` | store |
| GET | `/posts/{post}` | show |
| GET | `/posts/{post}/edit` | edit |
| PUT | `/posts/{post}` | update |
| DELETE | `/posts/{post}` | destroy |

Look for: Form Requests, Policies with `Gate::authorize()`, `@method('PUT')`, a shared `_form` partial,
the hidden-input checkbox trick and `withQueryString()` for paging with filters.
</details>

<details>
<summary><strong>6. Admin panel</strong></summary>

- `EnsureUserIsAdmin` middleware is registered as `admin` in `bootstrap/app.php`.
- All admin routes share one group: `middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')`.
- `Admin\UserController` uses `forceFill()` for `is_admin`, `email_verified_at` and `avatar_path`, because those are not mass-assignable.
- Leaving the password blank when editing keeps the old password.
</details>

---

## 🧩 Ideas to practise

- Add a "last login" column and show it in the admin users table
- Add image uploads to posts
- Add tags with a many-to-many relationship
- Add social login with Laravel Socialite
- Add two-factor authentication

## 🤝 Contributing

Contributions are welcome. Please read [CONTRIBUTING.md](CONTRIBUTING.md) and our [Code of Conduct](CODE_OF_CONDUCT.md).
Found a security issue? See [SECURITY.md](SECURITY.md).

## 📄 License

[MIT](LICENSE): free to use for learning and in your own projects. See [CHANGELOG.md](CHANGELOG.md) for what changed.
