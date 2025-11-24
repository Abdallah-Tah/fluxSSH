<?php

use App\Models\Server;
use App\Models\User;

it('can create whisp shell configuration', function () {
    expect(file_exists(__DIR__ . '/../../whisp/whisp.php'))->toBeTrue();

    $config = require __DIR__ . '/../../whisp/whisp.php';
    expect($config)->toBeArray();
    expect($config['apps']['ssh-shell'])->toBeArray();
});

it('can access ssh shell app file', function () {
    expect(file_exists(__DIR__ . '/../../whisp/apps/ssh-shell.php'))->toBeTrue();
});

it('whisp shell app has executable permissions', function () {
    $shellApp = __DIR__ . '/../../whisp/apps/ssh-shell.php';
    expect(file_exists($shellApp))->toBeTrue();

    // Check if file is readable
    expect(is_readable($shellApp))->toBeTrue();
});

it('can track server last connected timestamp', function () {
    $user = User::factory()->create();

    $server = Server::factory()->create([
        'last_connected_at' => null,
    ]);

    expect($server->last_connected_at)->toBeNull();

    $server->update(['last_connected_at' => now()]);
    $server->refresh();

    expect($server->last_connected_at)->not->toBeNull();
    expect($server->last_connected_at)->toBeInstanceOf(\Carbon\Carbon::class);
});
