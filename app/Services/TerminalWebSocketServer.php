<?php

namespace App\Services;

use App\Models\Server;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class TerminalWebSocketServer implements MessageComponentInterface
{
    protected $clients;

    protected $sessions;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->sessions = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        // Extract session token from query string
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $params);

        $sessionToken = $params['token'] ?? null;

        if (! $sessionToken) {
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'No session token provided',
            ]));
            $conn->close();

            return;
        }

        // Validate session
        $session = cache()->get("terminal_session:{$sessionToken}");

        if (! $session) {
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'Invalid or expired session',
            ]));
            $conn->close();

            return;
        }

        // Get server details
        $server = Server::find($session['server_id']);

        if (! $server) {
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'Server not found',
            ]));
            $conn->close();

            return;
        }

        try {
            // Create SSH connection
            $ssh = $this->createSSHConnection($server);

            // Store connection info
            $this->sessions[$conn->resourceId] = [
                'ssh' => $ssh,
                'server' => $server,
                'user_id' => $session['user_id'],
                'shell' => null,
            ];

            // Start shell session
            $shell = $ssh->getShell();
            $this->sessions[$conn->resourceId]['shell'] = $shell;

            // Send success message
            $conn->send(json_encode([
                'type' => 'connected',
                'server' => [
                    'name' => $server->name,
                    'host' => $server->host,
                ],
            ]));

            Log::info('Terminal session started', [
                'connection_id' => $conn->resourceId,
                'server_id' => $server->id,
            ]);
        } catch (\Exception $e) {
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'Failed to connect to server: '.$e->getMessage(),
            ]));
            $conn->close();

            Log::error('Failed to start terminal session', [
                'error' => $e->getMessage(),
                'server_id' => $server->id,
            ]);
        }
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        if (! isset($this->sessions[$from->resourceId])) {
            return;
        }

        $session = $this->sessions[$from->resourceId];
        $data = json_decode($msg, true);

        if (! $data || ! isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'input':
                // Send input to SSH shell
                if (isset($data['data']) && $session['shell']) {
                    try {
                        $session['shell']->write($data['data']);

                        // Read output
                        $output = $session['shell']->read();

                        if ($output) {
                            $from->send(json_encode([
                                'type' => 'output',
                                'data' => $output,
                            ]));
                        }
                    } catch (\Exception $e) {
                        $from->send(json_encode([
                            'type' => 'error',
                            'message' => $e->getMessage(),
                        ]));
                    }
                }
                break;

            case 'resize':
                // Handle terminal resize
                if (isset($data['cols']) && isset($data['rows']) && $session['shell']) {
                    try {
                        $session['shell']->resize($data['cols'], $data['rows']);
                    } catch (\Exception $e) {
                        Log::warning('Failed to resize terminal', [
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
                break;

            case 'ping':
                $from->send(json_encode(['type' => 'pong']));
                break;
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        if (isset($this->sessions[$conn->resourceId])) {
            $session = $this->sessions[$conn->resourceId];

            // Close SSH connection
            if ($session['ssh']) {
                $session['ssh']->disconnect();
            }

            unset($this->sessions[$conn->resourceId]);

            Log::info('Terminal session closed', [
                'connection_id' => $conn->resourceId,
            ]);
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error('WebSocket error', [
            'connection_id' => $conn->resourceId,
            'error' => $e->getMessage(),
        ]);

        $conn->close();
    }

    protected function createSSHConnection(Server $server)
    {
        $ssh = new \phpseclib3\Net\SSH2($server->host, $server->port);

        // Authenticate
        if ($server->auth_type === 'password') {
            if (! $ssh->login($server->username, $server->password)) {
                throw new \Exception('SSH authentication failed');
            }
        } elseif ($server->auth_type === 'key') {
            $key = \phpseclib3\Crypt\PublicKeyLoader::load($server->private_key, $server->key_passphrase);
            if (! $ssh->login($server->username, $key)) {
                throw new \Exception('SSH key authentication failed');
            }
        }

        return $ssh;
    }
}
