<?php

namespace App\Policies;

//use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Policies\ThreadPolicy as Base;

class ThreadPolicy extends Base
{
    // public function deletePosts($user, Thread $thread): bool
    // {
    //     return  in_array($user->role, ['admin', 'moderator']);
    // }

    public function restorePosts($user, Thread $thread): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
        
    public function rename($user, Thread $thread): bool
    {
        return $user->id === $thread->author_id || in_array($user->role, ['admin', 'moderator']);
    }

    public function delete($user, Thread $thread): bool
    {
        return $user->id === $thread->author_id || in_array($user->role, ['admin', 'moderator']);
    }

    public function restore($user, Thread $thread): bool
    {
        return $user->id === $thread->author_id || in_array($user->role, ['admin', 'moderator']);
    }

    public function reply($user, Thread $thread): bool
    {
        return !$thread->locked || in_array($user->role, ['admin', 'moderator']);
    }
}