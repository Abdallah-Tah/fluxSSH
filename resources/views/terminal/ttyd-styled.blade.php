<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $server->name }} - Professional Terminal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --term-bg: #f5f0e8;
            --term-text: #2d3748;
            --term-header-bg: #faf8f3;
            --term-sidebar-bg: #fff;
            --term-border: #e5dfd0;
            --term-cursor: #e67e22;
            --term-selection: #e67e22;
        }

        [data-theme="dark"] {
            --term-bg: #1a1a1a;
            --term-text: #e0e0e0;
            --term-header-bg: #2d2d2d;
            --term-sidebar-bg: #252525;
            --term-border: #3a3a3a;
            --term-cursor: #ff9500;
            --term-selection: #ff9500;
        }

        #terminal-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .terminal-container {
            height: calc(100vh - 120px);
            background: var(--term-bg);
        }

        @media (max-width: 1024px) {
            .terminal-container {
                height: calc(100vh - 80px);
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-[var(--term-bg)] text-[var(--term-text)] font-mono transition-colors duration-300" data-theme="light">
        @php
            $sanitizedHost = trim(str_replace(['{{', '}}'], '', $server->host));
            $connectionString = "{$server->username}@{$sanitizedHost}:{$server->port}";
        @endphp

        <!-- Header -->
        <header class="border-b border-[var(--term-border)] bg-[var(--term-header-bg)] backdrop-blur">
            <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--term-cursor)]/10 text-[var(--term-cursor)] shadow-lg shadow-[var(--term-cursor)]/20">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.75A2.75 2.75 0 016.75 5h10.5A2.75 2.75 0 0120 7.75v8.5A2.75 2.75 0 0117.25 19H6.75A2.75 2.75 0 014 16.25z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h3m-3 3h10m-10 3h7" />
                        </svg>
                        <span class="absolute -right-1 -top-1 flex h-3 w-3">
                            <span id="pulse" class="absolute inline-flex h-full w-full rounded-full bg-[var(--term-cursor)] opacity-70 animate-ping"></span>
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-[var(--term-cursor)]"></span>
                        </span>
                    </span>
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--term-text)]/60">
                            <span id="status">CONNECTED</span>
                        </p>
                        <h1 class="text-lg font-semibold leading-tight text-[var(--term-text)] sm:text-xl">{{ $server->name }}</h1>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full bg-[var(--term-cursor)]/10 px-3 py-1 text-xs font-semibold text-[var(--term-cursor)]">
                                <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)]"></span>
                                {{ $connectionString }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-[var(--term-text)]/5 px-3 py-1 text-xs font-semibold text-[var(--term-text)]/80">
                                Dir: <span class="font-mono text-[var(--term-text)]">/root</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                    <a
                        href="{{ route('servers.show', $server) }}"
                        class="rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-text)] hover:text-[var(--term-text)]"
                    >
                        Exit console
                    </a>
                </div>
            </div>
        </header>

        <!-- Terminal Container -->
        <div class="terminal-container">
            <iframe id="terminal-frame" style="display: none;"></iframe>
            <div id="loading" class="flex items-center justify-center h-full">
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center gap-3 text-[var(--term-cursor)]">
                        <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-semibold">Starting terminal...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function startTerminal() {
            const loading = document.getElementById('loading');
            const frame = document.getElementById('terminal-frame');
            const status = document.getElementById('status');
            const pulse = document.getElementById('pulse');

            try {
                status.textContent = 'CONNECTING';

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
                status.textContent = 'CONNECTED';

                // Store session ID for cleanup
                window.terminalSessionId = data.session_id;

                console.log('Terminal started:', { port: data.port, sessionId: data.session_id });

            } catch (error) {
                console.error('Failed to start terminal:', error);
                status.textContent = 'CONNECTION FAILED';
                pulse.classList.remove('bg-[var(--term-cursor)]');
                pulse.classList.add('bg-red-500');

                loading.innerHTML = '<div class="text-center space-y-4">' +
                    '<svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' +
                    '</svg>' +
                    '<p class="text-sm font-semibold text-red-600">Connection Failed</p>' +
                    '<p class="text-xs text-[var(--term-text)]/60">' + error.message + '</p>' +
                    '<button onclick="location.reload()" class="mt-4 rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] px-4 py-2 text-sm font-semibold text-[var(--term-text)]/80 hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)]">Try Again</button>' +
                    '</div>';
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