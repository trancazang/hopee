<?php

namespace App\Livewire\Forum\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use TeamTeaTime\Forum\Models\Post as Post;
use App\Models\ForumPostVote as Vote;
class VotePost extends Component
{ public $postId;
    public $score;
    public $userVote = null; // 'upvote', 'downvote', or null

    public function mount($postId, $initialScore, $initialVote)
    {
        $this->postId = $postId;
        $this->score = $initialScore;
        $this->userVote = $initialVote;
    }

    public function vote($type)
{
    if (!Auth::check()) return;

    $post = Post::findOrFail($this->postId);
    $user = Auth::user();

    $existingVote = Vote::where('post_id', $this->postId)
                        ->where('user_id', $user->id)
                        ->first();

    if ($existingVote) {
        if ($existingVote->vote_type === $type) {
            // Hủy vote nếu bấm lại cùng loại
            $existingVote->delete();
            $this->score += ($type === 'upvote') ? -1 : 1;
            $this->userVote = null;
        } else {
            // Đổi loại vote (up <-> down)
            $existingVote->update(['vote_type' => $type]);
            $this->score += ($type === 'upvote') ? 2 : -2;
            $this->userVote = $type;
        }
    } else {
        // Lần đầu vote
        Vote::create([
            'post_id'   => $this->postId,
            'user_id'   => $user->id,
            'vote_type' => $type,
        ]);
        $this->score += ($type === 'upvote') ? 1 : -1;
        $this->userVote = $type;
    }
}

    public function render()
    {
        return view('livewire.forum.components.vote-post');
    }
}
