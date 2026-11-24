<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_renders(): void
    {
        $this->get('/register')->assertOk()->assertSee('Create your account');
    }

    public function test_new_user_can_register_and_gets_a_verification_email(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ])->assertRedirect('/verify-email');

        $this->assertAuthenticated();

        $user = User::where('email', 'jane@example.com')->firstOrFail();
        $this->assertFalse($user->isAdmin());
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_registration_requires_matching_passwords_and_terms(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ])->assertRedirect('/register')
            ->assertSessionHasErrors(['password', 'terms']);

        $this->assertGuest();
    }

    public function test_password_needs_a_number(): void
    {
        $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'onlyletters',
            'password_confirmation' => 'onlyletters',
            'terms' => '1',
        ])->assertSessionHasErrors('password');
    }

    public function test_users_cannot_make_themselves_admin_when_signing_up(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Sneaky',
            'email' => 'sneaky@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
            'is_admin' => '1',
        ]);

        $this->assertFalse(User::where('email', 'sneaky@example.com')->firstOrFail()->isAdmin());
    }
}
