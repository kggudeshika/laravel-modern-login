<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * Decides who may do what with a post.
 * Laravel finds this class automatically because it is named {Model}Policy.
 */
class PostPolicy
{
    public function view(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }
}
