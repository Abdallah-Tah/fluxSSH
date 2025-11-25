<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class UpdateServerPassword extends Command
{
    protected $signature = 'server:password {server_id} {password}';

    protected $description = 'Update the password for a server';

    public function handle(): int
    {
        $serverId = $this->argument('server_id');
        $password = $this->argument('password');

        $server = Server::find($serverId);

        if (! $server) {
            $this->error("Server with ID {$serverId} not found.");

            return self::FAILURE;
        }

        $server->password = $password;
        $server->save();

        $this->info("Password updated successfully for server: {$server->name}");
        $this->info('The password has been encrypted and stored securely.');

        return self::SUCCESS;
    }
}
