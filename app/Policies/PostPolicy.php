<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function modify(User $user, Post $post): Response
    {
        return $user->id === $post->user_id ? Response::allow() : Response::deny("You do not own this post");
    }

    public function view(User $user, Post $post)
    {
        // Allow the user to view the post if it's public or the user is the creator of the post
        return $post->status === 'public' || $post->user_id === $user->id;
    }
}
