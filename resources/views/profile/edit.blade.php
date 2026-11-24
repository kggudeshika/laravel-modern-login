@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="page-header">
        <div>
            <h1>Profile</h1>
            <p class="muted">Manage your account details and security.</p>
        </div>
    </div>

    <div class="settings">
        {{-- 1. Update details (PATCH /profile) --}}
        <section class="card settings-card">
            <div class="settings-intro">
                <h2>Profile information</h2>
                <p class="muted">Update your name and email address.</p>
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

        {{-- 2. Change password (PUT /profile/password) --}}
        <section class="card settings-card">
            <div class="settings-intro">
                <h2>Change password</h2>
                <p class="muted">Use a long, random password to stay secure.</p>
            </div>
            <form method="POST" action="{{ route('profile.password') }}" novalidate>
                @csrf
                @method('PUT')

                <x-field name="current_password" label="Current password" type="password" icon="lock" bag="password"
                         autocomplete="current-password" required />
                <x-field name="password" label="New password" type="password" icon="lock" bag="password"
                         id="new_password" autocomplete="new-password" required />
                <x-field name="password_confirmation" label="Confirm new password" type="password" icon="lock" bag="password"
                         autocomplete="new-password" required />

                <div class="form-actions">
                    <button type="submit" class="btn-primary"><span>Update password</span></button>
                </div>
            </form>
        </section>

        {{-- 3. Delete account (DELETE /profile) --}}
        <section class="card settings-card card-danger">
            <div class="settings-intro">
                <h2>Delete account</h2>
                <p class="muted">This permanently deletes your account and all of your posts.</p>
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
