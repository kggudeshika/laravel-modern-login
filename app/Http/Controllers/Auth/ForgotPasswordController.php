<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the "forgot password" page.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Email the user a password reset link.
     *
     * With MAIL_MAILER=log (the default in .env.example) the email is written
     * to storage/logs/laravel.log instead of being sent, so you can copy the
     * link from there while developing.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
