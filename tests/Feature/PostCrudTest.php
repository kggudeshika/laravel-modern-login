<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return [
            'title' => 'My first post',
            'category' => 'tutorial',
            'status' => 'published',
            'published_on' => '2026-01-15',
            'is_featured' => '1',
            'body' => 'This is the body of my first post.',
            ...$overrides,
        ];
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/posts')->assertRedirect('/login');
    }

    public function test_index_lists_only_my_posts(): void
    {
        $user = User::factory()->create();
        Post::factory()->for($user)->create(['title' => 'Mine']);
        Post::factory()->create(['title' => 'Someone else']);

        $this->actingAs($user)->get('/posts')
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Someone else');
    }

    public function test_index_can_search(): void
    {
        $user = User::factory()->create();
        Post::factory()->for($user)->create(['title' => 'Laravel routing']);
        Post::factory()->for($user)->create(['title' => 'Cooking pasta']);

        $this->actingAs($user)->get('/posts?search=routing')
            ->assertSee('Laravel routing')
            ->assertDontSee('Cooking pasta');
    }

    public function test_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/posts/create')->assertOk();

        $response = $this->actingAs($user)->post('/posts', $this->validData());

        $post = Post::first();
        $response->assertRedirect(route('posts.show', $post));
        $this->assertSame($user->id, $post->user_id);
        $this->assertTrue($post->is_featured);
    }

    public function test_create_validates_input(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/posts', $this->validData(['title' => '', 'category' => 'nope', 'published_on' => '']))
            ->assertSessionHasErrors(['title', 'category', 'published_on']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_user_can_view_and_update_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['is_featured' => true]);

        $this->actingAs($user)->get(route('posts.show', $post))->assertOk()->assertSee($post->title);
        $this->actingAs($user)->get(route('posts.edit', $post))->assertOk();

        $this->actingAs($user)
            ->put(route('posts.update', $post), $this->validData(['title' => 'Updated title', 'is_featured' => '0']))
            ->assertRedirect(route('posts.show', $post));

        $post->refresh();
        $this->assertSame('Updated title', $post->title);
        $this->assertFalse($post->is_featured);
    }

    public function test_user_can_delete_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user)->delete(route('posts.destroy', $post))->assertRedirect('/posts');

        $this->assertModelMissing($post);
    }

    public function test_user_cannot_touch_someone_elses_post(): void
    {
        $post = Post::factory()->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)->get(route('posts.show', $post))->assertForbidden();
        $this->actingAs($intruder)->get(route('posts.edit', $post))->assertForbidden();
        $this->actingAs($intruder)->put(route('posts.update', $post), $this->validData())->assertForbidden();
        $this->actingAs($intruder)->delete(route('posts.destroy', $post))->assertForbidden();

        $this->assertModelExists($post);
    }
}
