<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_renders(): void
    {
        $this->actingAs(User::factory()->create())->get('/profile')->assertOk();
    }

    public function test_profile_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', ['name' => 'New Name', 'email' => 'new@example.com'])
            ->assertRedirect('/profile');

        $user->refresh();
        $this->assertSame('New Name', $user->name);
        $this->assertSame('new@example.com', $user->email);
    }

    public function test_password_can_be_changed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
        ])->assertRedirect('/profile');

        $this->assertTrue(Hash::check('brand-new-pass', $user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrong',
            'password' => 'brand-new-pass',
            'password_confirmation' => 'brand-new-pass',
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
