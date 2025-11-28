<?php

use App\Livewire\ServerList;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Http\Controllers\WebSocketTerminalController;
use App\Http\Controllers\RealtimeTerminalController;
use App\Http\Controllers\SimpleTerminalController;
use App\Models\Server;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('about', 'about')->name('about');
Route::view('contact', 'contact')->name('contact');
Route::view('terms', 'terms')->name('terms');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // SSH Management Routes
    Route::get('servers', ServerList::class)->name('servers');
    Route::get('servers/{server}', function (Server $server) {
        return view('server.show', compact('server'));
    })->name('servers.show');
    Route::get('console/{server}', function (Server $server) {
        return view('console', compact('server'));
    })->name('console');

    // New xterm terminal route
    Route::get('terminal/{server}', function (Server $server) {
        return view('xterm-console', compact('server'));
    })->name('terminal');

    // Real-time terminal route (SSE-based like cloud providers)
    Route::get('terminal-live/{server}', [RealtimeTerminalController::class, 'show'])->name('terminal.live');

    // Simple terminal (works reliably)
    Route::get('ssh/{server}', [SimpleTerminalController::class, 'show'])->name('ssh');

    // Terminal API routes (accept JSON)
    Route::post('/api/terminal/{server}/connect', [WebSocketTerminalController::class, 'connect']);
    Route::post('/api/terminal/{server}/shell', [WebSocketTerminalController::class, 'shell']);
    Route::post('/api/terminal/{server}/execute', [WebSocketTerminalController::class, 'execute']);
    Route::post('/api/terminal/{server}/execute-pty', [WebSocketTerminalController::class, 'executeWithPty']);
    Route::post('/api/terminal/{server}/disconnect', [WebSocketTerminalController::class, 'disconnect']);

    // Real-time terminal API routes (SSE)
    Route::post('/api/realtime-terminal/{server}/connect', [RealtimeTerminalController::class, 'connect']);
    Route::get('/api/realtime-terminal/{server}/stream', [RealtimeTerminalController::class, 'stream']);
    Route::post('/api/realtime-terminal/{server}/input', [RealtimeTerminalController::class, 'input']);
    Route::post('/api/realtime-terminal/{server}/resize', [RealtimeTerminalController::class, 'resize']);
    Route::post('/api/realtime-terminal/{server}/disconnect', [RealtimeTerminalController::class, 'disconnect']);

    // Simple terminal API
    Route::post('/api/simple-terminal/{server}/execute', [SimpleTerminalController::class, 'execute']);
    Route::post('/api/simple-terminal/{server}/info', [SimpleTerminalController::class, 'info']);

    // Settings Routes
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    Route::get('settings/api-keys', \App\Livewire\Settings\ApiKeys::class)->name('api-keys.index');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
