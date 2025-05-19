<?php

namespace App\Policies;

//use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Policies\PostPolicy as Base;

class PostPolicy extends Base
{
    public function edit($user, Post $post): bool
    {
        return $user->id === $post->author_id || in_array($user->role, ['admin', 'moderator']);
    }

    public function delete($user, Post $post): bool
    {
        return $user->id === $post->author_id || in_array($user->role, ['admin', 'moderator']);
    }
    public function restore($user, Post $post): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
   
}