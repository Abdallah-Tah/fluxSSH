<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

class WebSocketTerminalController extends Controller
{
    /**
     * Connect to SSH and return session info
     */
    public function connect(Request $request, Server $server)
    {
        try {
            $ssh = new SSH2($server->host, $server->port, 10);

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

            // Store SSH connection in cache for this session
            $sessionId = uniqid('ssh_', true);

            // Update last connected
            $server->update(['last_connected_at' => now()]);

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'server' => [
                    'name' => $server->name,
                    'host' => $server->host,
                    'username' => $server->username,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('SSH Connection failed', [
                'server_id' => $server->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Execute command with PTY for full interactive support
     * This creates a proper terminal environment
     */
    public function executeWithPty(Request $request, Server $server)
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
            $ssh->setTimeout(3); // 3 second timeout for reads

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

            // For htop specifically, use batch mode via exec (no PTY needed)
            if (preg_match('/^htop/', $command)) {
                $output = $ssh->exec('TERM=xterm-256color htop -C -d 5 -n 1 2>/dev/null || top -b -n 1 | head -40');
                $ssh->disconnect();
                return response()->json([
                    'success' => true,
                    'output' => $output,
                ]);
            }

            // For top, use batch mode
            if (preg_match('/^top/', $command)) {
                $output = $ssh->exec('top -b -n 1 | head -40');
                $ssh->disconnect();
                return response()->json([
                    'success' => true,
                    'output' => $output,
                ]);
            }

            // For other interactive commands, return helpful message
            $interactiveCommands = ['vim', 'vi', 'nano', 'less', 'more', 'man', 'watch'];
            foreach ($interactiveCommands as $cmd) {
                if (preg_match('/^' . preg_quote($cmd, '/') . '(\s|$)/', $command)) {
                    $ssh->disconnect();
                    return response()->json([
                        'success' => false,
                        'error' => "Interactive command '{$cmd}' requires a WebSocket terminal. Use Ctrl+C to cancel.",
                    ]);
                }
            }

            // Regular PTY execution for other commands
            $ssh->enablePTY();
            $ssh->setWindowSize($cols, $rows);

            // Execute and capture output
            $output = $ssh->exec("export TERM=xterm-256color && {$command}");

            $ssh->disconnect();

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error('SSH PTY Execute failed', [
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
     * Check if command is interactive
     */
    private function isInteractiveCommand(string $command): bool
    {
        $interactiveCommands = ['htop', 'top', 'vim', 'vi', 'nano', 'less', 'more', 'man', 'watch'];
        foreach ($interactiveCommands as $cmd) {
            if (preg_match('/^' . preg_quote($cmd, '/') . '(\s|$)/', $command)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Clean PTY output - remove command echo and prompts
     */
    private function cleanPtyOutput(string $output, string $command): string
    {
        $lines = explode("\n", $output);
        $result = [];
        $commandFound = false;

        foreach ($lines as $line) {
            // Skip lines containing the original command echo
            if (!$commandFound && strpos($line, $command) !== false) {
                $commandFound = true;
                continue;
            }

            // Skip empty prompts at the end
            if (preg_match('/^[\w@\-]+[:\~\/\w]*[$#]\s*$/', trim($line))) {
                continue;
            }

            $result[] = $line;
        }

        return implode("\n", $result);
    }

    /**
     * Execute command via AJAX (for simple command execution)
     */
    public function execute(Request $request, Server $server)
    {
        try {
            $command = $request->input('command', '');

            if (empty($command)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No command provided'
                ], 400);
            }

            $ssh = new SSH2($server->host, $server->port, 30);

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

            // Check if command needs PTY (interactive commands)
            $interactiveCommands = ['htop', 'top', 'vim', 'vi', 'nano', 'less', 'more', 'man'];
            $needsPty = false;
            foreach ($interactiveCommands as $cmd) {
                if (preg_match('/^' . $cmd . '(\s|$)/', $command)) {
                    $needsPty = true;
                    break;
                }
            }

            if ($needsPty) {
                // For htop, run in batch mode with colors
                if (preg_match('/^htop/', $command)) {
                    $command = 'TERM=xterm-256color htop -C -d 10 -n 1 2>/dev/null || top -b -n 1 | head -30';
                } elseif (preg_match('/^top/', $command)) {
                    $command = 'top -b -n 1 | head -30';
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => 'Interactive commands like vim/nano are not supported in this terminal. Use the console at /console/' . $server->id . ' for full interactivity.',
                    ]);
                }
            }

            // Execute command
            $output = $ssh->exec($command);

            $ssh->disconnect();

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error('SSH Execute failed', [
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
     * Interactive shell session
     */
    public function shell(Request $request, Server $server)
    {
        $request->validate([
            'input' => 'nullable|string',
            'cols' => 'nullable|integer',
            'rows' => 'nullable|integer',
        ]);

        try {
            $ssh = new SSH2($server->host, $server->port, 30);

            // Authenticate
            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (!$authenticated) {
                throw new \Exception('Authentication failed');
            }

            $cols = $request->input('cols', 80);
            $rows = $request->input('rows', 24);
            $input = $request->input('input', '');

            // Enable PTY for interactive shell
            $ssh->enablePTY();
            $ssh->setTerminal('xterm-256color');
            $ssh->setWindowSize($cols, $rows);

            // Execute command if provided
            if (!empty($input)) {
                // For single commands, use exec
                $output = $ssh->exec($input);
            } else {
                // Just get initial shell prompt
                $ssh->exec('echo "Ready"');
                $output = '';
            }

            $ssh->disconnect();

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disconnect shell session
     */
    public function disconnect(Request $request, Server $server)
    {
        return response()->json(['success' => true]);
    }
}
