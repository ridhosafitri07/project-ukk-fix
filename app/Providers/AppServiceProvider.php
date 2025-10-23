<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Register the app-layout component
        \Illuminate\Support\Facades\Blade::component('app-layout', \App\View\Components\AppLayout::class);
    }
}
