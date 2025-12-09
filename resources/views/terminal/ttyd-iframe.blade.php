<x-layouts.terminal :title="'Terminal - ' . $server->name">
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
                        <span class="status-text text-slate-400">Starting...</span>
                    </span>
                    <a href="{{ route('servers.show', $server) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 transition-colors">
                        ← Back to Server
                    </a>
                </div>
            </div>

            <!-- Terminal Container -->
            <div class="flex-1 overflow-hidden rounded-lg border border-slate-700 bg-[#1e1e1e] shadow-2xl flex flex-col"
                style="min-height: 600px;">
                <!-- Terminal Title Bar -->
                <div class="border-b border-slate-700 bg-slate-900 px-4 py-2 shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="h-3 w-3 rounded-full bg-red-500"></div>
                            <div class="h-3 w-3 rounded-full bg-yellow-500"></div>
                            <div class="h-3 w-3 rounded-full bg-green-500"></div>
                            <span class="ml-3 text-sm font-medium text-slate-300">SSH Terminal -
                                {{ $server->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a id="open-direct" href="#" target="_blank"
                                class="px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors hidden">
                                Open in New Tab
                            </a>
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

                <!-- Terminal iframe -->
                <iframe id="terminal-frame" class="flex-1 w-full border-0 bg-black" style="min-height: 550px;"></iframe>

                <!-- Loading overlay -->
                <div id="loading-overlay" class="absolute inset-0 flex items-center justify-center bg-black/80">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto mb-4"></div>
                        <p class="text-slate-300">Starting terminal session...</p>
                        <p class="text-slate-500 text-sm mt-2">Connecting to {{ $server->host }}:{{ $server->port }}</p>
                    </div>
                </div>
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
        <script>
            const statusElement = document.getElementById('connection-status');
            const terminalFrame = document.getElementById('terminal-frame');
            const loadingOverlay = document.getElementById('loading-overlay');
            const portInfo = document.getElementById('port-info');
            const openDirect = document.getElementById('open-direct');
            const btnFullscreen = document.getElementById('btn-fullscreen');

            let sessionId = null;

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
                    case 'error':
                        dot.classList.add('bg-red-500');
                        break;
                }
            }

            async function startSession() {
                try {
                    updateStatus('connecting', 'Starting terminal...');

                    const response = await fetch('{{ route('terminal.ttyd.start', $server) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    console.log('Session response:', data);

                    if (!data.success) {
                        throw new Error(data.error || 'Failed to start terminal');
                    }

                    sessionId = data.session_id;
                    const port = data.port;
                    portInfo.textContent = port;

                    // Set up direct link
                    const directUrl = `http://localhost:${port}`;
                    openDirect.href = directUrl;
                    openDirect.classList.remove('hidden');

                    // Load ttyd in iframe
                    terminalFrame.src = directUrl;

                    // Hide loading overlay when iframe loads
                    terminalFrame.onload = () => {
                        loadingOverlay.style.display = 'none';
                        updateStatus('connected', 'Connected');
                    };

                    // Fallback timeout
                    setTimeout(() => {
                        loadingOverlay.style.display = 'none';
                        updateStatus('connected', 'Connected');
                    }, 3000);

                } catch (error) {
                    console.error('Failed to start session:', error);
                    updateStatus('error', 'Failed to connect');
                    loadingOverlay.innerHTML = `
                        <div class="text-center">
                            <div class="text-red-500 text-4xl mb-4">✗</div>
                            <p class="text-red-400">Failed to start terminal</p>
                            <p class="text-slate-500 text-sm mt-2">${error.message}</p>
                            <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Retry
                            </button>
                        </div>
                    `;
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

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (sessionId) {
                    navigator.sendBeacon(
                        '{{ url('ttyd/stop') }}/' + sessionId,
                        JSON.stringify({
                            _token: '{{ csrf_token() }}'
                        })
                    );
                }
            });

            // Start session
            startSession();
        </script>
    @endpush
</x-layouts.terminal>
