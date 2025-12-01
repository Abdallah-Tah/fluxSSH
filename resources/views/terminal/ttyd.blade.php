<x-layouts.terminal :title="'Terminal - ' . $server->name">
    <div class="min-h-screen">
        <div class="container mx-auto p-4">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Professional Terminal</h1>
                    <p class="text-sm text-slate-400">{{ $server->name }} ({{ $server->username }}@{{ $server - > host }})
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
                            <span class="ml-2 text-sm font-medium text-slate-300">SSH Terminal (ttyd)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span id="connection-status" class="text-xs text-slate-400">Connecting...</span>
                        </div>
                    </div>
                </div>

                <!-- ttyd Terminal (loaded via iframe) -->
                <iframe id="terminal-frame" class="w-full border-0" style="height: 600px;"></iframe>
            </div>

            <div class="mt-4 rounded-lg border border-slate-700 bg-slate-900/50 p-4">
                <h2 class="mb-2 text-sm font-semibold text-white">Features:</h2>
                <ul class="space-y-1 text-xs text-slate-400">
                    <li>✅ Professional terminal using ttyd (same technology cloud providers use)</li>
                    <li>✅ Full interactive support - htop, vim, nano, etc. work perfectly</li>
                    <li>✅ Proper terminal emulation with xterm.js</li>
                    <li>✅ WebSocket-based for low latency</li>
                </ul>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const statusElement = document.getElementById('connection-status');
            const terminalFrame = document.getElementById('terminal-frame');

            function updateStatus(status, color = 'slate') {
                statusElement.textContent = status;
                statusElement.className = 'text-xs text-' + color + '-400';
            }

            function showError(message) {
                updateStatus('Connection failed', 'red');
                terminalFrame.srcdoc = '<!DOCTYPE html>' +
                    '<html><head><style>' +
                    'body { background: #000; color: #ff6b6b; font-family: monospace; padding: 20px; margin: 0; }' +
                    '.error { padding: 20px; border: 1px solid #ff6b6b; border-radius: 4px; }' +
                    'p { color: #888; margin-top: 10px; }' +
                    '</style></head><body><div class="error">' +
                    '<h2>✗ Connection Failed</h2>' +
                    '<p style="color: #ff6b6b;">' + message + '</p>' +
                    '<p>Please try refreshing the page.</p>' +
                    '</div></body></html>';
            }

            async function startTerminal() {
                try {
                    updateStatus('Starting terminal...', 'yellow');

                    // Start ttyd session
                    const response = await fetch('{{ route('terminal.ttyd.start', $server) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error('Server error: ' + response.status);
                    }

                    const data = await response.json();

                    if (!data.success) {
                        throw new Error(data.error || 'Failed to start terminal');
                    }

                    console.log('Terminal session started:', data);

                    // Load ttyd in iframe
                    const port = data.port;
                    terminalFrame.src = 'http://localhost:' + port;

                    updateStatus('Connected', 'green');

                    // Store session ID for cleanup
                    window.terminalSessionId = data.session_id;

                } catch (error) {
                    console.error('Failed to start terminal:', error);
                    showError(error.message);
                }
            }

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                if (window.terminalSessionId) {
                    navigator.sendBeacon(
                        '{{ url('ttyd/stop') }}/' + window.terminalSessionId,
                        JSON.stringify({
                            _token: '{{ csrf_token() }}'
                        })
                    );
                }
            });

            // Start terminal when page loads
            startTerminal();
        </script>
    @endpush
</x-layouts.terminal>
