<?php

namespace App\Providers;

use App\Services\NativePHP\EnsureMobileDatabaseIsMigrated;
use Illuminate\Support\ServiceProvider;

class NativeAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        app(EnsureMobileDatabaseIsMigrated::class)->handle();
    }
}
