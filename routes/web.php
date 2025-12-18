<?php

use App\Http\Controllers\RealtimeTerminalController;
use App\Http\Controllers\SimpleTerminalController;
use App\Http\Controllers\TestTerminalController;
use App\Http\Controllers\TtydTerminalController;
use App\Http\Controllers\WebSocketTerminalController;
use App\Livewire\ServerList;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Models\Server;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('about', 'about')->name('about');
Route::view('contact', 'contact')->name('contact');
Route::view('terms', 'terms')->name('terms');

// Debug route to test mobile debugging
Route::get('debug-test', function () {
    \App\Services\MobileDebugger::debug(['test' => 'Mobile debugging works!', 'timestamp' => now()], 'Debug Test');
    return response()->json(['message' => 'Debug logged - check storage/logs/mobile_debug.log']);
})->name('debug.test');

// Debug info page for mobile debugging
Route::get('debug-info', function () {
    $debugInfo = [
        'timestamp' => now(),
        'environment' => app()->environment(),
        'nativephp_running' => config('nativephp-internal.running', false),
        'debug_mode' => config('app.debug'),
        'recent_logs' => [],
        'session_data' => session()->all(),
        'request_data' => request()->all(),
        'platform_info' => [
            'user_agent' => request()->userAgent(),
            'is_mobile' => request()->header('User-Agent') ? str_contains(request()->header('User-Agent'), 'Mobile') : false,
            'nativephp_platform' => getenv('NATIVEPHP_PLATFORM') ?: 'unknown',
        ]
    ];

    // Get recent debug logs if they exist
    $debugFile = storage_path('logs/mobile_debug.log');
    if (file_exists($debugFile)) {
        $logs = file($debugFile);
        $debugInfo['recent_logs'] = array_slice($logs, -20); // Last 20 lines
    }

    // Get recent Laravel logs
    $laravelLog = storage_path('logs/laravel.log');
    if (file_exists($laravelLog)) {
        $logs = file($laravelLog);
        $debugInfo['recent_laravel_logs'] = array_slice($logs, -10); // Last 10 lines
    }

    return view('debug.info', compact('debugInfo'));
})->name('debug.info');

// Test Terminal (no auth required for demo)
Route::get('test-terminal', [TestTerminalController::class, 'show'])->name('test-terminal');
Route::post('test-terminal/start', [TestTerminalController::class, 'start'])->name('test-terminal.start');

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

    // Livewire Interactive Terminal (for NativePHP mobile)
    Route::get('shell/{server}', function (Server $server) {
        return view('terminal.ssh', compact('server'));
    })->name('shell');

    // ttyd terminal (professional solution using ttyd)
    Route::get('ttyd/{server}', [TtydTerminalController::class, 'show'])->name('terminal.ttyd');
    Route::post('ttyd/{server}/start', [TtydTerminalController::class, 'start'])->name('terminal.ttyd.start');
    Route::get('ttyd/proxy/{sessionId}', [TtydTerminalController::class, 'proxy'])->name('terminal.ttyd.proxy');
    Route::post('ttyd/stop/{sessionId}', [TtydTerminalController::class, 'stop'])->name('terminal.ttyd.stop');
    Route::post('terminal/theme/update', [TtydTerminalController::class, 'updateTheme'])->name('terminal.theme.update');

    // Professional Terminal with legacy design (Livewire + ttyd)
    Route::get('pro-terminal/{server}', \App\Livewire\Terminal\TtydTerminal::class)->name('terminal.pro');

    // Terminal API routes (accept JSON)
    Route::post('/api/terminal/{server}/connect', [WebSocketTerminalController::class, 'connect']);
    Route::post('/api/terminal/{server}/shell', [WebSocketTerminalController::class, 'shell']);
    Route::post('/api/terminal/{server}/execute', [WebSocketTerminalController::class, 'execute']);
    Route::post('/api/terminal/{server}/execute-pty', [WebSocketTerminalController::class, 'executeWithPty']);
    Route::post('/api/terminal/{server}/disconnect', [WebSocketTerminalController::class, 'disconnect']);

    // Real-time terminal API routes (SSE)
    Route::post('/api/realtime-terminal/{server}/connect', [RealtimeTerminalController::class, 'connect'])->name('terminal.connect');
    Route::get('/api/realtime-terminal/{server}/stream', [RealtimeTerminalController::class, 'stream'])->name('terminal.stream');
    Route::post('/api/realtime-terminal/{server}/input', [RealtimeTerminalController::class, 'input'])->name('terminal.input');
    Route::post('/api/realtime-terminal/{server}/resize', [RealtimeTerminalController::class, 'resize'])->name('terminal.resize');
    Route::post('/api/realtime-terminal/{server}/disconnect', [RealtimeTerminalController::class, 'disconnect'])->name('terminal.disconnect');

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
