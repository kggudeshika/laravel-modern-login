<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Profile settings: picture, details, password and account deletion.
 * Each form uses its own "error bag" so errors show next to the right form.
 */
class ProfileController extends Controller
{
    /**
     * GET /profile
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    /**
     * POST /profile/avatar — upload a new profile picture (replaces the old one).
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validateWithBag('avatar', [
            'avatar' => [
                'required',
                File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(2 * 1024), // 2 MB
                'dimensions:min_width=100,min_height=100',
            ],
        ]);

        $user = $request->user();
        $oldPath = $user->avatar_path;

        // Saves to storage/app/public/avatars/<random-name>.jpg
        $path = $request->file('avatar')->store('avatars', 'public');

        $user->forceFill(['avatar_path' => $path])->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile picture updated.');
    }

    /**
     * DELETE /profile/avatar — remove the profile picture.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $request->user()->deleteAvatar();

        return redirect()->route('profile.edit')->with('success', 'Profile picture removed.');
    }

    /**
     * PATCH /profile — update name and email.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->fill($validated);

        $emailChanged = $user->isDirty('email');

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged) {
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice');
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }

    /**
     * PUT /profile/password — change password (needs the current one).
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('password', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::defaults()],
        ]);

        $request->user()->update(['password' => $validated['password']]);

        return redirect()->route('profile.edit')->with('success', 'Password changed.');
    }

    /**
     * DELETE /profile — delete the account, its posts and its picture.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('deletion', [
            'delete_password' => ['required', 'current_password'],
        ], [], ['delete_password' => 'password']);

        $user = $request->user();

        Auth::logout();
        $user->deleteAvatar();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Your account has been deleted.');
    }
}
