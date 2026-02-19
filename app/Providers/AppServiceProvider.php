<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\AdminNavigationComposer;
use App\View\Composers\AgentNavigationComposer;

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
        // Register view composers for navigation
        View::composer('admin.*', AdminNavigationComposer::class);
        View::composer('agent.*', AgentNavigationComposer::class);
    }
}
