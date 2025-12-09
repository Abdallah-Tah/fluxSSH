<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\Terminal\TerminalSessionManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RealtimeTerminalController extends Controller
{
    /**
     * Display the real-time terminal view
     */
    public function show(Server $server)
    {
        return view('terminal-realtime', compact('server'));
    }

    /**
     * Create a new terminal session and return session ID
     */
    public function connect(Request $request, Server $server)
    {
        $sessionId = 'term_'.Str::uuid();
        $cols = $request->input('cols', 120);
        $rows = $request->input('rows', 40);

        $result = TerminalSessionManager::create($sessionId, $server, $cols, $rows);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'server' => [
                    'name' => $server->name,
                    'host' => $server->host,
                    'username' => $server->username,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'] ?? 'Connection failed',
        ], 500);
    }

    /**
     * Stream terminal output using Server-Sent Events (SSE)
     * This provides real-time output without WebSocket complexity
     */
    public function stream(Request $request, Server $server)
    {
        $sessionId = $request->input('session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'No session ID'], 400);
        }

        return new StreamedResponse(function () use ($sessionId, $server) {
            // Set headers for SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            // Create SSH connection for this stream
            $ssh = new SSH2($server->host, $server->port, 30);
            $ssh->setTimeout(0);

            // Authenticate
            if ($server->auth_type === 'key') {
                $key = PublicKeyLoader::load($server->private_key);
                $authenticated = $ssh->login($server->username, $key);
            } else {
                $authenticated = $ssh->login($server->username, $server->password);
            }

            if (! $authenticated) {
                echo 'data: '.json_encode(['type' => 'error', 'message' => 'Authentication failed'])."\n\n";
                ob_flush();
                flush();

                return;
            }

            // Enable PTY with proper terminal settings
            $cols = 120;
            $rows = 40;
            $ssh->enablePTY();
            $ssh->setTerminal('xterm-256color');
            $ssh->setWindowSize($cols, $rows);

            // Use a callback-based exec that processes output in chunks
            // This runs the command and we can write/read interactively
            $outputBuffer = '';
            $shellStarted = false;

            // Start bash in a background thread using exec with callback
            $ssh->exec('bash -i', function ($str) use (&$outputBuffer, &$shellStarted) {
                $outputBuffer .= $str;
                $shellStarted = true;

                return $str; // Return the string to continue execution
            });

            // Wait briefly for shell to start
            $waitStart = microtime(true);
            while (! $shellStarted && microtime(true) - $waitStart < 2) {
                usleep(50000); // 50ms
            }

            // Now that shell is started via callback, configure it
            if ($shellStarted) {
                $ssh->write("export TERM=xterm-256color\n");
                $ssh->write("stty cols {$cols} rows {$rows} 2>/dev/null || true\n");
                $ssh->write("PS1='\\[\\033[1;32m\\]\\u@\\h\\[\\033[0m\\]:\\[\\033[1;34m\\]\\w\\[\\033[0m\\]\\$ '\n");
                $ssh->write("clear\n");

                usleep(300000); // 300ms for commands to execute

                // Send connected message
                echo 'data: '.json_encode(['type' => 'connected', 'session_id' => $sessionId])."\n\n";
                ob_flush();
                flush();

                // Send any buffered output
                if ($outputBuffer) {
                    echo 'data: '.json_encode(['type' => 'output', 'data' => base64_encode($outputBuffer)])."\n\n";
                    ob_flush();
                    flush();
                    $outputBuffer = '';
                }
            } else {
                echo 'data: '.json_encode(['type' => 'error', 'message' => 'Failed to start shell'])."\n\n";
                ob_flush();
                flush();

                return;
            }

            // Store SSH connection reference in cache for input handling
            $cacheKey = "terminal_ssh_{$sessionId}";

            $lastActivity = time();
            $timeout = 300; // 5 minutes of inactivity

            while (true) {
                // Check for disconnect
                if (connection_aborted()) {
                    break;
                }

                // Check for input from cache
                $inputKey = "terminal_input_{$sessionId}";
                $input = cache()->pull($inputKey);

                if ($input !== null) {
                    $ssh->write($input);
                    $lastActivity = time();
                }

                // Read output
                $output = @$ssh->read('', SSH2::READ_SIMPLE);

                if ($output !== false && $output !== '') {
                    echo 'data: '.json_encode(['type' => 'output', 'data' => base64_encode($output)])."\n\n";
                    ob_flush();
                    flush();
                    $lastActivity = time();
                }

                // Check for resize
                $resizeKey = "terminal_resize_{$sessionId}";
                $resize = cache()->pull($resizeKey);
                if ($resize) {
                    $ssh->setWindowSize($resize['cols'], $resize['rows']);
                }

                // Timeout check
                if ((time() - $lastActivity) > $timeout) {
                    echo 'data: '.json_encode(['type' => 'timeout'])."\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                usleep(10000); // 10ms delay
            }

            $ssh->disconnect();
            cache()->forget($cacheKey);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Send input to terminal via cache (for SSE mode)
     */
    public function input(Request $request, Server $server)
    {
        $sessionId = $request->input('session_id');
        $input = $request->input('input', '');

        if (! $sessionId) {
            return response()->json(['error' => 'No session ID'], 400);
        }

        // Queue input for the stream to pick up
        $inputKey = "terminal_input_{$sessionId}";
        $current = cache()->get($inputKey, '');
        cache()->put($inputKey, $current.$input, now()->addMinutes(5));

        return response()->json(['success' => true]);
    }

    /**
     * Resize terminal
     */
    public function resize(Request $request, Server $server)
    {
        $sessionId = $request->input('session_id');
        $cols = $request->input('cols', 120);
        $rows = $request->input('rows', 40);

        if (! $sessionId) {
            return response()->json(['error' => 'No session ID'], 400);
        }

        $resizeKey = "terminal_resize_{$sessionId}";
        cache()->put($resizeKey, ['cols' => $cols, 'rows' => $rows], now()->addMinutes(5));

        return response()->json(['success' => true]);
    }

    /**
     * Disconnect terminal session
     */
    public function disconnect(Request $request, Server $server)
    {
        $sessionId = $request->input('session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'No session ID'], 400);
        }

        // Clean up cache
        cache()->forget("terminal_ssh_{$sessionId}");
        cache()->forget("terminal_input_{$sessionId}");
        cache()->forget("terminal_resize_{$sessionId}");

        return response()->json(['success' => true]);
    }
}
