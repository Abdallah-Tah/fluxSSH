<?php

/** @var string|null $previousNativePlatform */
$previousNativePlatform = null;
/** @var string|null $previousNativePlatformServer */
$previousNativePlatformServer = null;

beforeEach(function () {
    global $previousNativePlatform, $previousNativePlatformServer;

    $previousNativePlatform = getenv('NATIVEPHP_PLATFORM') ?: null;
    $previousNativePlatformServer = $_SERVER['NATIVEPHP_PLATFORM'] ?? null;
});

afterEach(function () {
    global $previousNativePlatform, $previousNativePlatformServer;

    if (is_null($previousNativePlatform)) {
        putenv('NATIVEPHP_PLATFORM');
    } else {
        putenv('NATIVEPHP_PLATFORM='.$previousNativePlatform);
    }

    if (is_null($previousNativePlatformServer)) {
        unset($_SERVER['NATIVEPHP_PLATFORM']);
    } else {
        $_SERVER['NATIVEPHP_PLATFORM'] = $previousNativePlatformServer;
    }
});

test('auth pages include csrf meta tag', function () {
    $csrfToken = csrf_token();

    $this->get('/login')
        ->assertOk()
        ->assertSee('name="csrf-token"', false)
        ->assertSee($csrfToken, false);
});

test('auth forms submit as multipart', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('enctype="multipart/form-data"', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('enctype="multipart/form-data"', false);
});

test('auth pages use wire:navigate on web', function () {
    putenv('NATIVEPHP_PLATFORM');
    unset($_SERVER['NATIVEPHP_PLATFORM']);

    $this->get('/login')
        ->assertOk()
        ->assertSee('wire:navigate', false);
});

test('auth pages do not use wire:navigate on NativePHP mobile', function () {
    putenv('NATIVEPHP_PLATFORM=android');
    $_SERVER['NATIVEPHP_PLATFORM'] = 'android';

    $this->get('/login')
        ->assertOk()
        ->assertDontSee('wire:navigate', false);

    $this->get('/register')
        ->assertOk()
        ->assertDontSee('wire:navigate', false);
});
