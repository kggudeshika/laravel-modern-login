# Laravel Modern Login + CRUD Starter

A small, fully commented **Laravel 13** project for learning how to build:

- 🔐 **Login** with "remember me" and brute-force protection (rate limiting)
- 📝 **Sign up** (register) with password confirmation and terms checkbox
- 🔑 **Forgot password** → email link → **reset password**
- 🗂️ **Full CRUD** (Create, Read, Update, Delete) for blog posts, with search, filter and pagination
- 👤 **Profile settings**: update details, change password, delete account
- 🎨 A modern design in **plain CSS** (no build step), with automatic dark mode and mobile layout

Everything is written by hand without a starter kit, so you can open each file and see exactly how it works.

---

## Quick start

You need **PHP 8.3+** and **Composer**.

```bash
git clone https://github.com/kggudeshika/laravel-modern-login.git
cd laravel-modern-login

composer install
cp .env.example .env          # Windows PowerShell: copy .env.example .env
php artisan key:generate
php artisan migrate --seed    # creates the SQLite database + a demo user with 15 posts
php artisan serve
```

Open <http://127.0.0.1:8000/login> and sign in with:

| Email | Password |
| --- | --- |
| `test@example.com` | `password` |

Run the tests:

```bash
php artisan test
```

> **Where is the password reset email?** `.env` uses `MAIL_MAILER=log`, so emails are written to
> `storage/logs/laravel.log` instead of being sent. Open that file and copy the reset link.
> To send real emails, set the `MAIL_*` values in `.env` (for example with Mailtrap or Mailpit).

---

## Pages

| URL | Page | Who can see it |
| --- | --- | --- |
| `/login` | Sign in | Guests |
| `/register` | Create account | Guests |
| `/forgot-password` | Ask for a reset link | Guests |
| `/reset-password/{token}` | Choose a new password (from the email) | Guests |
| `/dashboard` | Stats + recent posts | Signed-in users |
| `/posts` | List, search, filter, paginate | Signed-in users |
| `/posts/create` | Create form | Signed-in users |
| `/posts/{id}` | Show one post | Owner only |
| `/posts/{id}/edit` | Edit form | Owner only |
| `/profile` | Update details, change password, delete account | Signed-in users |

---

## Project map — where to look

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── LoginController.php            ← sign in / sign out + rate limiting
│   │   │   ├── RegisterController.php         ← sign up
│   │   │   ├── ForgotPasswordController.php   ← sends the reset email
│   │   │   └── ResetPasswordController.php    ← saves the new password
│   │   ├── DashboardController.php            ← single-action controller (__invoke)
│   │   ├── PostController.php                 ← the CRUD example (7 actions)
│   │   └── ProfileController.php              ← profile, password, delete account
│   └── Requests/
│       └── PostRequest.php                    ← validation rules for the post form
├── Models/
│   ├── Post.php                               ← categories / statuses + belongsTo User
│   └── User.php                               ← hasMany Post
└── Policies/
    └── PostPolicy.php                         ← "only the owner can edit/delete"

database/
├── migrations/2026_11_24_000000_create_posts_table.php
├── factories/PostFactory.php                  ← fake posts for seeding and tests
└── seeders/DatabaseSeeder.php                 ← demo user + 15 posts

resources/views/
├── layouts/
│   ├── guest.blade.php                        ← split-screen layout for auth pages
│   └── app.blade.php                          ← top navigation for signed-in pages
├── components/
│   ├── field.blade.php                        ← <x-field>: label + input + error
│   └── icon.blade.php                         ← <x-icon name="mail" />
├── auth/  login · register · forgot-password · reset-password
├── posts/ index · create · edit · show · _form (shared by create & edit)
├── profile/edit.blade.php
├── partials/pagination.blade.php
└── dashboard.blade.php

routes/web.php                                 ← all routes, grouped by guest / auth
public/css/style.css                           ← all styles (plain CSS)
public/js/ui.js                                ← show password, delete confirm, spinners
tests/Feature/                                 ← Login, Register, PasswordReset, PostCrud, Profile
```

---

## How it works (step by step)

### 1. Login

1. `GET /login` → `LoginController@create` shows `auth/login.blade.php`.
2. The form posts to `POST /login` → `LoginController@store`.
3. The controller validates the email and password, then calls `Auth::attempt()`.
4. After **5 wrong attempts** the `RateLimiter` blocks that email + IP for a short time.
5. On success the session is regenerated (prevents session fixation) and the user goes to `/dashboard`.

### 2. Sign up

`RegisterController@store` validates the form (`unique:users,email`, `confirmed`, `accepted` for the terms box),
creates the user, fires the `Registered` event and signs the user in.
The password is hashed automatically by the `'password' => 'hashed'` cast in `User.php`.

### 3. Forgot / reset password

1. `ForgotPasswordController@store` calls `Password::sendResetLink()`. Laravel saves a token in the
   `password_reset_tokens` table and emails a link to the route named **`password.reset`**.
2. The link opens `ResetPasswordController@create`, which shows a form with the token in a hidden input.
3. `ResetPasswordController@store` calls `Password::reset()`, which checks the token and saves the new password.

### 4. CRUD with a resource controller

One line in `routes/web.php` creates all seven routes:

```php
Route::resource('posts', PostController::class);
```

| Method | URL | Controller method | Purpose |
| --- | --- | --- | --- |
| GET | `/posts` | `index` | List |
| GET | `/posts/create` | `create` | Empty form |
| POST | `/posts` | `store` | Save new |
| GET | `/posts/{post}` | `show` | View one |
| GET | `/posts/{post}/edit` | `edit` | Filled form |
| PUT | `/posts/{post}` | `update` | Save changes |
| DELETE | `/posts/{post}` | `destroy` | Delete |

Things to notice:

- **Form Request** – `PostRequest` keeps validation out of the controller.
- **Policy** – `PostPolicy` + `Gate::authorize()` stop users from editing other people's posts (they get a 403).
- **Shared form** – `posts/_form.blade.php` is used by both create and edit.
- **Method spoofing** – HTML forms only support GET/POST, so `@method('PUT')` and `@method('DELETE')` tell Laravel the real method.
- **Every form control** – text, select, radio buttons, date, checkbox and textarea are all in the post form.
- **Checkbox trick** – a hidden `is_featured=0` input is sent when the box is unticked.
- **Search & filter** – a GET form keeps `?search=` and `?status=` in the URL; `withQueryString()` keeps them while paging.

### 5. Profile

`ProfileController` has three separate forms on one page. Each uses its own **error bag**
(`validateWithBag('profile', ...)`) so validation errors appear only under the form that was submitted.
Changing the password and deleting the account both ask for the current password (`current_password` rule).

### 6. Reusable Blade component

Instead of repeating label + input + error markup, every form uses `<x-field>`:

```blade
<x-field name="email" label="Email address" type="email" icon="mail" required />
<x-field name="category" label="Category" type="select" :options="$categories" />
<x-field name="body" label="Content" type="textarea" rows="8" />
```

It refills the old value after a failed submit and shows the validation message automatically.

---

## Ideas to practise

- Add an image upload to posts (`$request->file('image')->store('posts', 'public')`).
- Add email verification (`MustVerifyEmail` on `User` + the `verified` middleware).
- Add a `tags` table with a many-to-many relationship.
- Let users sort the posts table by title or date.
- Write a test for the rate limiter on the login page.

---

## License

[MIT](LICENSE) — free to use for learning and in your own projects.
