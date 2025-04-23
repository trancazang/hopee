<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ForumSearchController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->input('q');

        $categories = DB::table('forum_categories')
            ->where('title', 'like', "%{$term}%")
            ->get();

        $threads = DB::table('forum_threads')
            ->where('title', 'like', "%{$term}%")
            ->get();

        return view('forum.search_results', compact('term', 'categories', 'threads'));
    }
}
