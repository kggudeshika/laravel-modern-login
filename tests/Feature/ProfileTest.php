<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_renders(): void
    {
        $this->actingAs(User::factory()->create())->get('/profile')->assertOk()->assertSee('Profile picture');
    }

    public function test_name_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', ['name' => 'New Name', 'email' => $user->email])
            ->assertRedirect('/profile');

        $this->assertSame('New Name', $user->fresh()->name);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_changing_email_requires_verifying_it_again(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', ['name' => $user->name, 'email' => 'new@example.com'])
            ->assertRedirect('/verify-email');

        $user->refresh();
        $this->assertSame('new@example.com', $user->email);
        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_avatar_can_be_uploaded_replaced_and_removed(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        // Create
        $this->actingAs($user)
            ->post('/profile/avatar', ['avatar' => UploadedFile::fake()->image('me.jpg', 200, 200)])
            ->assertRedirect('/profile');
        $first = $user->fresh()->avatar_path;
        Storage::disk('public')->assertExists($first);

        // Replace (old file is deleted)
        $this->actingAs($user)
            ->post('/profile/avatar', ['avatar' => UploadedFile::fake()->image('new.png', 300, 300)]);
        $second = $user->fresh()->avatar_path;
        Storage::disk('public')->assertExists($second);
        Storage::disk('public')->assertMissing($first);

        // Delete
        $this->actingAs($user)->delete('/profile/avatar')->assertRedirect('/profile');
        $this->assertNull($user->fresh()->avatar_path);
        Storage::disk('public')->assertMissing($second);
    }

    public function test_avatar_must_be_an_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/profile/avatar', ['avatar' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf')])
            ->assertSessionHasErrorsIn('avatar', 'avatar');

        $this->assertNull($user->fresh()->avatar_path);
    }

    public function test_password_can_be_changed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'brand-new-pass1',
            'password_confirmation' => 'brand-new-pass1',
        ])->assertRedirect('/profile');

        $this->assertTrue(Hash::check('brand-new-pass1', $user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrong',
            'password' => 'brand-new-pass1',
            'password_confirmation' => 'brand-new-pass1',
        ])->assertSessionHasErrorsIn('password', 'current_password');
    }

    public function test_account_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete('/profile', ['delete_password' => 'password'])
            ->assertRedirect('/login');

        $this->assertGuest();
        $this->assertModelMissing($user);
    }
}
