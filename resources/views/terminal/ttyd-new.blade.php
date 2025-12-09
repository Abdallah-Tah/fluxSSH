<x-layouts.terminal :title="'Terminal - ' . $server->name">
    <!-- Include xterm.js CSS -->
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/css/xterm.css" />
    @endpush

    <div class="min-h-screen flex flex-col">
        <div class="container mx-auto p-4 flex-1 flex flex-col">
            <!-- Header -->
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Terminal</h1>
                    <p class="text-sm text-slate-400">{{ $server->name }}
                        ({{ $server->username }}@{{ $server - > host }}:{{ $server->port }})</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="connection-status" class="flex items-center gap-2 text-sm">
                        <span class="status-dot h-2 w-2 rounded-full bg-yellow-500 animate-pulse"></span>
                        <span class="status-text text-slate-400">Connecting...</span>
                    </span>
                    <a href="{{ route('servers.show', $server) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                        ← Back to Server
                    </a>
                </div>
            </div>

            <!-- Terminal Container -->
            <div
                class="flex-1 overflow-hidden rounded-lg border border-slate-700 bg-[#1e1e1e] shadow-2xl flex flex-col">
                <!-- Terminal Title Bar -->
                <div class="border-b border-slate-700 bg-slate-900 px-4 py-2 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-red-500 cursor-pointer hover:brightness-110"
                                title="Close"></div>
                            <div class="h-3 w-3 rounded-full bg-yellow-500 cursor-pointer hover:brightness-110"
                                title="Minimize"></div>
                            <div class="h-3 w-3 rounded-full bg-green-500 cursor-pointer hover:brightness-110"
                                title="Maximize"></div>
                            <span id="terminal-title" class="ml-3 text-sm font-medium text-slate-300">SSH
                                Terminal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="btn-reconnect"
                                class="hidden px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
                                Reconnect
                            </button>
                            <button id="btn-fullscreen"
                                class="px-3 py-1 text-xs bg-slate-700 hover:bg-slate-600 text-slate-300 rounded transition-colors"
                                title="Toggle Fullscreen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Terminal Area -->
                <div id="terminal-container" class="flex-1 p-1" style="min-height: 500px;"></div>
            </div>

            <!-- Info Footer -->
            <div class="mt-4 rounded-lg border border-slate-700 bg-slate-900/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-white mb-1">Tips:</h2>
                        <ul class="flex gap-4 text-xs text-slate-400">
                            <li>• Ctrl+C to interrupt</li>
                            <li>• Ctrl+L to clear</li>
                            <li>• Tab for autocomplete</li>
                            <li>• ↑/↓ for history</li>
                        </ul>
                    </div>
                    <div class="text-xs text-slate-500">
                        Port: <span id="port-info">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import {
                Terminal
            } from 'https://cdn.jsdelivr.net/npm/@xterm/xterm@5.5.0/+esm';
            import {
                FitAddon
            } from 'https://cdn.jsdelivr.net/npm/@xterm/addon-fit@0.10.0/+esm';
            import {
                WebLinksAddon
            } from 'https://cdn.jsdelivr.net/npm/@xterm/addon-web-links@0.11.0/+esm';

            // DOM elements
            const terminalContainer = document.getElementById('terminal-container');
            const statusElement = document.getElementById('connection-status');
            const terminalTitle = document.getElementById('terminal-title');
            const portInfo = document.getElementById('port-info');
            const btnReconnect = document.getElementById('btn-reconnect');
            const btnFullscreen = document.getElementById('btn-fullscreen');

            let terminal = null;
            let fitAddon = null;
            let ws = null;
            let currentPort = null;
            let sessionId = null;

            // Update connection status
            function updateStatus(status, text) {
                const dot = statusElement.querySelector('.status-dot');
                const textEl = statusElement.querySelector('.status-text');

                textEl.textContent = text;
                dot.classList.remove('bg-yellow-500', 'bg-green-500', 'bg-red-500', 'animate-pulse');

                switch (status) {
                    case 'connecting':
                        dot.classList.add('bg-yellow-500', 'animate-pulse');
                        break;
                    case 'connected':
                        dot.classList.add('bg-green-500');
                        break;
                    case 'disconnected':
                    case 'error':
                        dot.classList.add('bg-red-500');
                        btnReconnect.classList.remove('hidden');
                        break;
                }
            }

            // Initialize terminal
            function initTerminal() {
                terminal = new Terminal({
                    cursorBlink: true,
                    cursorStyle: 'block',
                    fontFamily: '"Cascadia Code", "Fira Code", "SF Mono", Menlo, Monaco, "Courier New", monospace',
                    fontSize: 14,
                    lineHeight: 1.2,
                    theme: {
                        background: '#1e1e1e',
                        foreground: '#d4d4d4',
                        cursor: '#d4d4d4',
                        cursorAccent: '#1e1e1e',
                        selectionBackground: 'rgba(255, 255, 255, 0.3)',
                        black: '#000000',
                        red: '#cd3131',
                        green: '#0dbc79',
                        yellow: '#e5e510',
                        blue: '#2472c8',
                        magenta: '#bc3fbc',
                        cyan: '#11a8cd',
                        white: '#e5e5e5',
                        brightBlack: '#666666',
                        brightRed: '#f14c4c',
                        brightGreen: '#23d18b',
                        brightYellow: '#f5f543',
                        brightBlue: '#3b8eea',
                        brightMagenta: '#d670d6',
                        brightCyan: '#29b8db',
                        brightWhite: '#e5e5e5'
                    },
                    scrollback: 10000,
                    allowProposedApi: true,
                });

                fitAddon = new FitAddon();
                terminal.loadAddon(fitAddon);
                terminal.loadAddon(new WebLinksAddon());

                terminal.open(terminalContainer);

                // Delay fit to ensure container is ready
                setTimeout(() => {
                    fitAddon.fit();
                }, 100);

                // Handle terminal input - send to WebSocket
                terminal.onData((data) => {
                    if (ws && ws.readyState === WebSocket.OPEN) {
                        const encoder = new TextEncoder();
                        const payload = encoder.encode(data);
                        const buffer = new Uint8Array(1 + payload.length);
                        buffer[0] = 0; // Input command
                        buffer.set(payload, 1);
                        ws.send(buffer);
                    }
                });

                // Handle resize
                terminal.onResize(({
                    cols,
                    rows
                }) => {
                    sendResize(cols, rows);
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    if (fitAddon) {
                        fitAddon.fit();
                    }
                });

                terminal.focus();
            }

            // Send resize command to ttyd
            function sendResize(cols, rows) {
                if (ws && ws.readyState === WebSocket.OPEN) {
                    const data = JSON.stringify({
                        columns: cols,
                        rows: rows
                    });
                    const encoder = new TextEncoder();
                    const payload = encoder.encode(data);
                    const buffer = new Uint8Array(1 + payload.length);
                    buffer[0] = 1; // Resize command
                    buffer.set(payload, 1);
                    ws.send(buffer);
                }
            }

            // Connect to ttyd WebSocket
            function connectWebSocket(port) {
                currentPort = port;
                portInfo.textContent = port;

                const wsUrl = `ws://localhost:${port}/ws`;
                console.log('Connecting to ttyd WebSocket:', wsUrl);

                ws = new WebSocket(wsUrl, ['tty']);
                ws.binaryType = 'arraybuffer';

                ws.onopen = () => {
                    console.log('WebSocket connected');
                    updateStatus('connected', 'Connected');
                    btnReconnect.classList.add('hidden');

                    // Send initial resize after connection
                    setTimeout(() => {
                        if (fitAddon) {
                            fitAddon.fit();
                            sendResize(terminal.cols, terminal.rows);
                        }
                    }, 200);
                };

                ws.onmessage = (event) => {
                    if (event.data instanceof ArrayBuffer) {
                        const view = new Uint8Array(event.data);
                        const cmd = view[0];
                        const payload = view.slice(1);

                        switch (cmd) {
                            case 0: // Output
                                const text = new TextDecoder().decode(payload);
                                terminal.write(text);
                                break;
                            case 1: // Set window title
                                const title = new TextDecoder().decode(payload);
                                terminalTitle.textContent = title || 'SSH Terminal';
                                break;
                            case 2: // Set preferences (ignore)
                                break;
                        }
                    }
                };

                ws.onclose = (event) => {
                    console.log('WebSocket closed:', event.code, event.reason);
                    updateStatus('disconnected', 'Disconnected');
                    terminal.write('\r\n\x1b[31m--- Connection closed ---\x1b[0m\r\n');
                };

                ws.onerror = (error) => {
                    console.error('WebSocket error:', error);
                    updateStatus('error', 'Connection error');
                };
            }

            // Start ttyd session
            async function startSession() {
                try {
                    updateStatus('connecting', 'Starting terminal...');
                    terminal.write('Connecting to server...\r\n');

                    const response = await fetch('{{ route('terminal.ttyd.start', $server) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Server error: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Session started:', data);

                    if (!data.success) {
                        throw new Error(data.error || 'Failed to start terminal');
                    }

                    sessionId = data.session_id;

                    // Small delay to ensure ttyd is ready
                    await new Promise(resolve => setTimeout(resolve, 500));

                    // Connect to the ttyd WebSocket
                    connectWebSocket(data.port);

                } catch (error) {
                    console.error('Failed to start session:', error);
                    updateStatus('error', 'Failed to connect');
                    terminal.write(`\r\n\x1b[31mError: ${error.message}\x1b[0m\r\n`);
                    terminal.write('\x1b[33mClick "Reconnect" to try again.\x1b[0m\r\n');
                }
            }

            // Stop session
            async function stopSession() {
                if (sessionId) {
                    try {
                        await fetch(`{{ url('ttyd/stop') }}/${sessionId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    } catch (e) {
                        console.error('Failed to stop session:', e);
                    }
                }
            }

            // Fullscreen toggle
            btnFullscreen.addEventListener('click', () => {
                const container = document.querySelector('.min-h-screen');
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    container.requestFullscreen();
                }
            });

            // Reconnect button
            btnReconnect.addEventListener('click', () => {
                btnReconnect.classList.add('hidden');
                terminal.clear();
                startSession();
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (ws) {
                    ws.close();
                }
                stopSession();
            });

            // Initialize and connect
            initTerminal();
            startSession();
        </script>
    @endpush
</x-layouts.terminal>
