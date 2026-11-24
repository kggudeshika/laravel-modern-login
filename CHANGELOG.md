# Changelog

All notable changes to this project are listed here.
The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [1.2.0] - 2026-11-24

### Added
- Admin panel at `/admin` with a sidebar layout and dashboard stats
- Admin CRUD for users: role, email verified, password and profile picture
- Admin CRUD for every user's posts, including changing the author
- `admin` middleware and `is_admin` column
- Email verification for new accounts and after an email change
- Profile picture upload, preview, replace and remove
- Sign in: demo account buttons, Caps Lock warning, sign in / sign up tabs
- Sign up: live password strength meter, rule checklist and match hint
- Shared `<head>` with meta tags, Open Graph tags and an SVG favicon
- Repository docs: contributing guide, code of conduct, security policy, issue and PR templates
- GitHub Actions workflow that runs the tests on every push

### Changed
- Password rules are now: at least 8 characters with a letter and a number
- New passwords must differ from the current one

## [1.1.0] - 2026-11-24

### Added
- Sign up, forgot password and reset password pages
- Posts CRUD with search, filter, pagination and an ownership policy
- Profile page: update details, change password, delete account
- `<x-field>` and `<x-icon>` Blade components
- Feature tests and a step-by-step README

## [1.0.0] - 2026-11-24

### Added
- Laravel 13 project with a modern split-screen login page
