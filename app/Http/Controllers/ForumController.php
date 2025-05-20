<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ForumPostVote;
use App\Models\ForumPostReport;
use TeamTeaTime\Forum\Models\Post;
use App\Models\ForumPost;

class ForumController extends Controller
{
    public function vote(Request $request, $postId)
    {
        $post = ForumPost::findOrFail($postId);

        ForumPostVote::updateOrCreate(
            ['user_id' => auth()->id(), 'post_id' => $post->id],
            ['vote_type' => $request->vote]
        );

        return back();
    }

    public function report(Request $request, $postId)
    {
        $post = ForumPost::findOrFail($postId);

        $reason = $request->input('reason_custom') ?: $request->input('reason_select');
    
        ForumPostReport::updateOrCreate(
            ['user_id' => auth()->id(), 'post_id' => $post->id],
            ['reason' => $reason]
        );
    
        return back()->with('success', 'Báo cáo của bạn đã được gửi thành công!');
    }
}
