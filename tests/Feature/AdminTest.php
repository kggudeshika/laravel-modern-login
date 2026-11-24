<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_normal_users_cannot_open_the_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/users')->assertForbidden();
        $this->actingAs($user)->get('/admin/posts')->assertForbidden();
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_admin_pages_render(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Admin dashboard');
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/users/create')->assertOk();
        $this->actingAs($admin)->get(route('admin.users.show', $post->user))->assertOk();
        $this->actingAs($admin)->get(route('admin.users.edit', $post->user))->assertOk();
        $this->actingAs($admin)->get('/admin/posts')->assertOk()->assertSee($post->title);
        $this->actingAs($admin)->get('/admin/posts/create')->assertOk();
        $this->actingAs($admin)->get(route('admin.posts.show', $post))->assertOk();
        $this->actingAs($admin)->get(route('admin.posts.edit', $post))->assertOk();
    }

    public function test_admin_can_create_a_user_with_avatar(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Person',
            'email' => 'person@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'is_admin' => '1',
            'verified' => '1',
            'avatar' => UploadedFile::fake()->image('a.jpg', 120, 120),
        ])->assertSessionHasNoErrors();

        $user = User::where('email', 'person@example.com')->firstOrFail();
        $this->assertTrue($user->isAdmin());
        $this->assertTrue($user->hasVerifiedEmail());
        Storage::disk('public')->assertExists($user->avatar_path);
    }

    public function test_admin_can_change_a_users_password_and_role(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Renamed',
            'email' => $user->email,
            'password' => 'changed123',
            'password_confirmation' => 'changed123',
            'is_admin' => '1',
            'verified' => '1',
        ])->assertRedirect(route('admin.users.show', $user));

        $user->refresh();
        $this->assertSame('Renamed', $user->name);
        $this->assertTrue($user->isAdmin());
        $this->assertTrue(Hash::check('changed123', $user->password));
    }

    public function test_leaving_password_blank_keeps_the_old_one(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'verified' => '1',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_admin_cannot_remove_own_admin_role_or_delete_self(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'is_admin' => '0',
            'verified' => '1',
        ])->assertSessionHasErrors('is_admin');

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

        $this->assertModelExists($admin);
        $this->assertTrue($admin->fresh()->isAdmin());
    }

    public function test_admin_can_delete_a_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->has(Post::factory()->count(2))->create();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $user))->assertRedirect('/admin/users');

        $this->assertModelMissing($user);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_admin_can_manage_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $author = User::factory()->create();

        $this->actingAs($admin)->post('/admin/posts', [
            'user_id' => $author->id,
            'title' => 'Written by admin for author',
            'category' => 'news',
            'status' => 'draft',
            'body' => 'Some body text here.',
        ])->assertSessionHasNoErrors();

        $post = Post::firstOrFail();
        $this->assertSame($author->id, $post->user_id);

        $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'user_id' => $admin->id,
            'title' => 'Changed title',
            'category' => 'news',
            'status' => 'draft',
            'body' => 'Some body text here.',
        ])->assertRedirect(route('admin.posts.show', $post));

        $post->refresh();
        $this->assertSame('Changed title', $post->title);
        $this->assertSame($admin->id, $post->user_id);

        $this->actingAs($admin)->delete(route('admin.posts.destroy', $post))->assertRedirect('/admin/posts');
        $this->assertModelMissing($post);
    }
}
