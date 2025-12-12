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
            --term-cursor: #ff79c6;
            --term-selection: #ff79c6;
        }

        #terminal-frame {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            border-radius: 0.75rem;
        }

        .terminal-card {
            background: var(--term-sidebar-bg);
            border: 1px solid var(--term-border);
            border-radius: 1rem;
            overflow: hidden;
            height: calc(100vh - 200px);
        }

        @media (max-width: 1024px) {
            .terminal-card {
                height: calc(100vh - 160px);
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-[var(--term-bg)] text-[var(--term-text)] font-mono transition-colors duration-300" data-theme="{{ auth()->user()->terminal_theme ?? 'saturn' }}" id="app-container">
        @php
            $sanitizedHost = trim(str_replace(['{{', '}}'], '', $server->host));
            $connectionString = "{$server->username}@{$sanitizedHost}:{$server->port}";
            $currentTheme = auth()->user()->terminal_theme ?? 'saturn';
            $themes = [
                'saturn' => 'Saturn',
                'dracula' => 'Dracula',
                'github-dark' => 'GitHub Dark',
                'github-light' => 'GitHub Light',
                'cyberpunk' => 'Cyberpunk',
            ];
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
                    <button
                        type="button"
                        onclick="location.reload()"
                        class="rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)] hover:shadow-lg hover:shadow-[var(--term-cursor)]/20"
                    >
                        Clear output
                    </button>
                    <a
                        href="{{ route('servers.show', $server) }}"
                        class="rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-text)] hover:text-[var(--term-text)]"
                    >
                        Exit console
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="mx-auto max-w-7xl px-5 py-6">
            <div class="grid gap-6 lg:grid-cols-12">
                <!-- Left Sidebar -->
                <div class="space-y-6 lg:col-span-3">
                    <!-- Session Card -->
                    <div class="rounded-2xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] p-5 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-[var(--term-text)]/80">Session</h3>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-[var(--term-cursor)]/10 px-2.5 py-1 text-xs font-semibold text-[var(--term-cursor)]">
                                <span class="h-1.5 w-1.5 rounded-full bg-[var(--term-cursor)]"></span>
                                Live
                            </span>
                        </div>

                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="font-semibold text-[var(--term-text)]/60">Host</dt>
                                <dd class="mt-1 font-mono text-[var(--term-text)]">{{ $server->host }}:{{ $server->port }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-[var(--term-text)]/60">User</dt>
                                <dd class="mt-1 font-mono text-[var(--term-text)]">{{ $server->username }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-[var(--term-text)]/60">Latency</dt>
                                <dd class="mt-1 flex items-center gap-2 font-mono text-[var(--term-cursor)]">
                                    <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)]"></span>
                                    ~24ms
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-4 pt-4 border-t border-[var(--term-border)]">
                            <div class="relative">
                                <button
                                    id="theme-toggle"
                                    onclick="document.getElementById('theme-dropdown').classList.toggle('hidden')"
                                    class="flex w-full items-center justify-between rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2.5 text-sm font-semibold transition hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)]"
                                >
                                    <span class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                        </svg>
                                        <span id="theme-label">{{ $themes[$currentTheme] }}</span>
                                    </span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="theme-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] shadow-lg overflow-hidden z-10">
                                    @foreach($themes as $themeKey => $themeName)
                                        <button
                                            onclick="selectTheme('{{ $themeKey }}', '{{ $themeName }}')"
                                            class="flex w-full items-center justify-between px-3 py-2.5 text-sm font-semibold text-[var(--term-text)] hover:bg-[var(--term-bg)] transition {{ $themeKey === $currentTheme ? 'bg-[var(--term-cursor)]/10 text-[var(--term-cursor)]' : '' }}"
                                        >
                                            <span>{{ $themeName }}</span>
                                            @if($themeKey === $currentTheme)
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button
                                onclick="disconnectTerminal()"
                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Disconnect
                            </button>
                        </div>
                    </div>

                    <!-- Quick Notes -->
                    <div class="rounded-2xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] p-5 shadow-sm">
                        <h3 class="mb-4 text-sm font-bold uppercase tracking-wider text-[var(--term-text)]/80">Quick notes</h3>
                        <ul class="space-y-3 text-xs text-[var(--term-text)]/70">
                            <li class="flex gap-2">
                                <span class="text-[var(--term-cursor)]">•</span>
                                <span>Use tab to request completions from the server.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-[var(--term-cursor)]">•</span>
                                <span>Interactive commands like htop, vim, and nano work perfectly.</span>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-[var(--term-cursor)]">•</span>
                                <span>Stay on this page while commands execute.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Terminal -->
                <div class="lg:col-span-9">
                    <div class="terminal-card">
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
            </div>
        </div>
    </div>

    <script>
        // Theme management
        let currentTheme = '{{ $currentTheme }}';

        async function selectTheme(themeKey, themeName) {
            currentTheme = themeKey;
            const container = document.getElementById('app-container');
            const label = document.getElementById('theme-label');
            const dropdown = document.getElementById('theme-dropdown');

            // Update UI immediately
            container.setAttribute('data-theme', themeKey);
            label.textContent = themeName;
            dropdown.classList.add('hidden');

            // Save to database
            try {
                await fetch('{{ route('terminal.theme.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ theme: themeKey })
                });

                // Stop current terminal session
                if (window.terminalSessionId) {
                    await fetch('{{ url('ttyd/stop') }}/' + window.terminalSessionId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                }

                // Reload the page to start a new terminal with the new theme
                window.location.reload();
            } catch (error) {
                console.error('Failed to save theme preference:', error);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('theme-dropdown');
            const toggle = document.getElementById('theme-toggle');
            if (dropdown && toggle && !toggle.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

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
                if (pulse) {
                    pulse.style.background = '#ef4444';
                }

                loading.innerHTML = '<div class="text-center space-y-4 p-8">' +
                    '<svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' +
                    '</svg>' +
                    '<p class="text-sm font-semibold text-red-600">Connection Failed</p>' +
                    '<p class="text-xs text-gray-600">' + error.message + '</p>' +
                    '<button onclick="location.reload()" class="mt-4 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:border-orange-500 hover:text-orange-500">Try Again</button>' +
                    '</div>';
            }
        }

        async function disconnectTerminal() {
            if (window.terminalSessionId) {
                try {
                    await fetch('{{ url('ttyd/stop') }}/' + window.terminalSessionId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                } catch (error) {
                    console.error('Failed to stop terminal:', error);
                }
            }
            // Redirect to server page
            window.location.href = '{{ route('servers.show', $server) }}';
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

        // Initialize on page load
        startTerminal();
    </script>
</body>
</html>