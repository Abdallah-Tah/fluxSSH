<div
    class="min-h-screen bg-[var(--term-bg)] text-[var(--term-text)] font-mono selection:bg-[var(--term-selection)] selection:text-white transition-colors duration-300"
    x-data="{ searchQuery: @entangle('searchQuery') }"
    data-theme="{{ $theme }}"
>
    @php
        $sanitizedHost = trim(str_replace(['{{', '}}'], '', $server->host));
        $connectionString = "{$server->username}@{$sanitizedHost}:{$server->port}";
    @endphp

    <header class="hidden lg:block border-b border-[var(--term-border)] bg-[var(--term-header-bg)] backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-[var(--term-cursor)]/10 text-[var(--term-cursor)] shadow-lg shadow-[var(--term-cursor)]/20">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.75A2.75 2.75 0 016.75 5h10.5A2.75 2.75 0 0120 7.75v8.5A2.75 2.75 0 0117.25 19H6.75A2.75 2.75 0 014 16.25z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h3m-3 3h10m-10 3h7" />
                    </svg>
                    <span class="absolute -right-1 -top-1 flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-[var(--term-cursor)] opacity-70 animate-ping"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full bg-[var(--term-cursor)]"></span>
                    </span>
                </span>
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-[var(--term-text)]/60">Connected</p>
                    <h1 class="text-lg font-semibold leading-tight text-[var(--term-text)] sm:text-xl">{{ $server->name }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full bg-[var(--term-cursor)]/10 px-3 py-1 text-xs font-semibold text-[var(--term-cursor)]">
                            <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)]"></span>
                            {{ $connectionString }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-[var(--term-text)]/5 px-3 py-1 text-xs font-semibold text-[var(--term-text)]/80">
                            Dir: <span class="font-mono text-[var(--term-text)]">{{ $currentDirectory }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <button
                    type="button"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)] hover:shadow-lg hover:shadow-[var(--term-cursor)]/20"
                    wire:click="clearOutput"
                >
                    Clear output
                </button>
                <a
                    href="{{ route('servers') }}"
                    class="rounded-xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] px-3 py-2 text-sm font-semibold text-[var(--term-text)]/80 transition hover:-translate-y-0.5 hover:border-[var(--term-text)] hover:text-[var(--term-text)]"
                >
                    Exit console
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Floating Header -->
    <header class="lg:hidden absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-6 py-4 bg-[var(--term-header-bg)]/80 pointer-events-none backdrop-blur-sm border-b border-[var(--term-border)]">
        <div class="flex items-center gap-2 pointer-events-auto">
            <span class="text-sm font-medium text-[var(--term-cursor)]">~24ms</span>
        </div>
        <h2 class="text-base font-bold text-[var(--term-text)] pointer-events-auto">{{ $server->name }}</h2>
        <a href="{{ route('servers') }}" class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-red-500/10 text-red-500 transition-colors hover:bg-red-500/20 pointer-events-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </header>

    <main class="mx-auto flex flex-col h-screen lg:h-auto lg:grid lg:max-w-6xl lg:gap-5 lg:px-5 lg:py-6 lg:grid-cols-[320px_1fr]">
        <aside class="space-y-4 hidden lg:block">
            <div class="rounded-2xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] p-4 shadow-lg backdrop-blur">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-[var(--term-text)]">Session</p>
                    <span class="rounded-full bg-[var(--term-cursor)]/10 px-2.5 py-1 text-xs font-semibold text-[var(--term-cursor)]">
                        Live
                    </span>
                </div>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-[var(--term-text)]/60">Host</dt>
                        <dd class="font-mono text-[var(--term-text)]">{{ $sanitizedHost }}:{{ $server->port }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-[var(--term-text)]/60">User</dt>
                        <dd class="font-mono text-[var(--term-text)]">{{ $server->username }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-[var(--term-text)]/60">Latency</dt>
                        <dd class="flex items-center gap-2 font-medium text-[var(--term-cursor)]">
                            <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)]"></span>
                            ~24ms
                        </dd>
                    </div>
                </dl>
                <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                    <button
                        type="button"
                        class="flex items-center justify-center gap-2 rounded-lg border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 font-semibold text-[var(--term-text)]/80 transition hover:border-[var(--term-cursor)] hover:text-[var(--term-cursor)]"
                        wire:click="toggleTheme"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v2.25m0 10.5v2.25m7.5-7.5H17.25m-10.5 0H4.5m11.045-5.955L14.25 7.5m-4.5 9-1.295 1.295m0-10.59L9.75 7.5m4.5 9 1.295 1.295" />
                        </svg>
                        Theme: {{ ucfirst($theme) }}
                    </button>
                    <button
                        type="button"
                        class="flex items-center justify-center gap-2 rounded-lg border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 font-semibold text-[var(--term-text)]/80 transition hover:border-red-500 hover:text-red-500"
                        wire:click="disconnect"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5H7A2.5 2.5 0 004.5 7v10A2.5 2.5 0 007 19.5h1.25m7.5-15H17A2.5 2.5 0 0119.5 7v10A2.5 2.5 0 0117 19.5h-1.25m-6-15v15m4.5-15v15" />
                        </svg>
                        Disconnect
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--term-border)] bg-[var(--term-sidebar-bg)] p-4 shadow-sm">
                <p class="text-sm font-semibold text-[var(--term-text)]">Quick notes</p>
                <ul class="mt-3 space-y-2 text-sm text-[var(--term-text)]/80">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--term-cursor)]"></span>
                        Use tab to request completions from the server.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--term-cursor)]"></span>
                        Filter output live without clearing history.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[var(--term-cursor)]"></span>
                        Stay on this page while commands execute.
                    </li>
                </ul>
            </div>
        </aside>

        <section class="flex flex-1 flex-col overflow-hidden lg:rounded-3xl lg:border lg:border-[var(--term-border)] lg:bg-[var(--term-bg)] lg:shadow-2xl lg:min-h-[65vh]">
            <div class="hidden lg:flex flex-wrap items-center gap-3 border-b border-[var(--term-border)] bg-[var(--term-header-bg)] px-5 py-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-[var(--term-text)]/50">Shell</p>
                    <p class="font-mono text-sm text-[var(--term-cursor)]">{{ $connectionString }}</p>
                </div>
                <div class="hidden h-9 w-px bg-[var(--term-border)] sm:block"></div>
                <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                    <span class="rounded-full bg-[var(--term-text)]/5 px-2.5 py-1 text-xs text-[var(--term-text)]/70 font-medium">Dir</span>
                    <span class="font-mono text-sm text-[var(--term-text)] break-all">{{ $currentDirectory }}</span>
                </div>
                <div class="ms-auto flex w-full flex-wrap items-center gap-2 sm:w-auto">
                    <div class="relative w-full sm:w-56">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-[var(--term-text)]/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6.75a3.75 3.75 0 105.3 5.3l3.45 3.45" />
                        </svg>
                        <input
                            type="text"
                            class="w-full rounded-xl border border-[var(--term-border)] bg-[var(--term-bg)] py-2 ps-9 pe-3 text-sm text-[var(--term-text)] placeholder:text-[var(--term-text)]/40 focus:border-[var(--term-cursor)] focus:outline-none focus:ring-2 focus:ring-[var(--term-cursor)]/20"
                            placeholder="Filter output..."
                            x-model="searchQuery"
                        />
                    </div>
                    <span class="rounded-lg border border-[var(--term-border)] bg-[var(--term-bg)] px-3 py-2 text-xs text-[var(--term-text)]/70 font-medium">
                        History: {{ count($history) }}
                    </span>
                </div>
            </div>

            <div
                class="flex-1 overflow-y-auto px-6 pb-40 pt-16 lg:px-5 lg:py-4 text-sm leading-relaxed text-[var(--term-text)] custom-scrollbar scroll-smooth font-mono"
                id="terminal-output"
                x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                x-init="scrollToBottom(); $watch('$wire.outputCount', () => setTimeout(() => scrollToBottom(), 50))"
                @scroll-terminal.window="scrollToBottom()"
            >
                <div class="mb-4 space-y-2 text-[var(--term-cursor)]">
                    <p>Last login: {{ now()->subDays(2)->format('D M d H:i:s Y') }} from 192.168.1.100</p>
                    <p>FluxSSH: Connected to {{ $server->name }}.</p>
                </div>

                @php
                    $filteredOutput = $this->getFilteredOutput();
                @endphp

                @forelse ($filteredOutput as $line)
                    <div class="group relative rounded-xl border border-transparent hover:border-[var(--term-border)] hover:bg-[var(--term-text)]/5 px-3 py-1 transition-all">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-1.5 h-1.5 w-1.5 rounded-full shrink-0"
                                style="background-color: {{ match($line['type']) {
                                    'command' => 'var(--term-cursor)',
                                    'output' => 'var(--term-text)',
                                    'error' => 'var(--term-ansi-red)',
                                    'success' => 'var(--term-ansi-green)',
                                    'info' => 'var(--term-ansi-cyan)',
                                    default => 'var(--term-text)'
                                } }}; opacity: {{ $line['type'] === 'output' ? '0.5' : '1' }}"
                            ></span>
                            <div class="flex-1 space-y-1 break-all">
                                <p class="font-mono text-[13px]" style="color: {{ match($line['type']) {
                                    'command' => 'var(--term-cursor)',
                                    'output' => 'var(--term-text)',
                                    'error' => 'var(--term-ansi-red)',
                                    'success' => 'var(--term-ansi-green)',
                                    'info' => 'var(--term-ansi-cyan)',
                                    default => 'var(--term-text)'
                                } }}">
                                    @if ($line['type'] === 'command')
                                        <span class="opacity-50">{{ $server->username }}@hostname:~$</span>
                                        <span class="ms-2 font-bold">{{ $line['text'] }}</span>
                                    @else
                                        {!! $line['text'] !!}
                                    @endif
                                </p>
                                <p class="text-[10px] uppercase tracking-[0.14em] opacity-30">{{ $line['timestamp'] ?? now()->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-[var(--term-border)] bg-[var(--term-text)]/5 px-4 py-6 text-center text-[var(--term-text)]/50">
                        Output will appear here after you run a command.
                    </div>
                @endforelse
            </div>

            <div class="hidden lg:block border-t border-[var(--term-border)] bg-[var(--term-header-bg)] px-5 py-4">
                <div class="flex flex-col gap-3 rounded-2xl border border-[var(--term-border)] bg-[var(--term-bg)] p-3 shadow-lg sm:flex-row sm:items-center sm:gap-4">
                    <div class="flex items-center gap-2 rounded-lg border border-[var(--term-border)] bg-[var(--term-cursor)]/10 px-3 py-2 text-xs text-[var(--term-cursor)] font-medium">
                        <span class="h-2 w-2 rounded-full bg-[var(--term-cursor)] animate-pulse"></span>
                        Ready for input
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3"
                             x-data="{
                                handleTab(e) {
                                    e.preventDefault();
                                    $wire.command = e.target.value;
                                    $wire.call('tabComplete');
                                }
                             }"
                             @focus-input.window="$refs.commandInput.focus()">
                            <span class="font-mono text-sm text-[var(--term-cursor)]">{{ $server->username }}@hostname:~$</span>
                            <input
                                x-ref="commandInput"
                                wire:model.live.debounce.150ms="command"
                                @keydown.tab="handleTab"
                                wire:keydown.enter="executeCommand"
                                wire:keydown.arrow-up.prevent="navigateHistory('up')"
                                wire:keydown.arrow-down.prevent="navigateHistory('down')"
                                type="text"
                                class="flex-1 bg-transparent text-sm text-[var(--term-text)] placeholder:text-[var(--term-text)]/30 focus:outline-none font-mono"
                                placeholder="Type a command..."
                                autocomplete="off"
                            />
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="rounded-xl bg-[var(--term-cursor)] px-3 py-2 text-sm font-semibold text-[var(--term-bg)] transition shadow-md shadow-[var(--term-cursor)]/20 hover:shadow-lg hover:shadow-[var(--term-cursor)]/30 hover:-translate-y-0.5"
                            wire:click="executeCommand"
                        >
                            Run
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Accessory Bar & Input -->
            <div class="lg:hidden fixed bottom-4 left-1/2 w-full max-w-2xl -translate-x-1/2 px-4 z-20">
                <div class="glass flex flex-col gap-2 rounded-xl p-2 border border-[var(--term-border)] bg-[var(--term-bg)]/95 backdrop-blur-xl shadow-xl">
                    <!-- Accessory Keys -->
                    <div class="flex gap-1 overflow-x-auto scrollbar-hide pb-1">
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5">ESC</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5" wire:click="tabComplete">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5">CTRL</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5">ALT</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-lg font-bold text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5">/</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5" wire:click="navigateHistory('up')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-[var(--term-cursor)] transition-colors hover:bg-[var(--term-text)]/10 bg-[var(--term-text)]/5" wire:click="navigateHistory('down')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                    </div>

                    <!-- Mobile Input -->
                    <div class="flex items-center gap-2"
                         x-data="{
                            handleTab(e) {
                                e.preventDefault();
                                $wire.command = e.target.value;
                                $wire.call('tabComplete');
                            }
                         }"
                         @focus-input.window="$refs.mobileCommandInput.focus()">
                        <div class="flex-1 relative">
                            <input
                                x-ref="mobileCommandInput"
                                wire:model.live.debounce.150ms="command"
                                @keydown.tab="handleTab"
                                wire:keydown.enter="executeCommand"
                                wire:keydown.arrow-up.prevent="navigateHistory('up')"
                                wire:keydown.arrow-down.prevent="navigateHistory('down')"
                                type="text"
                                class="w-full bg-[var(--term-bg)] border border-[var(--term-border)] rounded-lg py-2 px-3 text-sm text-[var(--term-text)] placeholder-[var(--term-text)]/40 focus:outline-none focus:border-[var(--term-cursor)] font-mono"
                                placeholder="Type command..."
                                autocomplete="off"
                            />
                        </div>
                        <button
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--term-cursor)] text-[var(--term-bg)] shadow-lg shadow-[var(--term-cursor)]/30 hover:shadow-xl hover:shadow-[var(--term-cursor)]/40 transition-all"
                            wire:click="executeCommand"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--term-border); border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--term-text); opacity: 0.5; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
