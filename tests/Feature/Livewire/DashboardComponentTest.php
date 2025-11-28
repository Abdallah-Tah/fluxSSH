<?php

declare(strict_types=1);

use App\Livewire\Dashboard;
use App\Models\CommandHistory;
use App\Models\Server;
use App\Models\User;
use Livewire\Livewire;

it('returns dashboard data via toJSON helper', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'user_id' => $user->id,
        'is_active' => true,
    ]);
    CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
    ]);

    Livewire::actingAs($user)
        ->test(Dashboard::class)
        ->call('toJSON')
        ->assertReturned(function (array $payload) use ($server) {
            expect($payload['servers'])->not->toBeEmpty();
            expect(collect($payload['servers'])->pluck('name'))->toContain($server->name);
            expect($payload['stats']['total'])->toBeGreaterThanOrEqual(1);

            return true;
        });
});
