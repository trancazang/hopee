<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Models\Thread;
use Illuminate\Support\Facades\DB;
use App\Models\User;



class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $postCount = Post::count();
        $threadCount = Thread::count();

        // Bài viết theo tháng
        $postsByMonth = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        return view('admin.dashboard', compact('userCount', 'postCount', 'threadCount', 'postsByMonth'));
    }
}
