{{-- Shared by admin/users/create and admin/users/edit. --}}
@php($editing = $user->exists)

<div class="form-section">
    <h3>Account</h3>
    <div class="form-grid">
        <x-field name="name" label="Full name" icon="user" :value="$user->name" required autofocus />
        <x-field name="email" label="Email address" type="email" icon="mail" :value="$user->email" required />
    </div>
</div>

<div class="form-section">
    <h3>Password</h3>
    @if ($editing)
        <p class="muted form-section-hint">Leave blank to keep the current password. Fill both fields to set a new one.</p>
    @endif
    <div class="form-grid">
        <x-field name="password" :label="$editing ? 'New password' : 'Password'" type="password" icon="lock"
                 autocomplete="new-password" :required="! $editing" hint="At least 8 characters with a letter and a number." />
        <x-field name="password_confirmation" label="Confirm password" type="password" icon="lock"
                 autocomplete="new-password" :required="! $editing" />
    </div>
</div>

<div class="form-section">
    <h3>Profile picture</h3>
    <div class="avatar-form">
        <x-avatar :user="$user" size="lg" id="avatar-preview" />
        <div class="avatar-actions">
            <label class="btn-secondary file-button">
                <x-icon name="camera" /> <span>Choose picture</span>
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                       data-preview="#avatar-preview" data-filename="#avatar-filename" class="visually-hidden">
            </label>
            <span id="avatar-filename" class="muted file-name">JPG, PNG or WebP, up to 2 MB</span>
            @if ($user->avatar_path)
                <label class="checkbox checkbox-inline">
                    <input type="checkbox" name="remove_avatar" value="1" @checked(old('remove_avatar'))>
                    <span>Remove current picture</span>
                </label>
            @endif
        </div>
    </div>
    @error('avatar')
        <p class="field-error">{{ $message }}</p>
    @enderror
</div>

<div class="form-section">
    <h3>Access</h3>
    <label class="switch">
        <input type="hidden" name="is_admin" value="0">
        <input type="checkbox" name="is_admin" value="1" @checked(old('is_admin', $user->is_admin))>
        <span class="switch-track"></span>
        <span><strong>Administrator</strong><small>Can open the admin panel and manage all users and posts.</small></span>
    </label>
    @error('is_admin')
        <p class="field-error">{{ $message }}</p>
    @enderror

    <label class="switch">
        <input type="hidden" name="verified" value="0">
        <input type="checkbox" name="verified" value="1" @checked(old('verified', $editing ? $user->hasVerifiedEmail() : true))>
        <span class="switch-track"></span>
        <span><strong>Email verified</strong><small>Unverified users must click the link in their email before using the app.</small></span>
    </label>
</div>
