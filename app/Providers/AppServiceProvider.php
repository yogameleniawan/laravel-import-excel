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
        $this->app->bind("YogaMeleniawan\JobBatchingWithRealtimeProgress\Interfaces\\RealtimeJobBatchInterface", "App\Repositories\\VerificationRepository");
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
