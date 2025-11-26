@php
    // Theme colors matching the screenshot (Cyan/Teal aesthetic)
    $themeColors = [
        'bg' => 'bg-[#09090b]',
        'text' => 'text-cyan-400',
        'prompt' => 'text-cyan-500',
        'selection' => 'selection:bg-cyan-500/30 selection:text-cyan-300',
        'border' => 'border-white/10',
    ];
@endphp

<div class="fixed inset-0 flex flex-col {{ $themeColors['bg'] }} {{ $themeColors['selection'] }} font-mono overflow-hidden" 
     x-data="{ 
        showSearch: false, 
        searchQuery: @entangle('searchQuery'),
        mobileMenuOpen: false
     }">
    
    <!-- Top Bar (Minimal) -->
    <header class="relative z-50 shrink-0 flex items-center justify-between px-4 py-3 bg-transparent">
        <div class="text-xs text-zinc-500">24ms</div>
        <div class="text-sm font-bold text-zinc-400">{{ $server->name }}</div>
        <a href="{{ route('servers') }}" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </header>

    <!-- Main Terminal Area -->
    <main class="flex-1 flex flex-col min-h-0 relative z-0 px-4 sm:px-6">
        <!-- Terminal Output -->
        <div class="flex-1 overflow-y-auto py-4 {{ $themeColors['text'] }} scroll-smooth custom-scrollbar"
             id="terminal-output"
             x-data="{ scrollToBottom() { this.$el.scrollTop = this.$el.scrollHeight; } }"
             x-init="scrollToBottom(); $watch('$wire.outputCount', () => setTimeout(() => scrollToBottom(), 50))"
             @scroll-terminal.window="scrollToBottom()">

            <div class="max-w-4xl mx-auto space-y-1">
                <!-- Welcome Message -->
                <div class="mb-6 text-cyan-400">
                    <p>Last login: {{ now()->subDays(2)->format('D M d H:i:s Y') }} from 192.168.1.100</p>
                    <p>FluxSSH Welcome Message: Connected to {{ $server->name }}.</p>
                </div>

                @foreach ($output as $line)
                    <div class="whitespace-pre-wrap break-words text-sm leading-relaxed tracking-tight {{ $line['type'] === 'command' ? 'text-white font-bold mt-4' : 'text-cyan-400' }}">
                        @if($line['type'] === 'command')
                            <span class="text-zinc-500 mr-2">{{ $server->username }}@hostname:~$</span>{{ $line['text'] }}
                        @else
                            {!! $line['text'] !!}
                        @endif
                    </div>
                @endforeach

                <!-- Active Prompt -->
                <div class="flex items-center gap-2 mt-4 text-sm">
                    <span class="text-zinc-500">{{ $server->username }}@hostname:~$</span>
                    <div class="relative flex-1">
                        <input wire:model.live="command" 
                               wire:keydown.enter="executeCommand"
                               type="text" 
                               class="w-full bg-transparent border-none p-0 text-white focus:ring-0 focus:outline-none placeholder-zinc-700"
                               autofocus />
                        <span class="absolute top-0 left-[calc(100%+2px)] w-2 h-5 bg-cyan-500 animate-pulse" style="left: {{ strlen($command) * 8 }}px"></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bottom Control Bar -->
    <div class="shrink-0 p-4 sm:p-6 z-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-[#1c1c1e] rounded-2xl border border-white/10 p-2 px-4 shadow-2xl overflow-x-auto scrollbar-hide">
                <div class="flex items-center justify-between min-w-[320px] gap-4">
                    <!-- Left Controls -->
                    <div class="flex items-center gap-4 sm:gap-6 text-cyan-400 font-bold text-xs tracking-wider shrink-0">
                        <button class="hover:text-cyan-300 transition-colors">ESC</button>
                        <button class="hover:text-cyan-300 transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                            I
                        </button>
                        <button class="hover:text-cyan-300 transition-colors">CTRL</button>
                        <button class="hover:text-cyan-300 transition-colors">ALT</button>
                        <div class="w-px h-4 bg-white/10"></div>
                        <button class="hover:text-cyan-300 transition-colors text-lg leading-none">/</button>
                        <button class="hover:text-cyan-300 transition-colors text-lg leading-none">-</button>
                    </div>

                    <!-- Center Arrows -->
                    <div class="flex items-center gap-3 sm:gap-4 text-cyan-400 shrink-0">
                        <button class="hover:text-cyan-300 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg></button>
                        <button class="hover:text-cyan-300 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg></button>
                        <button class="hover:text-cyan-300 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg></button>
                        <button class="hover:text-cyan-300 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg></button>
                    </div>

                    <!-- Magic Button -->
                    <button class="flex items-center gap-2 px-4 py-2 bg-fuchsia-500 hover:bg-fuchsia-400 text-black font-bold rounded-lg transition-all shadow-[0_0_15px_rgba(217,70,239,0.4)] hover:shadow-[0_0_25px_rgba(217,70,239,0.6)] shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                        Magic
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #52525b; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
