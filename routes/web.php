<?php

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
