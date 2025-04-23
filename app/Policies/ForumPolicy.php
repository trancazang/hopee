<?php

namespace App\Policies;

use App\Models\User;
use TeamTeaTime\Forum\Policies\ForumPolicy as Base;

class ForumPolicy extends Base
{
    public function createCategories($user): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);
    }

    public function moveCategories($user): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);
    }

    public function renameCategories($user): bool
    {
        return in_array($user->role, haystack: ['admin', 'moderator']);
    }

    public function viewTrashedThreads($user): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
}
