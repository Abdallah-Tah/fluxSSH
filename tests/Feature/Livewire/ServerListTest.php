<?php

declare(strict_types=1);

use App\Models\{User, Server};
use App\Livewire\ServerList;
use Livewire\Livewire;

test('server list component renders successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerList::class)
        ->assertStatus(200)
        ->assertSee('SSH Servers')
        ->assertSee('Add Server');
});

test('server list displays servers', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'name' => 'Test Server',
        'host' => 'test.example.com',
        'is_active' => true
    ]);

    $this->actingAs($user);

    Livewire::test(ServerList::class)
        ->assertSee('Test Server')
        ->assertSee('test.example.com')
        ->assertSee('Active');
});

test('can show add server form', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerList::class)
        ->call('addServer')
        ->assertSet('showForm', true)
        ->assertSet('editingServer', null);
});

test('can search servers', function () {
    $user = User::factory()->create();
    Server::factory()->create(['name' => 'Production Server', 'is_active' => true]);
    Server::factory()->create(['name' => 'Test Server', 'is_active' => true]);

    $this->actingAs($user);

    Livewire::test(ServerList::class)
        ->set('search', 'Production')
        ->assertSee('Production Server')
        ->assertDontSee('Test Server');
});
