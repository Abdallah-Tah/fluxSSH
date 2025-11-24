<?php

declare(strict_types=1);

use App\Models\{User, Server};
use App\Livewire\ServerForm;
use Livewire\Livewire;

test('server form component renders successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerForm::class)
        ->assertStatus(200)
        ->assertSee('Server Name')
        ->assertSee('Authentication Type');
});

test('can create a new server', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerForm::class)
        ->set('name', 'Test Server')
        ->set('host', 'test.example.com')
        ->set('port', 22)
        ->set('username', 'root')
        ->set('auth_type', 'password')
        ->set('password', 'secret')
        ->call('save')
        ->assertHasNoErrors();

    expect(Server::where('name', 'Test Server')->exists())->toBeTrue();
});

test('validates required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerForm::class)
        ->call('save')
        ->assertHasErrors(['name', 'host', 'username']);
});

test('can edit existing server', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'name' => 'Original Server',
        'host' => 'original.example.com'
    ]);

    $this->actingAs($user);

    Livewire::test(ServerForm::class, ['server' => $server])
        ->assertSet('name', 'Original Server')
        ->assertSet('host', 'original.example.com')
        ->set('name', 'Updated Server')
        ->call('save')
        ->assertHasNoErrors();

    expect($server->fresh()->name)->toBe('Updated Server');
});

test('switches between password and key authentication', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ServerForm::class)
        ->set('auth_type', 'password')
        ->assertSee('Password')
        ->set('auth_type', 'key')
        ->assertSee('Private Key');
});
