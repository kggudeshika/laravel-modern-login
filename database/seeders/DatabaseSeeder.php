<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Demo accounts (password for both: "password"):
     *   admin@example.com  — admin, can open /admin
     *   test@example.com   — normal user
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Post::factory(15)->for($user)->create();
        Post::factory(5)->for($admin)->create();

        // A few extra users so the admin panel has something to manage.
        User::factory(8)->has(Post::factory()->count(3))->create();
        User::factory(2)->unverified()->create();
    }
}
