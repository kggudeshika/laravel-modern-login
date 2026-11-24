<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Email verification: after signing up, users must click the link in
 * their email before they can open pages protected by the "verified" middleware.
 */
class EmailVerificationController extends Controller
{
    /**
     * GET /verify-email — "check your inbox" page.
     */
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * GET /verify-email/{id}/{hash} — the link inside the email.
     * EmailVerificationRequest checks the signed URL belongs to this user.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->route('dashboard')->with('success', 'Thanks! Your email address is verified.');
    }

    /**
     * POST /email/verification-notification — send the email again.
     */
    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification link has been sent to your email address.');
    }
}
