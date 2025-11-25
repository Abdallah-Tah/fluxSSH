@php
    // Theme colors with improved palettes
    $themeColors = match($theme) {
        'retro-green' => [
            'bg' => 'bg-black',
            'text' => 'text-green-400',
            'prompt' => 'text-green-500',
            'selection' => 'selection:bg-green-500/30 selection:text-green-300',
            'border' => 'border-green-500/20',
            'glow' => 'shadow-[0_0_30px_-5px_rgba(34,197,94,0.3)]',
        ],
        'retro-amber' => [
            'bg' => 'bg-[#1a1000]',
            'text' => 'text-amber-400',
            'prompt' => 'text-amber-500',
            'selection' => 'selection:bg-amber-500/30 selection:text-amber-300',
            'border' => 'border-amber-500/20',
            'glow' => 'shadow-[0_0_30px_-5px_rgba(245,158,11,0.3)]',
        ],
        'cyberpunk' => [
            'bg' => 'bg-[#09090b]',
            'text' => 'text-cyan-300',
            'prompt' => 'text-fuchsia-400',
            'selection' => 'selection:bg-cyan-500/30 selection:text-cyan-200',
            'border' => 'border-cyan-500/20',
            'glow' => 'shadow-[0_0_30px_-5px_rgba(6,182,212,0.3)]',
        ],
        default => [
            'bg' => 'bg-zinc-950',
            'text' => 'text-zinc-200',
            'prompt' => 'text-emerald-400',
            'selection' => 'selection:bg-emerald-500/30 selection:text-emerald-200',
            'border' => 'border-zinc-800',
            'glow' => 'shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]',
        ],
    };
@endphp

