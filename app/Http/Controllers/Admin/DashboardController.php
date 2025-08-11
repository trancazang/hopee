<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;


class DashboardController extends Controller
{
   
    public function index(Request $request)
{   $allPosts = DB::table('forum_posts')->whereNotNull('forum_posts.created_at');
    $postBase = (clone $allPosts);
    $voteBase = DB::table('forum_post_votes')->whereNotNull('forum_post_votes.created_at');
    $month = $request->input('month');
    $year = $request->input('year');

    

    if ($month) {
        $postBase->whereMonth('forum_posts.created_at', $month);
        $voteBase->whereMonth('forum_post_votes.created_at', $month);
    }
    if ($year) {
        $postBase->whereYear('forum_posts.created_at', $year);
        $voteBase->whereYear('forum_post_votes.created_at', $year);
    }
    

    // Tổng số
    $userCount = User::count();
    $postCount = DB::table('forum_posts')->count();
    $threadCount = DB::table('forum_threads')->count();

    // Bài viết theo tháng (đủ 12 tháng)
    $postsByMonth = array_fill(1, 12, 0);
    $monthlyData = (clone $allPosts)
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupByRaw('MONTH(created_at)')
        ->pluck('total', 'month')
        ->toArray();

    foreach ($monthlyData as $m => $count) {
        $postsByMonth[(int)$m] = $count;
    }   

    // Chủ đề nổi bật
    $topThreads = (clone $postBase)
        ->select('thread_id', DB::raw('COUNT(*) as post_count'))
        ->groupBy('thread_id')
        ->orderByDesc('post_count')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            $thread = DB::table('forum_threads')->where('id', $item->thread_id)->first();
            return [
                'title' => $thread->title ?? 'Không rõ',
                'count' => $item->post_count
            ];
        });

    // Bài viết có ảnh hưởng
    $topPosts = (clone $postBase)
        ->leftJoin('forum_post_votes', 'forum_posts.id', '=', 'forum_post_votes.post_id')
        ->select('forum_posts.id', 'forum_posts.content', DB::raw("
            SUM(CASE WHEN vote_type = 'upvote' THEN 1 
                     WHEN vote_type = 'downvote' THEN -1 
                     ELSE 0 END) as score
        "))
        ->groupBy('forum_posts.id', 'forum_posts.content')
        ->orderByDesc('score')
        ->limit(5)
        ->get();

    // Chuyên gia có ảnh hưởng (tổng điểm vote trên bài viết do họ viết)
    $topMod = (clone $postBase)
    ->join('forum_post_votes', 'forum_posts.id', '=', 'forum_post_votes.post_id')
    ->join('users', 'forum_posts.author_id', '=', 'users.id')
    ->where('users.role', 'moderator') // chỉ lấy moderator
    ->select('forum_posts.author_id', DB::raw("
        COUNT(DISTINCT forum_posts.id) as post_count,
        SUM(CASE WHEN forum_post_votes.vote_type = 'upvote' THEN 1 ELSE 0 END) as upvote_count
    "))
    ->groupBy('forum_posts.author_id')
    ->orderByDesc('upvote_count')
    ->limit(5)
    ->get()
    ->map(function ($userStat) {
        $user = User::find($userStat->author_id);
        return [
            'name' => $user?->name ?? 'Không rõ',
            'upvotes' => $userStat->upvote_count,
            'posts'   => $userStat->post_count,
        ];
    });
    // Top user có ảnh hưởng (tổng điểm vote trên bài viết do họ viết)
    $topUsers = (clone $postBase)
    ->join('forum_post_votes', 'forum_posts.id', '=', 'forum_post_votes.post_id')
    ->select('forum_posts.author_id', DB::raw("
        SUM(
            CASE WHEN forum_post_votes.vote_type = 'upvote' THEN 1
                 WHEN forum_post_votes.vote_type = 'downvote' THEN -1
                 ELSE 0
            END
        ) as total_score
    "))
    ->groupBy('forum_posts.author_id')
    ->orderByDesc('total_score')
    ->limit(5)
    ->get()
    ->map(function ($stat) {
        $user = User::find($stat->author_id);
        return [
            'name'  => $user?->name ?? 'Không rõ',
            'score' => $stat->total_score,
        ];
    });
    //  chủ đề có nhiều bài viết nhất
    $postsByThread = DB::table('forum_threads')
        ->joinSub(clone $postBase, 'filtered_posts', function ($join) {
            $join->on('forum_threads.id', '=', 'filtered_posts.thread_id');
        })
        ->select('forum_threads.title', DB::raw('COUNT(filtered_posts.id) as total'))
        ->groupBy('forum_threads.title')
        ->orderByDesc('total')
        ->limit(10)
        ->pluck('total', 'forum_threads.title')
        ->toArray();

    // Từ khóa phổ biến
    $allText = (clone $postBase)->pluck('content')->implode(' ');
    $cleanText = strtolower(strip_tags($allText));
    $cleanText = preg_replace('/[^a-zA-ZÀ-ỹ0-9\s]/u', '', $cleanText);
    $words = explode(' ', $cleanText);

    $phrases = [];
    for ($i = 0; $i < count($words) - 1; $i++) {
        $one = $words[$i];
        $two = $words[$i] . ' ' . $words[$i + 1];
        $three = $i < count($words) - 2 ? $two . ' ' . $words[$i + 2] : null;

        $phrases[] = $one;
        $phrases[] = $two;
        if ($three) $phrases[] = $three;
    }

    $filtered = array_filter($phrases, fn($p) => mb_strlen(trim($p)) > 3);
    $freq = array_count_values($filtered);
    arsort($freq);
    $topKeywordsRaw = array_slice($freq, 0, 30, true); // dùng cho danh sách
    $topKeywords = collect($topKeywordsRaw)
    ->map(function ($count, $word) {
        return ['text' => $word, 'size' => 10 + $count * 2, 'count' => $count];
    })
    ->values(); // dùng cho word cloud

        // word cloud dùng nhiều hơn
    // Lọc tuần theo tháng và năm (nếu có)
    $postsByWeek = (clone $postBase)
    ->selectRaw('WEEK(created_at, 1) as week, COUNT(*) as total')
    ->groupByRaw('WEEK(created_at, 1)')
    ->orderBy('week');

    if ($month) {
    $postsByWeek->whereMonth('created_at', $month);
    }
    if ($year) {
    $postsByWeek->whereYear('created_at', $year);
    }

    $postsByWeek = $postsByWeek->pluck('total', 'week')->toArray();

    // Nhãn tuần (dựa vào tháng/năm lọc)
    $labelsByWeek = collect(array_keys($postsByWeek))->map(function ($week) use ($year) {
    $baseYear = $year ?? Carbon::now()->year;
    $start = Carbon::createFromDate($baseYear)->startOfYear()->addWeeks($week - 1)->startOfWeek();
    $end = (clone $start)->endOfWeek();
    return "Tuần $week\n(" . $start->format('d/m') . ' - ' . $end->format('d/m') . ")";
    })->toArray();
        
    // Lấy ngày bắt đầu & kết thúc tuần hiện tại (hoặc dựa vào request nếu có lọc)
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    // Người dùng mới trong tuần
    $newUsersThisWeek = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
    $newUsersList = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])
    ->orderBy('created_at', 'desc')
    ->get(['name', 'email', 'created_at']);

    // Bài viết mới trong tuần
    $newPostsThisWeek = DB::table('forum_posts')
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->count();

    return view('admin.dashboard', compact(
        'userCount',
        'postCount',
        'threadCount',
        'postsByMonth',
        'postsByWeek',
        'labelsByWeek',
        'topThreads',
        'topPosts',
        'topUsers',
        'topKeywords',
        'topKeywordsRaw',  
        'postsByThread',
        'newUsersThisWeek',
        'newUsersList',
        'newPostsThisWeek',
        'month',
        'year',
        'topMod'
    ));
    
}

}
