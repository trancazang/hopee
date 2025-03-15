<?php

namespace App\Policies;

use App\Models\User;
use TeamTeaTime\Forum\Policies\ForumPolicy as Base;

class ForumPolicy extends Base
{
    public function createCategories($user): bool
    {
        return $user->role == 'admin';
    }

    public function moveCategories($user): bool
    {
        return $user->role == 'admin';
    }

    public function renameCategories($user): bool
    {
        return $user->role == 'admin';
    }

    public function viewTrashedThreads($user): bool
    {
        return in_array($user->role, ['admin', 'moderator']);
    }
}
