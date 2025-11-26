<div
    class="min-h-screen bg-gradient-to-br from-zinc-950 via-zinc-900 to-black text-zinc-50 font-sans selection:bg-emerald-500/20"
    x-data="{ searchQuery: @entangle('searchQuery') }"
>
    @php
        $sanitizedHost = trim(str_replace(['{{', '}}'], '', $server->host));
        $connectionString = "{$server->username}@{$sanitizedHost}:{$server->port}";
    @endphp

    <header class="hidden lg:block border-b border-white/5 bg-white/5 backdrop-blur">
        <div class="mx-auto flex max-w-6xl flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-300">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.75A2.75 2.75 0 016.75 5h10.5A2.75 2.75 0 0120 7.75v8.5A2.75 2.75 0 0117.25 19H6.75A2.75 2.75 0 014 16.25z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 9h3m-3 3h10m-10 3h7" />
                    </svg>
                    <span class="absolute -right-1 -top-1 flex h-3 w-3">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-70 animate-ping"></span>
                        <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-400"></span>
                    </span>
                </span>
                <div class="space-y-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-emerald-300">Connected</p>
                    <h1 class="text-lg font-semibold leading-tight text-white sm:text-xl">{{ $server->name }}</h1>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-zinc-100">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            {{ $connectionString }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-zinc-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-200"></span>
                            Dir: <span class="font-mono text-white">{{ $currentDirectory }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                <button
                    type="button"
                    class="rounded-xl border border-white/10 bg-white/10 px-3 py-2 text-sm font-semibold text-zinc-100 transition hover:-translate-y-0.5 hover:border-emerald-400/50 hover:text-white hover:shadow-lg hover:shadow-emerald-500/25"
                    wire:click="clearOutput"
                >
                    Clear output
                </button>
                <a
                    href="{{ route('servers') }}"
                    class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-zinc-100 transition hover:-translate-y-0.5 hover:border-zinc-200 hover:text-white"
                >
                    Exit console
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Floating Header -->
    <header class="lg:hidden absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-6 py-4 bg-gradient-to-b from-black/80 to-transparent pointer-events-none">
        <div class="flex items-center gap-2 pointer-events-auto">
            <span class="text-sm font-medium text-zinc-400">~24ms</span>
        </div>
        <h2 class="text-base font-bold text-zinc-400 pointer-events-auto">{{ $server->name }}</h2>
        <a href="{{ route('servers') }}" class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-red-500/20 text-red-500 transition-colors hover:bg-red-500/40 pointer-events-auto">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </header>

    <main class="mx-auto flex flex-col h-screen lg:h-auto lg:grid lg:max-w-6xl lg:gap-5 lg:px-5 lg:py-6 lg:grid-cols-[320px_1fr]">
        <aside class="space-y-4 hidden lg:block">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-lg shadow-black/30 backdrop-blur">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-zinc-100">Session</p>
                    <span class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-200">
                        Live
                    </span>
                </div>
                <dl class="mt-4 space-y-3 text-sm text-zinc-300">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-400">Host</dt>
                        <dd class="font-mono text-zinc-100">{{ $sanitizedHost }}:{{ $server->port }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-400">User</dt>
                        <dd class="font-mono text-zinc-100">{{ $server->username }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-400">Latency</dt>
                        <dd class="flex items-center gap-2 font-medium text-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            ~24ms
                        </dd>
                    </div>
                </dl>
                <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                    <button
                        type="button"
                        class="flex items-center justify-center gap-2 rounded-lg border border-white/10 bg-zinc-900/60 px-3 py-2 font-semibold text-zinc-100 transition hover:border-emerald-300/50 hover:text-white"
                        wire:click="toggleTheme"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v2.25m0 10.5v2.25m7.5-7.5H17.25m-10.5 0H4.5m11.045-5.955L14.25 7.5m-4.5 9-1.295 1.295m0-10.59L9.75 7.5m4.5 9 1.295 1.295" />
                        </svg>
                        Theme
                    </button>
                    <button
                        type="button"
                        class="flex items-center justify-center gap-2 rounded-lg border border-white/10 bg-zinc-900/60 px-3 py-2 font-semibold text-zinc-100 transition hover:border-red-300/50 hover:text-white"
                        wire:click="disconnect"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5H7A2.5 2.5 0 004.5 7v10A2.5 2.5 0 007 19.5h1.25m7.5-15H17A2.5 2.5 0 0119.5 7v10A2.5 2.5 0 0117 19.5h-1.25m-6-15v15m4.5-15v15" />
                        </svg>
                        Disconnect
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-emerald-500/15 via-emerald-500/5 to-transparent p-4 shadow-inner shadow-emerald-500/10">
                <p class="text-sm font-semibold text-white">Quick notes</p>
                <ul class="mt-3 space-y-2 text-sm text-zinc-200">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Use tab to request completions from the server.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Filter output live without clearing history.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Stay on this page while commands execute.
                    </li>
                </ul>
            </div>
        </aside>

        <section class="flex flex-1 flex-col overflow-hidden lg:rounded-3xl lg:border lg:border-white/10 lg:bg-black/60 lg:shadow-2xl lg:shadow-black/40 lg:backdrop-blur lg:min-h-[65vh]">
            <div class="hidden lg:flex flex-wrap items-center gap-3 border-b border-white/5 px-5 py-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.14em] text-zinc-400">Shell</p>
                    <p class="font-mono text-sm text-emerald-200">{{ $connectionString }}</p>
                </div>
                <div class="hidden h-9 w-px bg-white/10 sm:block"></div>
                <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                    <span class="rounded-full bg-white/5 px-2.5 py-1 text-xs text-zinc-300">Dir</span>
                    <span class="font-mono text-sm text-white break-all">{{ $currentDirectory }}</span>
                </div>
                <div class="ms-auto flex w-full flex-wrap items-center gap-2 sm:w-auto">
                    <div class="relative w-full sm:w-56">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6.75a3.75 3.75 0 105.3 5.3l3.45 3.45" />
                        </svg>
                        <input
                            type="text"
                            class="w-full rounded-xl border border-white/10 bg-white/5 py-2 ps-9 pe-3 text-sm text-white placeholder:text-zinc-500 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                            placeholder="Filter output..."
                            x-model="searchQuery"
                        />
                    </div>
                    <span class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300">
                        History: {{ count($history) }}
                    </span>
                </div>
            </div>

            <div
                class="flex-1 overflow-y-auto px-6 pb-40 pt-16 lg:px-5 lg:py-4 text-sm leading-relaxed text-zinc-100 custom-scrollbar scroll-smooth"
                id="terminal-output"
                x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
                x-init="scrollToBottom(); $watch('$wire.outputCount', () => setTimeout(() => scrollToBottom(), 50))"
                @scroll-terminal.window="scrollToBottom()"
            >
                <div class="mb-4 space-y-2 text-emerald-300">
                    <p>Last login: {{ now()->subDays(2)->format('D M d H:i:s Y') }} from 192.168.1.100</p>
                    <p>FluxSSH: Connected to {{ $server->name }}.</p>
                </div>

                @php
                    $filteredOutput = $this->getFilteredOutput();
                @endphp

                @forelse ($filteredOutput as $line)
                    <div class="group relative rounded-xl border border-white/5 bg-white/5 px-3 py-2 hover:border-emerald-400/40 hover:bg-white/5">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-1 h-2 w-2 rounded-full {{ [
                                    'command' => 'bg-emerald-400',
                                    'output' => 'bg-zinc-400',
                                    'error' => 'bg-red-400',
                                    'success' => 'bg-emerald-400',
                                    'info' => 'bg-sky-400',
                                ][$line['type']] ?? 'bg-zinc-500' }}"
                            ></span>
                            <div class="flex-1 space-y-1">
                                <p class="font-mono text-[13px] {{ $this->getLineClass($line['type']) }}">
                                    @if ($line['type'] === 'command')
                                        <span class="text-zinc-500">{{ $server->username }}@hostname:~$</span>
                                        <span class="ms-2">{{ $line['text'] }}</span>
                                    @else
                                        {!! $line['text'] !!}
                                    @endif
                                </p>
                                <p class="text-[11px] uppercase tracking-[0.14em] text-zinc-500">{{ $line['timestamp'] ?? now()->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-white/10 bg-white/5 px-4 py-6 text-center text-zinc-400">
                        Output will appear here after you run a command.
                    </div>
                @endforelse
            </div>

            <div class="hidden lg:block border-t border-white/5 bg-gradient-to-r from-white/5 via-white/5 to-transparent px-5 py-4">
                <div class="flex flex-col gap-3 rounded-2xl border border-white/10 bg-zinc-950/70 p-3 shadow-inner shadow-black/30 sm:flex-row sm:items-center sm:gap-4">
                    <div class="flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs text-zinc-300">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Ready for input
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-sm text-emerald-200">{{ $server->username }}@hostname:~$</span>
                            <input
                                wire:model.live="command"
                                wire:keydown.enter="executeCommand"
                                wire:keydown.tab.prevent="tabComplete"
                                wire:keydown.arrow-up.prevent="navigateHistory('up')"
                                wire:keydown.arrow-down.prevent="navigateHistory('down')"
                                type="text"
                                class="flex-1 bg-transparent text-sm text-white placeholder:text-zinc-600 focus:outline-none"
                                placeholder="Type a command and press Enter"
                            />
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-semibold text-emerald-200 transition hover:border-emerald-400/60 hover:text-white"
                            wire:click="executeCommand"
                        >
                            Run
                        </button>
                        <span class="rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-[11px] uppercase tracking-[0.16em] text-zinc-400">
                            Enter ↵
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mobile Accessory Bar & Input -->
            <div class="lg:hidden fixed bottom-4 left-1/2 w-full max-w-2xl -translate-x-1/2 px-4 z-20">
                <div class="glass flex flex-col gap-2 rounded-xl p-2 border border-white/10 bg-zinc-900/60 backdrop-blur-xl">
                    <!-- Accessory Keys -->
                    <div class="flex gap-1 overflow-x-auto scrollbar-hide pb-1">
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-cyan-400 transition-colors hover:bg-white/10 bg-white/5">ESC</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-cyan-400 transition-colors hover:bg-white/10 bg-white/5" wire:click="tabComplete">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-cyan-400 transition-colors hover:bg-white/10 bg-white/5">CTRL</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-cyan-400 transition-colors hover:bg-white/10 bg-white/5">ALT</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-lg font-bold text-cyan-400 transition-colors hover:bg-white/10 bg-white/5">/</button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-cyan-400 transition-colors hover:bg-white/10 bg-white/5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-cyan-400 transition-colors hover:bg-white/10 bg-white/5" wire:click="navigateHistory('up')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
                        </button>
                        <button class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-cyan-400 transition-colors hover:bg-white/10 bg-white/5" wire:click="navigateHistory('down')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        </button>
                    </div>
                    
                    <!-- Mobile Input -->
                    <div class="flex items-center gap-2">
                        <div class="flex-1 relative">
                            <input
                                wire:model.live="command"
                                wire:keydown.enter="executeCommand"
                                wire:keydown.arrow-up.prevent="navigateHistory('up')"
                                wire:keydown.arrow-down.prevent="navigateHistory('down')"
                                type="text"
                                class="w-full bg-black/50 border border-white/10 rounded-lg py-2 px-3 text-sm text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50"
                                placeholder="Type command..."
                            />
                        </div>
                        <button 
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-tr from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/20"
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
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #52525b; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
