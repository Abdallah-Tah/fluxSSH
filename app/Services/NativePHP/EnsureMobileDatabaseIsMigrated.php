<?php

namespace App\Services\NativePHP;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class EnsureMobileDatabaseIsMigrated
{
    public function handle(): void
    {
        $platform = getenv('NATIVEPHP_PLATFORM') ?: ($_SERVER['NATIVEPHP_PLATFORM'] ?? null);

        if (! in_array($platform, ['android', 'ios'], true)) {
            return;
        }

        $schemaDirectory = base_path('database/schema');

        if (File::isDirectory($schemaDirectory)) {
            File::deleteDirectory($schemaDirectory);
        }

        // Check if database needs migration by checking if servers table exists
        if (! Schema::hasTable('servers')) {
            // Run migrations for mobile environment
            Artisan::call('migrate', ['--force' => true]);
        }
    }
}