<div class="fixed inset-0 flex flex-col {{ $themeColors['bg'] }} {{ $themeColors['selection'] }} transition-colors duration-500 overflow-hidden" 
     x-data="{ 
        showSearch: false, 
        searchQuery: @entangle('searchQuery'),
        mobileMenuOpen: false,
        toggleMobileMenu() { this.mobileMenuOpen = !this.mobileMenuOpen }
     }">
    
    <!-- Ambient Background Effects -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-emerald-500/5 blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[100px]"></div>
    </div>

    <!-- Top Navigation Bar -->
    <header class="relative z-50 shrink-0 bg-white/5 backdrop-blur-xl border-b border-white/5">
        <div class="px-4 h-16 flex items-center justify-between gap-4">
            <!-- Left: Server Info -->
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('servers') }}" class="shrink-0 p-2 -ml-2 rounded-xl text-zinc-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
                
                <div class="flex items-center gap-3 min-w-0">
                    <div class="relative shrink-0">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-zinc-800 to-zinc-900 border border-white/10 flex items-center justify-center shadow-lg">
                            <svg class="w-4 h-4 {{ $themeColors['prompt'] }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full border-2 border-[#09090b] {{ $connected ? 'bg-emerald-500' : 'bg-red-500' }}">
                            @if($connected)
                                <div class="absolute inset-0 rounded-full bg-emerald-500 animate-ping opacity-75"></div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex flex-col min-w-0">
                        <h1 class="text-sm font-bold text-white truncate leading-tight">{{ $server->name }}</h1>
                        <div class="flex items-center gap-1.5 text-[10px] font-medium text-zinc-400 font-mono leading-tight truncate">
                            <span class="{{ $themeColors['prompt'] }}">{{ $server->username }}</span>
                            <span>@</span>
                            <span>{{ $server->host }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-1">
                <!-- Desktop Actions -->
                <div class="hidden sm:flex items-center gap-1">
                    <button wire:click="toggleTheme" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors" title="Change Theme">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                        </svg>
                    </button>
                    <button @click="showSearch = !showSearch" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors" title="Search">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                    <div class="w-px h-6 bg-white/10 mx-1"></div>
                    <button wire:click="initializeConnection" class="p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors {{ $isLoading ? 'animate-spin' : '' }}" title="Reconnect">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu Toggle -->
                <button @click="toggleMobileMenu()" class="sm:hidden p-2 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="absolute top-full left-0 right-0 bg-[#09090b] border-b border-white/10 p-4 space-y-2 sm:hidden shadow-2xl">
            
            <div class="grid grid-cols-4 gap-2">
                <button wire:click="toggleTheme" class="flex flex-col items-center gap-1 p-3 rounded-xl bg-white/5 text-zinc-400 hover:text-white hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                    </svg>
                    <span class="text-[10px]">Theme</span>
                </button>
                <button @click="showSearch = !showSearch; mobileMenuOpen = false" class="flex flex-col items-center gap-1 p-3 rounded-xl bg-white/5 text-zinc-400 hover:text-white hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <span class="text-[10px]">Search</span>
                </button>
                <button wire:click="clearOutput" class="flex flex-col items-center gap-1 p-3 rounded-xl bg-white/5 text-zinc-400 hover:text-white hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <span class="text-[10px]">Clear</span>
                </button>
                <button wire:click="initializeConnection" class="flex flex-col items-center gap-1 p-3 rounded-xl bg-white/5 text-zinc-400 hover:text-white hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span class="text-[10px]">Reset</span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div x-show="showSearch" x-transition class="border-t border-white/5 bg-black/20 p-2">
            <div class="relative max-w-2xl mx-auto">
                <input x-model="searchQuery" type="text" placeholder="Search output..." 
                       class="w-full bg-white/5 text-white placeholder-zinc-500 border border-white/10 rounded-lg px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Terminal Area -->
    <main class="flex-1 flex flex-col min-h-0 relative z-0">
        <!-- Terminal Output -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 font-mono text-sm {{ $themeColors['text'] }} scroll-smooth custom-scrollbar"
             id="terminal-output"
             style="{{ $theme === 'retro-green' || $theme === 'retro-amber' ? 'text-shadow: 0 0 5px currentColor;' : '' }}"
             x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
             x-init="scrollToBottom(); $watch('$wire.outputCount', () => setTimeout(() => scrollToBottom(), 50))"
             @scroll-terminal.window="scrollToBottom()">

            <!-- CRT Effects -->
            @if($theme === 'retro-green' || $theme === 'retro-amber')
                <div class="fixed inset-0 pointer-events-none opacity-[0.03] z-10" style="background: repeating-linear-gradient(0deg, transparent, transparent 2px, currentColor 2px, currentColor 4px);"></div>
                <div class="fixed inset-0 pointer-events-none opacity-[0.04] z-10 bg-[radial-gradient(circle_at_center,transparent_50%,rgba(0,0,0,0.4)_100%)]"></div>
            @endif

            <div class="max-w-5xl mx-auto space-y-1">
                @forelse ($output as $index => $line)
                    <div wire:key="output-{{ $outputCount }}-{{ $index }}"
                         class="group flex gap-4 py-0.5 -mx-2 px-2 rounded hover:bg-white/5 transition-colors">
                        <span class="hidden sm:block text-zinc-600 text-xs shrink-0 w-16 opacity-0 group-hover:opacity-100 transition-opacity select-none pt-0.5 font-mono">
                            {{ $line['timestamp'] }}
                        </span>
                        <pre class="{{ $this->getLineClass($line['type']) }} whitespace-pre-wrap break-words flex-1 text-sm leading-relaxed font-mono tracking-tight">{{ $line['text'] }}</pre>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-[60vh]">
                        <div class="w-24 h-24 rounded-3xl bg-white/5 flex items-center justify-center mb-8 border border-white/5 {{ $themeColors['glow'] }} animate-pulse">
                            <svg class="w-12 h-12 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-zinc-300 mb-2">FluxSSH Terminal</h3>
                        <p class="text-zinc-500 text-sm text-center max-w-xs">
                            {{ $connected ? 'Ready for commands.' : 'Connecting to ' . $server->host . '...' }}
                        </p>
                    </div>
                @endforelse

                @if ($isLoading && count($output) > 0)
                    <div class="flex items-center gap-3 py-2 {{ $themeColors['text'] }} opacity-70">
                        <div class="flex gap-1.5">
                            <span class="w-1.5 h-1.5 {{ $themeColors['prompt'] }} rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-1.5 h-1.5 {{ $themeColors['prompt'] }} rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-1.5 h-1.5 {{ $themeColors['prompt'] }} rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Input Area -->
        <div class="shrink-0 p-4 bg-white/5 backdrop-blur-md border-t border-white/5 z-20">
            <div class="max-w-5xl mx-auto">
                @if ($connected)
                    <form wire:submit="executeCommand" x-data="terminalInput()"
                          @keydown.tab.prevent="handleTab()"
                          @keydown.arrow-up.prevent="previousCommand()"
                          @keydown.arrow-down.prevent="nextCommand()"
                          @keydown.ctrl.c.prevent="handleCtrlC()"
                          @keydown.ctrl.l.prevent="handleCtrlL()">
                        <div class="flex items-center gap-3 bg-black/40 rounded-2xl border border-white/10 focus-within:border-emerald-500/50 focus-within:ring-2 focus-within:ring-emerald-500/20 transition-all p-1.5 pl-4 shadow-xl">
                            <!-- Prompt -->
                            <div class="hidden sm:flex items-center gap-1.5 text-sm shrink-0 select-none font-mono">
                                <span class="{{ $themeColors['prompt'] }} font-bold">{{ $server->username }}</span>
                                <span class="text-zinc-600">@</span>
                                <span class="text-zinc-400 font-bold">{{ $server->name }}</span>
                                <span class="{{ $themeColors['prompt'] }} font-bold text-lg leading-none ml-1">$</span>
                            </div>
                            <div class="sm:hidden {{ $themeColors['prompt'] }} font-bold text-lg leading-none">$</div>

                            <!-- Input -->
                            <input wire:model.live="command" type="text"
                                   class="flex-1 bg-transparent border-none {{ $themeColors['text'] }} placeholder-zinc-600 font-mono text-sm focus:outline-none focus:ring-0 p-2"
                                   placeholder="Enter command..."
                                   autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                                   x-ref="commandInput" :disabled="$wire.isLoading" />

                            <!-- Submit Button -->
                            <button type="submit"
                                    class="shrink-0 p-2.5 bg-emerald-500 hover:bg-emerald-400 disabled:bg-zinc-800 disabled:text-zinc-500 disabled:cursor-not-allowed text-white rounded-xl transition-all shadow-lg shadow-emerald-500/20"
                                    :disabled="!$wire.connected || $wire.isLoading">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex justify-center">
                        <button wire:click="initializeConnection" class="w-full sm:w-auto px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-white text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reconnect to Server
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Styles & Scripts -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        function terminalInput() {
            return {
                historyIndex: -1,
                tempCommand: '',
                history: @json($this->getCommandHistory()),
                tabPressCount: 0,
                lastTabTime: 0,

                init() {
                    this.$nextTick(() => {
                        this.$refs.commandInput?.focus();
                    });

                    this.$watch('$wire.history', (value) => {
                        this.history = [...value].reverse();
                        this.historyIndex = -1;
                    });

                    this.$watch('$wire.command', () => {
                        this.tabPressCount = 0;
                    });
                },

                handleTab() {
                    const now = Date.now();
                    if (now - this.lastTabTime < 500) {
                        this.tabPressCount++;
                    } else {
                        this.tabPressCount = 1;
                    }
                    this.lastTabTime = now;
                    this.$wire.tabComplete();
                },

                previousCommand() {
                    if (this.history.length === 0) return;
                    if (this.historyIndex === -1) {
                        this.tempCommand = this.$wire.command || '';
                        this.historyIndex = 0;
                    } else if (this.historyIndex < this.history.length - 1) {
                        this.historyIndex++;
                    }
                    this.$wire.set('command', this.history[this.historyIndex] || '');
                },

                nextCommand() {
                    if (this.historyIndex === -1) return;
                    if (this.historyIndex > 0) {
                        this.historyIndex--;
                        this.$wire.set('command', this.history[this.historyIndex] || '');
                    } else {
                        this.historyIndex = -1;
                        this.$wire.set('command', this.tempCommand || '');
                    }
                },

                handleCtrlC() {
                    this.$wire.set('command', '');
                    this.historyIndex = -1;
                    this.tabPressCount = 0;
                },

                handleCtrlL() {
                    this.$wire.clearOutput();
                }
            };
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('focusInput', () => {
                setTimeout(() => {
                    const input = document.querySelector('input[wire\\:model\\.live="command"]');
                    if (input) {
                        input.focus();
                        input.setSelectionRange(input.value.length, input.value.length);
                    }
                }, 100);
            });

            Livewire.on('output-added', (event) => {
                const terminal = document.getElementById('terminal-output');
                if (!terminal) return;

                const data = event[0];
                const output = data.output;
                const count = data.count;

                const lineDiv = document.createElement('div');
                lineDiv.className = 'group flex gap-4 py-0.5 -mx-2 px-2 rounded hover:bg-white/5 transition-colors';
                lineDiv.setAttribute('data-output-id', count);

                const timestampSpan = document.createElement('span');
                timestampSpan.className = 'hidden sm:block text-zinc-600 text-xs shrink-0 w-16 opacity-0 group-hover:opacity-100 transition-opacity select-none pt-0.5 font-mono';
                timestampSpan.textContent = output.timestamp;

                const outputPre = document.createElement('pre');
                outputPre.className = `whitespace-pre-wrap break-words flex-1 text-sm leading-relaxed font-mono tracking-tight ${getLineClass(output.type)}`;
                outputPre.textContent = output.text;

                lineDiv.appendChild(timestampSpan);
                lineDiv.appendChild(outputPre);

                const emptyState = terminal.querySelector('.flex.flex-col.items-center.justify-center');
                if (emptyState) {
                    emptyState.remove();
                }

                terminal.querySelector('.max-w-5xl').appendChild(lineDiv);
                setTimeout(() => {
                    terminal.scrollTop = terminal.scrollHeight;
                }, 10);
            });

            function getLineClass(type) {
                const classes = {
                    'command': 'text-cyan-400 font-bold',
                    'output': 'text-zinc-200',
                    'error': 'text-red-400 font-medium',
                    'success': 'text-emerald-400 font-medium',
                    'info': 'text-sky-400',
                };
                return classes[type] || 'text-zinc-300';
            }
        });
    </script>
</div>
