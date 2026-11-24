<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Password rules used by Password::defaults() everywhere
        // (sign up, reset password, change password, admin user form).
        // The checklist on the sign-up page shows these same rules.
        Password::defaults(fn () => Password::min(8)->letters()->numbers());
    }
}
