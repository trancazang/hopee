<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Thread;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Policies\CategoryPolicy;
use TeamTeaTime\Forum\Policies\ThreadPolicy;
use TeamTeaTime\Forum\Policies\PostPolicy;

use App\Models\Test;
use App\Policies\TestPolicy;

use Illuminate\Support\Facades\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected $policies = [
        Category::class => CategoryPolicy::class,
        Thread::class => ThreadPolicy::class,
        Post::class => PostPolicy::class,
        Test::class => TestPolicy::class,
    ];

}
