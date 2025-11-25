<?php

use App\Livewire\CommandConsole;
use App\Models\Server;
use App\Models\User;
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

    app()->bind(SSHService::class, fn () => new class extends SSHService
    {
        public function __construct() {}

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
