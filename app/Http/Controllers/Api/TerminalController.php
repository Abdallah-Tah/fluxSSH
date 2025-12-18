<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\SSHService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TerminalController extends Controller
{
    public function __construct(
        protected SSHService $sshService
    ) {}

    /**
     * Get server list for authenticated user
     */
    public function servers()
    {
        return response()->json([
            'servers' => Auth::user()->servers()->get(['id', 'name', 'host', 'port', 'username']),
        ]);
    }

    /**
     * Create a new terminal session
     */
    public function createSession(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
        ]);

        $server = Server::findOrFail($validated['server_id']);

        // Check user owns this server
        if ($server->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Generate session token
        $sessionToken = bin2hex(random_bytes(32));

        // Store session in cache with 1 hour expiry
        cache()->put("terminal_session:{$sessionToken}", [
            'user_id' => Auth::id(),
            'server_id' => $server->id,
            'created_at' => now(),
        ], 3600);

        return response()->json([
            'session_token' => $sessionToken,
            'websocket_url' => config('app.websocket_url')."/terminal/{$sessionToken}",
            'server' => [
                'id' => $server->id,
                'name' => $server->name,
                'host' => $server->host,
            ],
        ]);
    }

    /**
     * Execute a single SSH command (for non-interactive operations)
     */
    public function executeCommand(Request $request)
    {
        $validated = $request->validate([
            'server_id' => 'required|exists:servers,id',
            'command' => 'required|string',
        ]);

        $server = Server::findOrFail($validated['server_id']);

        if ($server->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $output = $this->sshService->executeCommand(
                $server,
                $validated['command']
            );

            return response()->json([
                'success' => true,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get connection status
     */
    public function connectionStatus(Request $request, $serverId)
    {
        $server = Server::findOrFail($serverId);

        if ($server->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $connected = $this->sshService->testConnection($server);

            return response()->json([
                'connected' => $connected,
                'server' => $server->only(['id', 'name', 'host', 'port']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'error' => $e->getMessage(),
            ], 200); // Still return 200 but with connected: false
        }
    }
}
