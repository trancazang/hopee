<?php

namespace App\Policies;
use Musonza\Chat\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    use HandlesAuthorization;
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        if (in_array($user->role, ['admin', 'moderator'])) {
            return true;
        }
    
        // ✅ Ghi log để kiểm tra
        \Log::info("Checking if user {$user->id} is participant of conversation {$conversation->id}");
    
        return $conversation->participants()
                            ->where('messageable_id', $user->id)
                            ->exists();
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Conversation $conversation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Conversation $conversation): bool
    {
        return false;
    }
}
