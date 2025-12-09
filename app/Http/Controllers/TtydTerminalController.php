<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\Terminal\TtydManager;

class TtydTerminalController extends Controller
{
    public function __construct(
        private TtydManager $ttydManager
    ) {}

    /**
     * Show the ttyd terminal view
     */
    public function show(Server $server)
    {
        // Start ttyd session first
        $result = $this->ttydManager->startSession($server);

        if (!$result['success']) {
            return back()->with('error', $result['error'] ?? 'Failed to start terminal');
        }

        $port = $result['port'];

        // Redirect directly to ttyd (avoids HTTPS/HTTP iframe issues)
        return redirect("http://localhost:{$port}/");
    }

    /**
     * Start a ttyd terminal session
     */
    public function start(Server $server)
    {
        $result = $this->ttydManager->startSession($server);

        return response()->json($result);
    }

    /**
     * Proxy WebSocket connections to ttyd
     */
    public function proxy(string $sessionId)
    {
        $session = $this->ttydManager->getSession($sessionId);

        if (! $session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Return the port for the frontend to connect to
        return response()->json([
            'success' => true,
            'port' => $session['port'],
        ]);
    }

    /**
     * Stop a ttyd terminal session
     */
    public function stop(string $sessionId)
    {
        $stopped = $this->ttydManager->stopSession($sessionId);

        return response()->json(['success' => $stopped]);
    }
}
