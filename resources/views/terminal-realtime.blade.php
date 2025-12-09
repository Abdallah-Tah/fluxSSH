<x-layouts.terminal :title="'Terminal - ' . $server->name">
    <div class="min-h-screen">
        <div class="container mx-auto p-4">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Interactive Terminal</h1>
                    <p class="text-sm text-slate-400">{{ $server->name }} ({{ $server->username }}@{{ $server->host }})
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('servers.show', $server) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                        ← Back to Server
                    </a>
                </div>
            </div>

            <!-- Terminal Container -->
            <div class="overflow-hidden rounded-lg border border-slate-700 bg-black shadow-2xl">
                <div class="border-b border-slate-700 bg-slate-900 px-4 py-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-red-500"></div>
                            <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                            <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            <span class="ml-2 text-sm font-medium text-slate-300">SSH Terminal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="connection-status" class="text-xs text-slate-400">Connecting...</span>
                        </div>
                    </div>
                </div>

                <!-- xterm.js Terminal -->
                <div id="terminal" class="p-2" style="height: 600px;"></div>
            </div>

            <div class="mt-4 rounded-lg border border-slate-700 bg-slate-900/50 p-4">
                <h2 class="mb-2 text-sm font-semibold text-white">Tips:</h2>
                <ul class="space-y-1 text-xs text-slate-400">
                    <li>• This is a real interactive terminal - you can run htop, vim, nano, and any other command</li>
                    <li>• Use Ctrl+C to cancel running commands</li>
                    <li>• The terminal will automatically reconnect if disconnected</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/css/xterm.min.css" />
        <script type="module">
            console.log('Terminal script loading...');

            import {
                Terminal
            } from 'https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/+esm';
            import {
                FitAddon
            } from 'https://cdn.jsdelivr.net/npm/@xterm/addon-fit@0.10.0/+esm';
            import {
                WebLinksAddon
            } from 'https://cdn.jsdelivr.net/npm/@xterm/addon-web-links@0.11.0/+esm';

            console.log('Terminal libraries loaded');

            // Initialize xterm.js
            const term = new Terminal({
                cursorBlink: true,
                fontSize: 14,
                fontFamily: 'Menlo, Monaco, "Courier New", monospace',
                theme: {
                    background: '#000000',
                    foreground: '#ffffff',
                    cursor: '#ffffff',
                    cursorAccent: '#000000',
                    selection: 'rgba(255, 255, 255, 0.3)',
                    black: '#000000',
                    red: '#e06c75',
                    green: '#98c379',
                    yellow: '#d19a66',
                    blue: '#61afef',
                    magenta: '#c678dd',
                    cyan: '#56b6c2',
                    white: '#abb2bf',
                    brightBlack: '#5c6370',
                    brightRed: '#e06c75',
                    brightGreen: '#98c379',
                    brightYellow: '#d19a66',
                    brightBlue: '#61afef',
                    brightMagenta: '#c678dd',
                    brightCyan: '#56b6c2',
                    brightWhite: '#ffffff'
                },
                scrollback: 1000,
                allowProposedApi: true
            });

            // Add fit addon for responsive terminal
            const fitAddon = new FitAddon();
            term.loadAddon(fitAddon);

            // Add web links addon
            term.loadAddon(new WebLinksAddon());

            // Open terminal
            const terminalElement = document.getElementById('terminal');
            term.open(terminalElement);
            fitAddon.fit();

            // Handle window resize
            window.addEventListener('resize', () => {
                fitAddon.fit();
                if (sessionId && isConnected) {
                    sendResize();
                }
            });

            let sessionId = null;
            let isConnected = false;
            let eventSource = null;

            const statusElement = document.getElementById('connection-status');

            function updateStatus(status, color = 'slate') {
                statusElement.textContent = status;
                statusElement.className = `text-xs text-${color}-400`;
            }

            // Connect to server
            async function connect() {
                try {
                    console.log('Starting connection...');
                    updateStatus('Connecting...', 'yellow');

                    const url = '{{ route('terminal.connect', $server) }}';
                    console.log('Connecting to:', url);

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            cols: term.cols,
                            rows: term.rows
                        })
                    });

                    const data = await response.json();
                    console.log('Connection response:', data);

                    if (!data.success) {
                        throw new Error(data.error || 'Connection failed');
                    }

                    sessionId = data.session_id;
                    console.log('Session ID:', sessionId);
                    updateStatus('Connected', 'green');
                    isConnected = true;

                    term.writeln('\r\n\x1b[1;32m✓ Connected to {{ $server->name }}\x1b[0m');
                    term.writeln('\x1b[90mSession ID: ' + sessionId + '\x1b[0m\r\n');

                    // Start SSE stream
                    startStream();

                } catch (error) {
                    console.error('Connection error:', error);
                    updateStatus('Connection failed', 'red');
                    term.writeln('\r\n\x1b[1;31m✗ Connection failed: ' + error.message + '\x1b[0m\r\n');

                    // Show more details in terminal
                    if (error.stack) {
                        console.error('Error stack:', error.stack);
                    }

                    // Retry after 3 seconds
                    setTimeout(() => {
                        term.writeln('\x1b[90mRetrying connection...\x1b[0m\r\n');
                        connect();
                    }, 3000);
                }
            }

            // Start Server-Sent Events stream
            function startStream() {
                const url = new URL('{{ route('terminal.stream', $server) }}', window.location.origin);
                url.searchParams.append('session_id', sessionId);

                eventSource = new EventSource(url);

                eventSource.addEventListener('message', (event) => {
                    const data = JSON.parse(event.data);

                    if (data.type === 'connected') {
                        updateStatus('Connected', 'green');
                    } else if (data.type === 'output') {
                        // Decode base64 output
                        const output = atob(data.data);
                        term.write(output);
                    } else if (data.type === 'error') {
                        term.writeln('\r\n\x1b[1;31m✗ Error: ' + data.message + '\x1b[0m\r\n');
                        updateStatus('Error', 'red');
                    } else if (data.type === 'timeout') {
                        handleDisconnect('Session timed out due to inactivity');
                    }
                });

                eventSource.addEventListener('error', (event) => {
                    if (eventSource.readyState === EventSource.CLOSED) {
                        handleDisconnect('Connection closed');
                    } else {
                        console.error('SSE error:', event);
                    }
                });
            }

            // Handle user input from terminal
            term.onData((data) => {
                if (!isConnected || !sessionId) return;

                fetch('{{ route('terminal.input', $server) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        input: data
                    })
                }).catch(error => {
                    console.error('Failed to send input:', error);
                });
            });

            // Send terminal resize
            function sendResize() {
                fetch('{{ route('terminal.resize', $server) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        cols: term.cols,
                        rows: term.rows
                    })
                }).catch(error => {
                    console.error('Failed to send resize:', error);
                });
            }

            // Handle disconnect
            function handleDisconnect(reason = 'Connection closed') {
                isConnected = false;
                updateStatus('Disconnected', 'red');
                term.writeln('\r\n\x1b[1;31m✗ ' + reason + '\x1b[0m\r\n');

                if (eventSource) {
                    eventSource.close();
                    eventSource = null;
                }

                // Attempt to reconnect
                term.writeln('\x1b[90mAttempting to reconnect in 3 seconds...\x1b[0m\r\n');
                setTimeout(connect, 3000);
            }

            // Clean up on page unload
            window.addEventListener('beforeunload', () => {
                if (sessionId) {
                    navigator.sendBeacon('{{ route('terminal.disconnect', $server) }}',
                        JSON.stringify({
                            session_id: sessionId
                        }));
                }
            });

            // Start connection
            connect();
        </script>
    @endpush
</x-layouts.terminal>
