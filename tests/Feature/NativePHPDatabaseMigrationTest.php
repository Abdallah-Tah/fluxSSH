<?php

use App\Services\NativePHP\EnsureMobileDatabaseIsMigrated;
use Illuminate\Support\Facades\File;

function unsetEnv(string $key): void
{
    putenv($key);
    unset($_ENV[$key], $_SERVER[$key]);
}

test('it does nothing when not running on NativePHP', function () {
    unsetEnv('NATIVEPHP_PLATFORM');
    unsetEnv('APP_RUNNING_IN_CONSOLE');

    $schemaDirectory = base_path('database/schema');
    expect(File::isDirectory($schemaDirectory))->toBeTrue();

    app(EnsureMobileDatabaseIsMigrated::class)->handle();

    expect(File::isDirectory($schemaDirectory))->toBeTrue();
});

test('it removes sqlite schema dumps on NativePHP mobile', function () {
    putenv('NATIVEPHP_PLATFORM=android');
    $_ENV['NATIVEPHP_PLATFORM'] = 'android';
    $_SERVER['NATIVEPHP_PLATFORM'] = 'android';
    unsetEnv('APP_RUNNING_IN_CONSOLE');

    expect(getenv('NATIVEPHP_PLATFORM'))->toBe('android');
    expect($_SERVER['NATIVEPHP_PLATFORM'])->toBe('android');
    expect(getenv('APP_RUNNING_IN_CONSOLE'))->not()->toBe('true');

    $schemaDirectory = base_path('database/schema');
    expect(File::isDirectory($schemaDirectory))->toBeTrue();

    app(EnsureMobileDatabaseIsMigrated::class)->handle();

    expect(File::isDirectory($schemaDirectory))->toBeFalse();
});
