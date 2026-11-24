# Security Policy

## Supported versions

Only the latest version on the `main` branch receives fixes.

## Reporting a vulnerability

Please **do not** open a public issue for security problems.

Instead, use GitHub's private reporting:
**Security** tab → **Report a vulnerability** on
<https://github.com/kggudeshika/laravel-modern-login/security>.

Please include:
- What the problem is and where it is (file / URL)
- Steps to reproduce it
- What could happen if someone used it

You'll get a reply as soon as possible, and credit in the changelog if you'd like.

## Using this project in production

This is a learning starter. Before putting it on a real server:

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Use HTTPS and set `SESSION_SECURE_COOKIE=true`
- Configure a real mail service for verification and reset emails
- Change or remove the demo accounts from `DatabaseSeeder`
- Keep Laravel and dependencies up to date: `composer update`
