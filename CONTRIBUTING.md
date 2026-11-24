# Contributing

Thanks for helping make this a better learning resource! 🎉

## Ways to help

- 🐛 Report a bug with the **Bug report** issue template
- 💡 Suggest a feature or a new example with the **Feature request** template
- 📖 Improve comments or the README. Clear explanations matter most here.
- 🧪 Add tests for a flow that isn't covered yet

## Getting set up

```bash
git clone https://github.com/kggudeshika/laravel-modern-login.git
cd laravel-modern-login
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Making a change

1. Fork the repo and create a branch: `git checkout -b feature/short-description`
2. Keep the code **beginner friendly**:
   - Prefer plain, readable code over clever tricks
   - Add a short comment when something is not obvious
   - Keep styles in `public/css/style.css` (no build step)
3. Add or update tests in `tests/Feature`
4. Make sure everything passes:
   ```bash
   php artisan test
   vendor/bin/pint     # code style
   ```
5. Open a pull request and fill in the template

## Commit messages

Short and in the present tense, for example `Add tags to posts` or `Fix avatar preview on Safari`.

## Code of conduct

By taking part you agree to follow the [Code of Conduct](CODE_OF_CONDUCT.md).
