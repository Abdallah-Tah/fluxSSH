<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #3f3f46;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #52525b;
        }
        
        /* Mobile Bottom Nav Safe Area */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>

<body class="min-h-screen bg-black font-sans antialiased text-white selection:bg-blue-500/30 selection:text-blue-400">
    <!-- Desktop Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-[#09090b] border-r border-white/5 hidden lg:flex lg:flex-col">
        <!-- Logo -->
        <div class="flex h-20 shrink-0 items-center px-6 border-b border-white/5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-white">FluxSSH <span class="text-lg">🔥</span></span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8">
            <div>
                <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">Menu</h3>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-500/10 text-blue-400' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('servers') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('servers') || request()->routeIs('server.*') ? 'bg-blue-500/10 text-blue-400' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                        </svg>
                        Servers
                    </a>
                </div>
            </div>

            <div>
                <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">Configuration</h3>
                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-blue-500/10 text-blue-400' : 'text-zinc-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Section -->
        <div class="border-t border-white/5 p-4">
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/5 transition-all duration-200 cursor-pointer group">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-zinc-800 to-zinc-700 flex items-center justify-center text-sm font-bold text-white shadow-sm">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate group-hover:text-blue-400 transition-colors">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="lg:hidden sticky top-0 z-40 bg-black/80 backdrop-blur-xl border-b border-white/5 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-xl font-bold text-white">FluxSSH <span class="text-lg">🔥</span></span>
        </div>
        <div class="h-8 w-8 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-white">
            {{ auth()->user()->initials() }}
        </div>
    </div>

    <!-- Main Content -->
    <main class="lg:pl-72 min-h-screen pb-32 lg:pb-0">
        {{ $slot }}
    </main>

    <!-- Global Floating Bottom Navigation (Mobile Only) -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-[90%] max-w-md px-6 py-3 bg-[#1c1c1e]/90 backdrop-blur-xl border border-white/10 rounded-full flex items-center justify-between shadow-2xl z-50 lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-cyan-400' : 'text-zinc-500 hover:text-white' }} transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
            <span class="text-[10px] font-medium">Dashboard</span>
        </a>
        <a href="{{ route('servers') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('servers') ? 'text-cyan-400' : 'text-zinc-500 hover:text-white' }} transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" /></svg>
            <span class="text-[10px] font-medium">Servers</span>
        </a>
        
        <!-- Add Button (Links to Servers page for now) -->
        <a href="{{ route('servers') }}" class="relative -top-6 bg-gradient-to-tr from-cyan-500 to-fuchsia-500 w-14 h-14 rounded-full flex items-center justify-center text-white shadow-lg shadow-cyan-500/30 hover:scale-105 transition-transform shrink-0">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </a>

        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('profile.edit') ? 'text-cyan-400' : 'text-zinc-500 hover:text-white' }} transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
            <span class="text-[10px] font-medium">Settings</span>
        </a>
    </div>

    @fluxScripts
</body>

</html>
