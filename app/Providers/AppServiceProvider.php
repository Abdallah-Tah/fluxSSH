<?php

namespace App\Providers;

use App\Services\SSH\SSHManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register SSH Manager as singleton
        $this->app->singleton(SSHManager::class, function ($app) {
            return new SSHManager();
        });

        // Alias for easier access
        $this->app->alias(SSHManager::class, 'ssh');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
