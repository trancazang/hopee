<?php

namespace App\Policies;

//use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Policies\CategoryPolicy as Base;

class CategoryPolicy extends Base
{
    public function edit($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function deleteThreads($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function enableThreads($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function moveThreadsFrom($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function moveThreadsTo($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function lockThreads($user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }

    public function pinThreads($user, Category $category): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);
    }

    public function view($user, Category $category): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);

    }

    public function delete($user, Category $category): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);

    }
}
