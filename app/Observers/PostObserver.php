<?php

namespace App\Observers;

use TeamTeaTime\Forum\Models\Post;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
   
     public function creating(Post $post): void
     { 
        $components = request('components');

        $parentId = null;
        if (is_array($components) && isset($components[0]['snapshot'])) {
            $snapshot = json_decode($components[0]['snapshot'], true);
            $parentId = $snapshot['data']['parent'][1]['key'] ?? null;
        }
    
        \Log::info('🪵 Observer ran - parent_id: ' . $parentId);
    
        if ($parentId) {
            $post->post_id = $parentId;
        }
     }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
