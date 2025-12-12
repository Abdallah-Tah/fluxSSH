<div class="min-h-screen bg-[var(--term-bg)] text-[var(--term-text)] font-mono selection:bg-[var(--term-selection)] selection:text-white transition-colors duration-300"
    data-theme="{{ $theme }}" x-data="{
        fullscreen: false,
        showMobileMenu: false,
        terminalReady: false,
        connectionStatus: 'connecting'
    }" x-init="// Check if iframe is loaded
    $watch('terminalReady', value => {
        if (value) connectionStatus = 'connected';
    });">
    @php
        $sanitizedHost = trim(str_replace(['{{ ', ' }}'], '', $server->host));
        $connectionString = "{$server->username}@{$sanitizedHost}:{$server->port}";
    @endphp

    <!-- Desktop Header -->
    <header class="hidden lg:block border-b border-[var(--term-border)] bg-[var(--term-header-bg)] backdrop-blur"
        x-show="!fullscreen">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span
                    class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--term-cursor)]/10 text-[var(--term-cursor)] shadow-lg shadow-[var(--term-cursor)]/20">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span class="absolute -right-1 -top-1 flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full rounded-full opacity-70"
                            :class="connectionStatus === 'connected' ? 'bg-[var(--term-cursor)] animate-none' :
                                'bg-amber-500 animate-ping'"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full"
                            :class="connectionStatus === 'connected' ? 'bg-[var(--term-cursor)]' : 'bg-amber-500'"></span>
                    </span>
                </span>
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--term-text)]/60"
                        x-text="connectionStatus === 'connected' ? 'Connected' : 'Connecting...'">Connected</p>
                    <h1 class="text-lg font-semibold leading-tight text-[var(--term-text)] sm:text-xl">
                        {{ $server->name }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-[var(--term-cursor)]/10 px-3 py-1 text-xs font-semibold text-[var(--term-cursor)]">
                            <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)]"></span>
                            {{ $connectionString }}
                        </span>
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-[var(--term-text)]/5 px-3 py-1 text-xs font-semibold text-[var(--term-text)]/80">
                            Professional Terminal (ttyd)
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <button type="button"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)] hover:shadow-lg hover:shadow-[var(--term-cursor)]/20"
                    wire:click="toggleTheme">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.5v2.25m0 10.5v2.25m7.5-7.5H17.25m-10.5 0H4.5m11.045-5.955L14.25 7.5m-4.5 9-1.295 1.295m0-10.59L9.75 7.5m4.5 9 1.295 1.295" />
                        </svg>
                        Theme: {{ ucfirst($theme) }}
                    </span>
                </button>
                <button type="button"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)] hover:shadow-lg hover:shadow-[var(--term-cursor)]/20"
                    @click="fullscreen = !fullscreen">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                        Fullscreen
                    </span>
                </button>
                <button type="button"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-amber-500 hover:text-amber-500"
                    wire:click="reconnect">
                    <span class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reconnect
                    </span>
                </button>
                <a href="{{ route('servers') }}"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-text)] hover:text-[var(--term-text)]">
                    Exit terminal
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Header -->
    <header class="lg:hidden absolute top-0 left-0 right-0 z-30 flex flex-col" x-show="!fullscreen">
        <div
            class="flex items-center justify-between px-4 py-3 bg-[var(--term-header-bg)]/95 backdrop-blur-md border-b border-[var(--term-border)]">
            <div class="flex items-center gap-3">
                <button @click="showMobileMenu = !showMobileMenu"
                    class="p-2 -ml-2 rounded-lg text-[var(--term-text)]/70 hover:bg-[var(--term-text)]/10 transition-colors">
                    <svg x-show="!showMobileMenu" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-cloak x-show="showMobileMenu" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-sm font-bold text-[var(--term-text)]">{{ $server->name }}</h2>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-medium px-2 py-1 rounded-md"
                    :class="connectionStatus === 'connected' ? 'text-[var(--term-cursor)] bg-[var(--term-cursor)]/10' :
                        'text-amber-500 bg-amber-500/10'"
                    x-text="connectionStatus === 'connected' ? 'Connected' : 'Connecting...'"></span>
                <button @click="fullscreen = !fullscreen"
                    class="p-2 rounded-lg text-[var(--term-text)]/70 hover:bg-[var(--term-text)]/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="showMobileMenu" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="bg-[var(--term-header-bg)]/95 backdrop-blur-xl border-b border-[var(--term-border)] shadow-xl px-4 py-4 space-y-3"
            @click.outside="showMobileMenu = false" style="display: none;">
            <div class="grid grid-cols-2 gap-2">
                <button type="button" wire:click="toggleTheme"
                    class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] text-[var(--term-text)] active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-[var(--term-cursor)]" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <span class="text-xs font-medium">{{ ucfirst($theme) }}</span>
                </button>

                <button type="button" wire:click="reconnect"
                    class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] text-[var(--term-text)] active:scale-95 transition-all">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span class="text-xs font-medium">Reconnect</span>
                </button>
            </div>

            <a href="{{ route('servers') }}"
                class="w-full flex items-center justify-center gap-2 p-3 rounded-xl bg-[var(--term-bg)] border border-[var(--term-border)] text-[var(--term-text)] font-medium active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Back to Servers
            </a>
        </div>
    </header>

    <!-- Main Terminal Area -->
    <main class="transition-all duration-300"
        :class="fullscreen ? 'h-screen' : 'h-[calc(100vh-80px)] lg:h-[calc(100vh-88px)] pt-14 lg:pt-0'">
        @if ($error)
            <!-- Error State -->
            <div class="h-full flex flex-col items-center justify-center gap-4 p-6">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-500/10 text-red-500">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="text-center space-y-2">
                    <h3 class="text-lg font-semibold text-[var(--term-text)]">Connection Failed</h3>
                    <p class="text-sm text-[var(--term-text)]/60 max-w-md">{{ $error }}</p>
                </div>
                <button wire:click="reconnect"
                    class="rounded-xl bg-[var(--term-cursor)] px-4 py-2 text-sm font-semibold text-[var(--term-bg)] transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[var(--term-cursor)]/30">
                    Try Again
                </button>
            </div>
        @elseif($port)
            <!-- Terminal iframe -->
            <div class="h-full w-full relative">
                <!-- Loading overlay -->
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-4 bg-[var(--term-bg)] z-10 transition-opacity duration-300"
                    :class="terminalReady ? 'opacity-0 pointer-events-none' : 'opacity-100'">
                    <div class="relative">
                        <div class="h-12 w-12 rounded-full border-2 border-[var(--term-border)]"></div>
                        <div
                            class="absolute inset-0 h-12 w-12 rounded-full border-2 border-[var(--term-cursor)] border-t-transparent animate-spin">
                        </div>
                    </div>
                    <p class="text-sm text-[var(--term-text)]/60">Initializing terminal...</p>
                </div>

                <iframe id="ttyd-frame" src="http://localhost:{{ $port }}/" class="h-full w-full border-0"
                    @load="terminalReady = true; connectionStatus = 'connected'"
                    @error="connectionStatus = 'error'"
                ></iframe>
            </div>
        @else
            <!-- Loading State -->
            <div class="h-full flex flex-col items-center justify-center gap-4">
                <div class="relative">
                    <div class="h-12 w-12 rounded-full border-2 border-[var(--term-border)]"></div>
                    <div class="absolute inset-0 h-12 w-12 rounded-full border-2 border-[var(--term-cursor)] border-t-transparent animate-spin"></div>
                </div>
                <p class="text-sm text-[var(--term-text)]/60">Starting terminal session...</p>
            </div> @endif
                    </main>

                    <!-- Keyboard shortcut hint (desktop only) -->
                    <div class="hidden lg:block fixed bottom-4 right-4 text-xs text-[var(--term-text)]/40"
                        x-show="!fullscreen">
                        Press <kbd class="px-1.5 py-0.5 rounded bg-[var(--term-text)]/10 font-mono">F11</kbd> for
                        fullscreen
                    </div>

                    <!-- Fullscreen keyboard handler -->
                    <div x-data @keydown.f11.window.prevent="fullscreen = !fullscreen"
                        @keydown.escape.window="fullscreen = false"></div>
            </div>
