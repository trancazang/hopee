<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use TeamTeaTime\Forum\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfWeek();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfWeek();

        $weeks = collect();
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();

            $weeks->push([
                'label' => $weekStart->format('d/m'),
                'posts' => Post::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'users' => User::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            ]);
        }

        $postThisWeek = Post::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $postLastWeek = Post::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

        $userThisWeek = User::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $userLastWeek = User::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

        $postChange = $this->calcGrowth($postThisWeek, $postLastWeek);
        $userChange = $this->calcGrowth($userThisWeek, $userLastWeek);

        return view('dashboard', compact(
            'weeks',
            'postThisWeek', 'postLastWeek', 'postChange',
            'userThisWeek', 'userLastWeek', 'userChange',
            'startDate', 'endDate'
        ));
    }

    private function calcGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? '∞%' : '0%';
        return number_format((($current - $previous) / $previous) * 100, 1) . '%';
    }
}
