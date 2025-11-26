<div class="space-y-8">
    <div class="text-center sm:text-left">
        <h2 class="text-3xl font-bold text-white tracking-tight">New Connection</h2>
        <p class="mt-2 text-zinc-400">Configure the details for your new secure shell connection.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Connection Name -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-zinc-300">Connection Name</label>
            <input wire:model="name" type="text" placeholder="My Production Server"
                class="w-full bg-[#1c1c1e] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all" />
        </div>

        <!-- Hostname -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-zinc-300">Hostname / IP</label>
            <input wire:model="host" type="text" placeholder="192.168.1.1"
                class="w-full bg-[#1c1c1e] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all font-mono" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Port -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-zinc-300">Port</label>
                <input wire:model="port" type="number" placeholder="22"
                    class="w-full bg-[#1c1c1e] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all font-mono" />
            </div>

            <!-- User -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-zinc-300">User</label>
                <input wire:model="username" type="text" placeholder="root"
                    class="w-full bg-[#1c1c1e] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all font-mono" />
            </div>
        </div>

        <!-- Authentication Method -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-zinc-300">Authentication Method</label>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" wire:click="$set('auth_type', 'password')"
                    class="px-4 py-3 rounded-xl border transition-all {{ $auth_type === 'password' ? 'bg-[#1c1c1e] border-cyan-500/50 text-white' : 'bg-[#1c1c1e] border-white/10 text-zinc-500 hover:text-zinc-300' }}">
                    Password
                </button>
                <button type="button" wire:click="$set('auth_type', 'key')"
                    class="px-4 py-3 rounded-xl border transition-all {{ $auth_type === 'key' ? 'bg-cyan-900/20 border-cyan-500 text-cyan-400' : 'bg-[#1c1c1e] border-white/10 text-zinc-500 hover:text-zinc-300' }}">
                    SSH Key
                </button>
            </div>
        </div>

        <!-- Key Selection Carousel -->
        @if ($auth_type === 'key')
            <div class="space-y-2 animate-fade-in">
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-zinc-300">Select Key</label>
                    <div class="flex gap-2">
                        <button type="button" class="p-1 rounded-full bg-[#1c1c1e] border border-white/10 text-zinc-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        </button>
                        <button type="button" class="p-1 rounded-full bg-[#1c1c1e] border border-white/10 text-zinc-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                    <!-- Key Card 1 (Active) -->
                    <div class="shrink-0 w-48 p-4 rounded-xl bg-gradient-to-br from-cyan-900/20 to-blue-900/20 border border-cyan-500/50 cursor-pointer relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-20"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-white">Workstation-Main</h4>
                                <svg class="w-5 h-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                            </div>
                            <p class="text-[10px] text-zinc-500 font-mono truncate">SHA256:aBcDeFg...</p>
                        </div>
                    </div>

                    <!-- Key Card 2 -->
                    <div class="shrink-0 w-48 p-4 rounded-xl bg-[#1c1c1e] border border-white/10 cursor-pointer relative overflow-hidden group hover:border-white/20 transition-colors">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-10"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-zinc-300">Personal-Laptop</h4>
                                <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                            </div>
                            <p class="text-[10px] text-zinc-600 font-mono truncate">SHA256:xYzAbCd...</p>
                        </div>
                    </div>

                    <!-- Key Card 3 -->
                    <div class="shrink-0 w-48 p-4 rounded-xl bg-[#1c1c1e] border border-white/10 cursor-pointer relative overflow-hidden group hover:border-white/20 transition-colors">
                        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-10"></div>
                        <div class="relative z-10 flex flex-col h-full justify-between gap-4">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-zinc-300">Cloud-Server</h4>
                                <svg class="w-5 h-5 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                            </div>
                            <p class="text-[10px] text-zinc-600 font-mono truncate">SHA256:123456...</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-2 animate-fade-in">
                <input wire:model="password" type="password" placeholder="Enter Password"
                    class="w-full bg-[#1c1c1e] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all" />
            </div>
        @endif

        <button type="submit"
            class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all hover:shadow-[0_0_30px_rgba(37,99,235,0.5)] active:scale-[0.98]">
            Establish Connection
        </button>
    </form>
</div>
