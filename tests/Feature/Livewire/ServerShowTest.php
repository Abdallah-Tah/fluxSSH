<?php

declare(strict_types=1);

use App\Livewire\Server\Show;
use App\Models\ActivityLog;
use App\Models\CommandHistory;
use App\Models\Server;
use App\Models\User;
use App\Services\SSH\SSHManager;
use Livewire\Livewire;

function fakeServerStats(): void
{
    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function getServerStats(Server $server): array
        {
            return ['success' => true];
        }
    });
}

it('shows the overview tab by default', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'user_id' => $user->id,
        'cpu_usage' => 12.5,
        'memory_usage' => 48.3,
        'disk_usage' => 65.4,
    ]);

    fakeServerStats();

    Livewire::actingAs($user)
        ->test(Show::class, ['server' => $server])
        ->assertSet('activeTab', 'overview')
        ->assertSee('CPU Usage')
        ->assertSee($server->name);
});

it('renders server command history on the logs tab', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create(['user_id' => $user->id]);

    CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'ls -la',
        'current_directory' => '/home/forge',
    ]);

    CommandHistory::factory()->create([
        'command' => 'Noise entry',
    ]);

    fakeServerStats();

    Livewire::actingAs($user)
        ->test(Show::class, ['server' => $server])
        ->call('setTab', 'logs')
        ->assertSee('Recent Command History')
        ->assertSee('ls -la')
        ->assertDontSee('Noise entry');
});

it('shows recent activity for the server', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create(['user_id' => $user->id]);

    ActivityLog::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'type' => 'command',
        'action' => 'executed',
        'description' => 'Deployed release',
    ]);

    ActivityLog::factory()->create([
        'description' => 'Other server event',
    ]);

    fakeServerStats();

    Livewire::actingAs($user)
        ->test(Show::class, ['server' => $server])
        ->call('setTab', 'activity')
        ->assertSee('Latest Events')
        ->assertSee('Deployed release')
        ->assertDontSee('Other server event');
});
