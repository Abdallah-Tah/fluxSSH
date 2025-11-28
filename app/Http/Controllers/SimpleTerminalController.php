<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

/**
 * Simple polling-based terminal controller
 * Works reliably without WebSocket or SSE complexity
 */
class SimpleTerminalController extends Controller
{
    /**
     * Display the terminal view
     */
    public function show(Server $server)
    {
        return view('terminal-simple', compact('server'));
    }

    /**
     * Execute command and return output immediately
     */
    public function execute(Request $request, Server $server)
    {
        try {
            $command = $request->input('command', '');
            $cols = $request->input('cols', 120);
            $rows = $request->input('rows', 40);

            if (empty($command)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No command provided'
                ], 400);
            }

            $ssh = new SSH2($server->host, $server->port, 10);
            $ssh->setTimeout(30);

            // Authenticate
            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (!$authenticated) {
                return response()->json([
                    'success' => false,
                    'error' => 'Authentication failed'
                ], 401);
            }

            // Check if it's an interactive command
            $interactiveCommands = ['htop', 'top', 'vim', 'vi', 'nano', 'less', 'more', 'man', 'watch'];
            $baseCmd = explode(' ', trim($command))[0];

            if (in_array($baseCmd, $interactiveCommands)) {
                // Run interactive commands with PTY snapshot
                return $this->executeInteractive($ssh, $command, $baseCmd, $cols, $rows);
            }

            // Regular command - just exec
            $output = $ssh->exec("export TERM=xterm-256color && " . $command);
            $ssh->disconnect();

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error('Terminal execute failed', [
                'server_id' => $server->id,
                'command' => $request->input('command'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle interactive commands with PTY
     */
    private function executeInteractive(SSH2 $ssh, string $command, string $baseCmd, int $cols, int $rows): \Illuminate\Http\JsonResponse
    {
        // For htop/top, use batch mode
        if ($baseCmd === 'htop') {
            $output = $ssh->exec("TERM=xterm-256color COLUMNS={$cols} LINES={$rows} htop -C -d 10 -n 1 2>/dev/null || top -b -n 1 | head -40");
            $ssh->disconnect();
            return response()->json(['success' => true, 'output' => $output]);
        }

        if ($baseCmd === 'top') {
            $output = $ssh->exec("TERM=xterm-256color top -b -n 1 | head -50");
            $ssh->disconnect();
            return response()->json(['success' => true, 'output' => $output]);
        }

        // For other interactive commands, explain they need a real terminal
        $ssh->disconnect();
        return response()->json([
            'success' => false,
            'error' => "'{$baseCmd}' is an interactive command. Try using: {$baseCmd} in batch mode if available."
        ]);
    }

    /**
     * Get shell prompt info (pwd, user, etc)
     */
    public function info(Request $request, Server $server)
    {
        try {
            $ssh = new SSH2($server->host, $server->port, 10);
            $ssh->setTimeout(5);

            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (!$authenticated) {
                return response()->json(['success' => false, 'error' => 'Auth failed'], 401);
            }

            $pwd = trim($ssh->exec('pwd'));
            $whoami = trim($ssh->exec('whoami'));
            $hostname = trim($ssh->exec('hostname'));

            $ssh->disconnect();

            return response()->json([
                'success' => true,
                'pwd' => $pwd,
                'user' => $whoami,
                'hostname' => $hostname,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
