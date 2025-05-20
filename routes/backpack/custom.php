<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('forum-categories', 'ForumCategoriesCrudController');
    Route::crud('forumthreads', 'ForumthreadsCrudController');
    Route::crud('forum-post', 'ForumPostCrudController');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('backpack.dashboard');
    Route::crud('forum-post-report', 'ForumPostReportCrudController');
    Route::post('/forum-post-report/{id}/status', function ($id) {
        \App\Models\ForumPostReport::where('id', $id)->update([
            'status' => request('status'),
        ]);
        return response()->json(['success' => true]);
    });
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
