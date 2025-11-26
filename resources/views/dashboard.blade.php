<x-layouts.app title="Dashboard">
    <div class="min-h-full pb-32 lg:pb-0 bg-black">
        <!-- Header -->
        <header class="flex items-center justify-between px-4 sm:px-8 py-6">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-cyan-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2.5-1.25L12 8.5l-2.5 1.25L12 11zm0 2.5l-5-2.5-5 2.5L12 22l10-8.5-5-2.5-5 2.5z"/></svg>
                <h1 class="text-xl font-bold text-white tracking-tight">FluxSSH</h1>
            </div>
            <button class="p-2 rounded-full bg-zinc-800 text-zinc-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
            </button>
        </header>

        <main class="px-4 sm:px-8 space-y-8">
            <!-- Search Bar -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" 
                       placeholder="Search servers or tags..." 
                       class="w-full bg-[#1c1c1e] border border-white/5 rounded-xl py-3 pl-12 pr-4 text-zinc-300 placeholder-zinc-600 focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all" />
            </div>

            <!-- Server Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Card 1 -->
                <div class="group bg-[#1c1c1e] rounded-xl p-4 border border-white/5 hover:border-cyan-500/30 transition-all cursor-pointer flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded bg-emerald-500 flex items-center justify-center text-black font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm">Web-Prod-01</h3>
                            <p class="text-xs text-zinc-500">192.168.1.100</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <svg class="w-24 h-8 text-emerald-400" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M0 15 L10 12 L20 18 L30 10 L40 20 L50 5 L60 15 L70 12 L80 18 L90 10 L100 15" />
                        </svg>
                        <svg class="w-5 h-5 text-zinc-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group bg-[#1c1c1e] rounded-xl p-4 border border-white/5 hover:border-yellow-500/30 transition-all cursor-pointer flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded bg-yellow-400 flex items-center justify-center text-black font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm">DB-Staging-Alpha</h3>
                            <p class="text-xs text-zinc-500">db-staging.internal.net</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <svg class="w-24 h-8 text-yellow-400" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M0 15 L10 20 L20 10 L30 18 L40 12 L50 22 L60 8 L70 15 L80 12 L90 18 L100 15" />
                        </svg>
                        <svg class="w-5 h-5 text-zinc-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group bg-[#1c1c1e] rounded-xl p-4 border border-white/5 hover:border-pink-500/30 transition-all cursor-pointer flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded bg-pink-500 flex items-center justify-center text-black font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm">API-Gateway-EU</h3>
                            <p class="text-xs text-zinc-500">10.0.5.23</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <svg class="w-24 h-8 text-pink-500" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-dasharray="4 4" d="M0 15 L100 15" />
                        </svg>
                        <svg class="w-5 h-5 text-zinc-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="group bg-[#1c1c1e] rounded-xl p-4 border border-white/5 hover:border-emerald-500/30 transition-all cursor-pointer flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded bg-emerald-500 flex items-center justify-center text-black font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm">Cache-Redis-US</h3>
                            <p class="text-xs text-zinc-500">172.16.0.5</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <svg class="w-24 h-8 text-emerald-400" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M0 20 Q 25 20 50 15 T 100 15" />
                        </svg>
                        <svg class="w-5 h-5 text-zinc-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layouts.app>
