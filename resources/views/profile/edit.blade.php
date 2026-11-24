@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="profile-hero card">
        <x-avatar :user="$user" size="xl" />
        <div>
            <h1>{{ $user->name }}</h1>
            <p class="muted">{{ $user->email }}</p>
            <div class="meta">
                @if ($user->hasVerifiedEmail())
                    <span class="badge badge-success"><x-icon name="check" /> Email verified</span>
                @else
                    <span class="badge badge-warning"><x-icon name="alert" /> Email not verified</span>
                @endif
                @if ($user->isAdmin())
                    <span class="badge badge-accent"><x-icon name="shield" /> Admin</span>
                @endif
                <span class="muted">Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <div class="settings">
        {{-- 1. Profile picture: upload / replace (POST /profile/avatar) and remove (DELETE /profile/avatar) --}}
        <section class="card settings-card">
            <div class="settings-intro">
                <h2>Profile picture</h2>
                <p class="muted">JPG, PNG or WebP. At least 100×100 pixels, up to 2 MB.</p>
            </div>
            <div>
                <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="avatar-form" novalidate>
                    @csrf
                    <x-avatar :user="$user" size="lg" id="avatar-preview" />

                    <div class="avatar-actions">
                        <label class="btn-secondary file-button">
                            <x-icon name="camera" />
                            <span>{{ $user->avatar_path ? 'Choose new picture' : 'Choose picture' }}</span>
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                                   data-preview="#avatar-preview" data-filename="#avatar-filename" class="visually-hidden">
                        </label>
                        <span id="avatar-filename" class="muted file-name">No file chosen</span>
                    </div>
                    @error('avatar', 'avatar')
                        <p class="field-error">{{ $message }}</p>
                    @enderror

                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><span>Upload picture</span></button>
                    </div>
                </form>

                @if ($user->avatar_path)
                    <form method="POST" action="{{ route('profile.avatar.destroy') }}" data-confirm="Remove your profile picture?" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link link-danger"><x-icon name="trash" /> Remove current picture</button>
                    </form>
                @endif
            </div>
        </section>

        {{-- 2. Update details (PATCH /profile) --}}
        <section class="card settings-card">
            <div class="settings-intro">
                <h2>Profile information</h2>
                <p class="muted">Update your name and email. Changing your email asks you to verify it again.</p>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" novalidate>
                @csrf
                @method('PATCH')

                <x-field name="name" label="Name" icon="user" bag="profile"
                         :value="$user->name" autocomplete="name" required />
                <x-field name="email" label="Email address" type="email" icon="mail" bag="profile"
                         :value="$user->email" autocomplete="username" required />

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><span>Save</span></button>
                </div>
            </form>
        </section>

        {{-- 3. Change password (PUT /profile/password) --}}
        <section class="card settings-card">
            <div class="settings-intro">
                <h2>Change password</h2>
                <p class="muted">Enter your current password, then choose a new one with at least 8 characters, a letter and a number.</p>
            </div>
            <form method="POST" action="{{ route('profile.password') }}" novalidate>
                @csrf
                @method('PUT')

                <x-field name="current_password" label="Current password" type="password" icon="lock" bag="password"
                         autocomplete="current-password" required />
                <x-field name="password" label="New password" type="password" icon="lock" bag="password"
                         autocomplete="new-password" required data-strength="#profile-strength" />

                <div id="profile-strength" class="strength" aria-live="polite">
                    <div class="strength-bar"><span></span><span></span><span></span><span></span></div>
                    <p class="strength-label">Password strength: <strong>—</strong></p>
                </div>

                <x-field name="password_confirmation" label="Confirm new password" type="password" icon="lock" bag="password"
                         autocomplete="new-password" required data-match="password" />
                <p class="match-hint" id="match-hint" hidden></p>

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><span>Update password</span></button>
                </div>
            </form>
        </section>

        {{-- 4. Delete account (DELETE /profile) --}}
        <section class="card settings-card card-danger">
            <div class="settings-intro">
                <h2>Delete account</h2>
                <p class="muted">This permanently deletes your account, your picture and all of your posts.</p>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}" data-confirm="Delete your account and all posts? This cannot be undone." novalidate>
                @csrf
                @method('DELETE')

                <x-field name="delete_password" label="Confirm with your password" type="password" icon="lock" bag="deletion"
                         autocomplete="current-password" required />

                <div class="form-actions">
                    <button type="submit" class="btn-danger"><x-icon name="trash" /> Delete account</button>
                </div>
            </form>
        </section>
    </div>
@endsection
