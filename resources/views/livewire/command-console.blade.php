<div class="min-h-screen flex flex-col bg-linear-to-br from-zinc-950 via-zinc-900 to-zinc-950">
    <!-- Header -->
    <header class="bg-zinc-900/80 backdrop-blur-xl border-b border-zinc-800/50 sticky top-0 z-10">
        <div class="px-4 sm:px-6 py-3 sm:py-4">
            <!-- Mobile: Stacked layout, Desktop: Horizontal layout -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <!-- Server Info -->
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Server Icon with pulse animation when connected -->
                    <div class="relative shrink-0">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-linear-to-br from-emerald-500/20 to-emerald-600/10 flex items-center justify-center border border-emerald-500/20">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                        </div>
                        @if ($connected)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-zinc-900"></span>
                            </span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-base sm:text-lg font-semibold text-white truncate">{{ $server->name }}</h1>
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $connected ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $connected ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                {{ $connected ? 'Live' : 'Offline' }}
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-zinc-500 font-mono truncate">
                            {{ $server->getConnectionString() }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2 shrink-0">
                    <button wire:click="clearOutput"
                        class="p-2 sm:px-3 sm:py-2 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all duration-200 flex items-center gap-2"
                        title="Clear Terminal">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span class="hidden sm:inline text-sm">Clear</span>
                    </button>

                    <button wire:click="initializeConnection"
                        class="p-2 sm:px-3 sm:py-2 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800 transition-all duration-200 flex items-center gap-2 {{ $isLoading ? 'opacity-50 cursor-not-allowed' : '' }}"
                        title="Reconnect" @disabled($isLoading)>
                        <svg class="w-4 h-4 {{ $isLoading ? 'animate-spin' : '' }}" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span class="hidden sm:inline text-sm">Reconnect</span>
                    </button>

                    <a href="{{ route('servers') }}"
                        class="p-2 sm:px-3 sm:py-2 rounded-lg text-zinc-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200 flex items-center gap-2"
                        title="Close Terminal">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="hidden sm:inline text-sm">Close</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Terminal Window -->
    <main class="flex-1 flex flex-col min-h-0 p-2 sm:p-4 lg:p-6">
        <div
            class="flex-1 flex flex-col bg-zinc-950 rounded-xl sm:rounded-2xl border border-zinc-800/50 shadow-2xl shadow-black/50 overflow-hidden">
            <!-- Terminal Title Bar (macOS style) -->
            <div class="bg-zinc-900 px-4 py-2.5 flex items-center gap-3 border-b border-zinc-800/50">
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('servers') }}"
                        class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-400 transition-colors cursor-pointer"></a>
                    <div
                        class="w-3 h-3 rounded-full bg-yellow-500 hover:bg-yellow-400 transition-colors cursor-pointer">
                    </div>
                    <div class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-400 transition-colors cursor-pointer">
                    </div>
                </div>
                <div class="flex-1 text-center">
                    <span class="text-xs text-zinc-500 font-medium">{{ $server->username }}@{{ $server - > host }} —
                        FluxSSH</span>
                </div>
                <div class="w-[52px]"></div> <!-- Spacer for centering -->
            </div>

            <!-- Terminal Output -->
            <div class="flex-1 overflow-y-auto p-3 sm:p-4 lg:p-6 font-mono text-sm leading-relaxed scroll-smooth"
                id="terminal-output" x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }" x-init="scrollToBottom()"
                @scroll-terminal.window="scrollToBottom()">

                @forelse ($output as $index => $line)
                    <div class="group flex gap-2 sm:gap-4 py-0.5 sm:py-1 hover:bg-white/2 -mx-2 px-2 rounded transition-colors duration-150"
                        x-data="{ show: false }" x-init="setTimeout(() => show = true, {{ $index * 10 }})" x-show="show"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <span
                            class="text-zinc-700 text-[10px] sm:text-xs font-normal shrink-0 pt-0.5 w-12 sm:w-16 opacity-0 group-hover:opacity-100 transition-opacity select-none">
                            {{ $line['timestamp'] }}
                        </span>
                        <pre
                            class="{{ $this->getLineClass($line['type']) }} whitespace-pre-wrap break-all sm:wrap-break-word flex-1 text-xs sm:text-sm">{{ $line['text'] }}</pre>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full py-12 sm:py-20">
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-linear-to-br from-zinc-800 to-zinc-900 flex items-center justify-center mb-4 sm:mb-6 border border-zinc-700/50">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-zinc-600" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-zinc-300 mb-2">FluxSSH Terminal</h3>
                        <p class="text-zinc-600 text-sm text-center max-w-xs">
                            {{ $connected ? 'Ready for commands. Type something to get started.' : 'Establishing secure connection...' }}
                        </p>
                        @if (!$connected)
                            <div class="mt-4 flex items-center gap-2 text-amber-500">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-sm">Connecting...</span>
                            </div>
                        @endif
                    </div>
                @endforelse

                @if ($isLoading && count($output) > 0)
                    <div class="flex items-center gap-2 text-amber-400 py-2 px-2">
                        <div class="flex gap-1">
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-bounce"
                                style="animation-delay: 0ms"></span>
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-bounce"
                                style="animation-delay: 150ms"></span>
                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-bounce"
                                style="animation-delay: 300ms"></span>
                        </div>
                        <span class="text-xs sm:text-sm">Executing command...</span>
                    </div>
                @endif
            </div>

            <!-- Command Input Area -->
            <div class="bg-zinc-900/50 border-t border-zinc-800/50 p-3 sm:p-4">
                @if ($connected)
                    <form wire:submit="executeCommand" class="flex flex-col gap-3" x-data="terminalInput()"
                        @keydown.tab.prevent="handleTab()" @keydown.arrow-up.prevent="previousCommand()"
                        @keydown.arrow-down.prevent="nextCommand()" @keydown.ctrl.c.prevent="handleCtrlC()"
                        @keydown.ctrl.l.prevent="handleCtrlL()">

                        <!-- Input Row -->
                        <div
                            class="flex items-center gap-2 sm:gap-3 bg-zinc-950 rounded-xl border border-zinc-800 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all duration-200 p-2 sm:p-3">
                            <!-- Prompt -->
                            <div class="hidden sm:flex items-center gap-1.5 text-xs sm:text-sm shrink-0 select-none">
                                <span class="text-emerald-400 font-medium">{{ $server->username }}</span>
                                <span class="text-zinc-600">@</span>
                                <span class="text-purple-400">{{ $server->name }}</span>
                                <span class="text-zinc-600">:</span>
                                <span class="text-blue-400">{{ $this->getPrompt() }}</span>
                                <span class="text-zinc-400 font-bold">$</span>
                            </div>
                            <!-- Mobile Prompt -->
                            <div class="sm:hidden flex items-center gap-1 text-xs shrink-0 select-none">
                                <span class="text-emerald-400">$</span>
                            </div>

                            <!-- Input Field -->
                            <input wire:model.live="command" type="text" placeholder="Enter command..."
                                class="flex-1 bg-transparent border-none text-zinc-100 placeholder-zinc-600 font-mono text-sm focus:outline-none focus:ring-0 min-w-0"
                                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                                x-ref="commandInput" :disabled="$wire.isLoading" />

                            <!-- Submit Button -->
                            <button type="submit"
                                class="shrink-0 px-3 sm:px-4 py-2 bg-emerald-500 hover:bg-emerald-400 disabled:bg-zinc-700 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 shadow-lg shadow-emerald-500/20"
                                :disabled="!$wire.connected || $wire.isLoading">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                <span class="hidden sm:inline">Run</span>
                            </button>
                        </div>

                        <!-- Keyboard Shortcuts (Desktop Only) -->
                        <div class="hidden sm:flex items-center justify-between text-xs text-zinc-600">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <kbd
                                        class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700 text-zinc-400 font-sans">Tab</kbd>
                                    <span>Autocomplete</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <kbd
                                        class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700 text-zinc-400 font-sans">↑↓</kbd>
                                    <span>History</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <kbd
                                        class="px-1.5 py-0.5 bg-zinc-800 rounded border border-zinc-700 text-zinc-400 font-sans">Ctrl+L</kbd>
                                    <span>Clear</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-zinc-500">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Secure SSH Connection</span>
                            </div>
                        </div>
                    </form>
                @else
                    <!-- Not Connected State -->
                    <div class="flex flex-col items-center justify-center py-6 sm:py-8">
                        <div
                            class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mb-4 border border-red-500/20">
                            <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <p class="text-zinc-400 text-sm mb-4">Connection lost</p>
                        <button wire:click="initializeConnection"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                            Reconnect
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <style>
        /* Custom scrollbar for terminal */
        #terminal-output::-webkit-scrollbar {
            width: 8px;
        }

        #terminal-output::-webkit-scrollbar-track {
            background: transparent;
        }

        #terminal-output::-webkit-scrollbar-thumb {
            background: rgba(63, 63, 70, 0.5);
            border-radius: 4px;
        }

        #terminal-output::-webkit-scrollbar-thumb:hover {
            background: rgba(82, 82, 91, 0.7);
        }

        /* Smooth animation for command output */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        function terminalInput() {
            return {
                historyIndex: -1,
                tempCommand: '',
                history: @json($this->getCommandHistory()),

                init() {
                    this.$nextTick(() => {
                        this.$refs.commandInput?.focus();
                    });

                    this.$watch('$wire.history', (value) => {
                        this.history = [...value].reverse();
                        this.historyIndex = -1;
                    });
                },

                handleTab() {
                    this.$wire.tabComplete();
                },

                previousCommand() {
                    if (this.history.length === 0) return;

                    if (this.historyIndex === -1) {
                        this.tempCommand = this.$wire.command;
                        this.historyIndex = 0;
                    } else if (this.historyIndex < this.history.length - 1) {
                        this.historyIndex++;
                    }

                    this.$wire.set('command', this.history[this.historyIndex]);
                },

                nextCommand() {
                    if (this.historyIndex === -1) return;

                    if (this.historyIndex > 0) {
                        this.historyIndex--;
                        this.$wire.set('command', this.history[this.historyIndex]);
                    } else {
                        this.historyIndex = -1;
                        this.$wire.set('command', this.tempCommand);
                    }
                },

                handleCtrlC() {
                    this.$wire.set('command', '');
                    this.historyIndex = -1;
                },

                handleCtrlL() {
                    this.$wire.clearOutput();
                }
            };
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('focusInput', () => {
                setTimeout(() => {
                    document.querySelector('input[wire\\:model\\.live="command"]')?.focus();
                }, 100);
            });
        });

        Livewire.hook('morph.updated', ({
            el
        }) => {
            const terminal = document.getElementById('terminal-output');
            if (terminal) {
                terminal.scrollTop = terminal.scrollHeight;
            }
        });
    </script>
</div>
