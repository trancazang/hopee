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
use TeamTeaTime\Forum\Support\Authorization\PostAuthorization as PackagePostAuth;
use App\Support\Authorization\PostAuthorization as AppPostAuth;

use Illuminate\Support\Facades\Gate;

use App\Observers\PostObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!class_exists('OriginalPostAuthorization')) {
            class_alias(AppPostAuth::class, 'OriginalPostAuthorization');
        }
    
        class_alias(AppPostAuth::class, PackagePostAuth::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class);
        app()->bind(PackagePostAuth::class, function () {
            return new AppPostAuth();
        });
        view()->composer('*', function ($view) {
            $recentThreads = Thread::latest()->limit(6)->with('category')->get();
            $view->with('recentThreads', $recentThreads);
            $view->with('miniChat', true); // flag

        });
        
    }

    protected $policies = [
        Category::class => CategoryPolicy::class,
        Thread::class => ThreadPolicy::class,
        Post::class => PostPolicy::class,
        Test::class => TestPolicy::class,
        \Musonza\Chat\Models\Conversation::class => \App\Policies\ConversationPolicy::class,
        
    ];

}
