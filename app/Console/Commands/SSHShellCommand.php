<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class SSHShellCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssh:shell {server? : The server ID or name to connect to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Launch the FluxSSH interactive shell';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $serverInput = $this->argument('server');
        $serverId = null;

        if ($serverInput) {
            // Try to find server by ID first, then by name
            $server = Server::where('id', $serverInput)
                ->orWhere('name', $serverInput)
                ->first();

            if (!$server) {
                $this->error("Server '{$serverInput}' not found.");
                return 1;
            }

            $serverId = $server->id;
        }

        $shellPath = base_path('whisp/apps/ssh-shell.php');

        if (!file_exists($shellPath)) {
            $this->error('SSH shell app not found at: ' . $shellPath);
            return 1;
        }

        $this->info('Launching FluxSSH interactive shell...');
        $this->newLine();

        // Execute the shell app
        $command = "php {$shellPath}";
        if ($serverId) {
            $command .= " {$serverId}";
        }

        // Use passthru to allow interactive input/output
        passthru($command, $returnCode);

        return $returnCode;
    }
}
