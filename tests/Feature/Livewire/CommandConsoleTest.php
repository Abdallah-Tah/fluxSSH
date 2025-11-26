<?php

use App\Livewire\CommandConsole;
use App\Models\Server;
use App\Models\User;
use App\Services\SSH\SSHManager;
use App\Services\SSHService;
use Livewire\Livewire;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

use function PHPUnit\Framework\assertFileExists;

it('surfaces ssh error output in the console', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'auth_type' => 'password',
    ]);

    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function testConnection(Server $server): array
        {
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => 'Connection successful',
            ];
        }

        public function getServerInfo(Server $server): array
        {
            return [
                'success' => true,
                'system_info' => 'TestOS',
                'current_directory' => '/root',
            ];
        }

        public function executeCommand(Server $server, string $command): array
        {
            return [
                'success' => false,
                'command' => $command,
                'output' => 'Permission denied (publickey,password).',
                'error' => 'Permission denied (publickey,password).',
                'exit_code' => 255,
                'timestamp' => now(),
            ];
        }
    });

    Livewire::actingAs($user)
        ->test(CommandConsole::class, ['server' => $server])
        ->set('command', 'ls')
        ->call('executeCommand')
        ->assertSee('Permission denied (publickey,password).');
});

it('returns error details when ssh command exits with a failure code', function () {
    $server = Server::factory()->create([
        'auth_type' => 'password',
        'password' => 'secret',
        'connection_options' => [],
    ]);

    $fakeProcess = \Mockery::mock(Process::class);
    $fakeProcess->shouldReceive('getOutput')->andReturn('');
    $fakeProcess->shouldReceive('getErrorOutput')->andReturn('Permission denied (publickey,password).');
    $fakeProcess->shouldReceive('getExitCode')->andReturn(255);

    $sshConnection = \Mockery::mock(Ssh::class)->makePartial();

    $sshConnection->shouldReceive('usePassword')
        ->zeroOrMoreTimes()
        ->andReturnSelf();

    $sshConnection->shouldReceive('disableStrictHostKeyChecking')
        ->zeroOrMoreTimes()
        ->andReturnSelf();

    $sshConnection->shouldReceive('configureProcess')
        ->zeroOrMoreTimes()
        ->andReturnSelf();

    $sshConnection->shouldReceive('execute')
        ->once()
        ->andReturn($fakeProcess);

    $service = new class($sshConnection) extends SSHService
    {
        public function __construct(private Ssh $connection) {}

        protected function createSshConnection(Server $server): Ssh
        {
            return $this->connection;
        }
    };

    app()->instance(SSHService::class, $service);

    $result = $service->executeCommand($server, 'ls');

    expect($result['success'])->toBeFalse();
    expect($result['error'])->toBe('Permission denied (publickey,password).');
    expect($result['exit_code'])->toBe(255);
});

afterEach(function () {
    \Mockery::close();
});

it('writes private key contents to a usable temp file', function () {
    $server = Server::factory()->create([
        'auth_type' => 'key',
        'private_key' => <<<'KEY'
-----BEGIN OPENSSH PRIVATE KEY-----
dummy-key
-----END OPENSSH PRIVATE KEY-----
KEY,
    ]);

    $service = new class extends SSHService
    {
        public function __construct() {}

        public function publicEnsurePrivateKeyFile(Server $server): string
        {
            return $this->ensurePrivateKeyFile($server);
        }
    };

    $path = $service->publicEnsurePrivateKeyFile($server);

    assertFileExists($path);
    expect(file_get_contents($path))->toStartWith('-----BEGIN OPENSSH PRIVATE KEY-----');
});

it('saves command to history when executed', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'auth_type' => 'password',
    ]);

    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function testConnection(Server $server): array
        {
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => 'Connection successful',
            ];
        }

        public function getServerInfo(Server $server): array
        {
            return [
                'success' => true,
                'system_info' => 'TestOS',
                'current_directory' => '/root',
            ];
        }

        public function executeCommand(Server $server, string $command): array
        {
            return [
                'success' => true,
                'command' => $command,
                'output' => 'Command output',
                'error' => '',
                'exit_code' => 0,
                'timestamp' => now(),
            ];
        }
    });

    Livewire::actingAs($user)
        ->test(CommandConsole::class, ['server' => $server])
        ->set('command', 'ls -la')
        ->call('executeCommand');

    expect(\App\Models\CommandHistory::query()->where('user_id', $user->id)->where('server_id', $server->id)->exists())->toBeTrue();
    expect(\App\Models\CommandHistory::query()->where('command', 'ls -la')->first())->not->toBeNull();
});

it('loads command history on mount', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'auth_type' => 'password',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'ls -la',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'pwd',
    ]);

    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function testConnection(Server $server): array
        {
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => 'Connection successful',
            ];
        }

        public function getServerInfo(Server $server): array
        {
            return [
                'success' => true,
                'system_info' => 'TestOS',
                'current_directory' => '/root',
            ];
        }
    });

    $component = Livewire::actingAs($user)
        ->test(CommandConsole::class, ['server' => $server]);

    expect($component->get('history'))->toBeArray();
    expect($component->get('history'))->toContain('ls -la');
    expect($component->get('history'))->toContain('pwd');
});

it('navigates command history with up arrow', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'auth_type' => 'password',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'ls -la',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'pwd',
    ]);

    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function testConnection(Server $server): array
        {
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => 'Connection successful',
            ];
        }

        public function getServerInfo(Server $server): array
        {
            return [
                'success' => true,
                'system_info' => 'TestOS',
                'current_directory' => '/root',
            ];
        }
    });

    $component = Livewire::actingAs($user)
        ->test(CommandConsole::class, ['server' => $server])
        ->call('navigateHistory', 'up')
        ->assertSet('command', 'pwd')
        ->call('navigateHistory', 'up')
        ->assertSet('command', 'ls -la');
});

it('navigates command history with down arrow', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'auth_type' => 'password',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'ls -la',
    ]);

    \App\Models\CommandHistory::factory()->create([
        'user_id' => $user->id,
        'server_id' => $server->id,
        'command' => 'pwd',
    ]);

    app()->bind(SSHManager::class, fn () => new class extends SSHManager
    {
        public function testConnection(Server $server): array
        {
            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => 'Connection successful',
            ];
        }

        public function getServerInfo(Server $server): array
        {
            return [
                'success' => true,
                'system_info' => 'TestOS',
                'current_directory' => '/root',
            ];
        }
    });

    $component = Livewire::actingAs($user)
        ->test(CommandConsole::class, ['server' => $server])
        ->set('command', 'test input')
        ->call('navigateHistory', 'up')
        ->assertSet('command', 'pwd')
        ->call('navigateHistory', 'up')
        ->assertSet('command', 'ls -la')
        ->call('navigateHistory', 'down')
        ->assertSet('command', 'pwd')
        ->call('navigateHistory', 'down')
        ->assertSet('command', 'test input');
});
