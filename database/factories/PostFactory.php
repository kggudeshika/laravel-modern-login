<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(array_keys(Post::STATUSES));

        return [
            'user_id' => User::factory(),
            'title' => rtrim(fake()->sentence(6), '.'),
            'category' => fake()->randomElement(array_keys(Post::CATEGORIES)),
            'status' => $status,
            'published_on' => $status === 'published' ? fake()->dateTimeBetween('-6 months') : null,
            'is_featured' => fake()->boolean(25),
            'body' => fake()->paragraphs(4, true),
        ];
    }
}
