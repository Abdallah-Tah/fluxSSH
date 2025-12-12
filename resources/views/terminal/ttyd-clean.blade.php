<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terminal - {{ $server->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
        }
        #terminal-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }
        #loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-family: monospace;
            font-size: 14px;
            text-align: center;
        }
        .error {
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div id="loading">Starting terminal...</div>
    <iframe id="terminal-frame" style="display: none;"></iframe>

    <script>
        async function startTerminal() {
            const loading = document.getElementById('loading');
            const frame = document.getElementById('terminal-frame');

            try {
                // Start ttyd session
                const response = await fetch('{{ route('terminal.ttyd.start', $server) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to start terminal');
                }

                // Connect to ttyd
                const ttydUrl = 'http://localhost:' + data.port;
                frame.src = ttydUrl;

                // Hide loading, show terminal
                loading.style.display = 'none';
                frame.style.display = 'block';

                // Store session ID for cleanup
                window.terminalSessionId = data.session_id;

                console.log('Terminal started:', { port: data.port, sessionId: data.session_id });

            } catch (error) {
                console.error('Failed to start terminal:', error);
                loading.className = 'error';
                loading.innerHTML = '✗ Connection Failed<br><br>' + error.message + '<br><br>Please refresh the page to try again.';
            }
        }

        // Clean up on page unload
        window.addEventListener('beforeunload', function() {
            if (window.terminalSessionId) {
                navigator.sendBeacon(
                    '{{ url('ttyd/stop') }}/' + window.terminalSessionId,
                    JSON.stringify({ _token: '{{ csrf_token() }}' })
                );
            }
        });

        // Start terminal when page loads
        startTerminal();
    </script>
</body>
</html>