<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 font-sans antialiased selection:bg-emerald-500/30 selection:text-emerald-500">
    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 glass border-r border-zinc-200/50 dark:border-zinc-800/50 hidden lg:flex lg:flex-col transition-all duration-300">
        <!-- Logo -->
        <div class="flex h-20 shrink-0 items-center px-6 border-b border-zinc-100/50 dark:border-zinc-800/50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div
                    class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 group-hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <svg class="w-6 h-6 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold tracking-tight text-zinc-900 dark:text-white group-hover:text-emerald-500 dark:group-hover:text-emerald-400 transition-colors">FluxSSH</span>
                    <span class="text-[10px] uppercase tracking-wider font-semibold text-zinc-400 dark:text-zinc-500">Manager</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8">
            <!-- Main Menu -->
            <div>
                <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                    Menu</h3>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400 shadow-sm ring-1 ring-emerald-200/50 dark:ring-emerald-500/20' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800/50' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-zinc-100 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 group-hover:text-zinc-700 dark:group-hover:text-zinc-200' }} transition-colors duration-200">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                        Overview
                    </a>

                    <a href="{{ route('servers') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('servers') || request()->routeIs('server.*') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400 shadow-sm ring-1 ring-emerald-200/50 dark:ring-emerald-500/20' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800/50' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('servers') || request()->routeIs('server.*') ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-zinc-100 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 group-hover:text-zinc-700 dark:group-hover:text-zinc-200' }} transition-colors duration-200">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                        </div>
                        Servers
                    </a>

                    <a href="#"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800/50">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-zinc-100 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 group-hover:text-zinc-700 dark:group-hover:text-zinc-200 transition-colors duration-200">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                            </svg>
                        </div>
                        Activity
                    </a>
                </div>
            </div>

            <!-- Settings Section -->
            <div>
                <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                    Configuration</h3>
                <div class="space-y-1">
                    <a href="{{ route('profile.edit') }}"
                        class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ request()->routeIs('profile.edit') || request()->routeIs('user-password.edit') || request()->routeIs('two-factor.show') || request()->routeIs('appearance.edit') || request()->routeIs('api-keys.index') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400 shadow-sm ring-1 ring-emerald-200/50 dark:ring-emerald-500/20' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-50 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800/50' }}">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg {{ request()->routeIs('profile.edit') || request()->routeIs('user-password.edit') || request()->routeIs('two-factor.show') || request()->routeIs('appearance.edit') || request()->routeIs('api-keys.index') ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-zinc-100 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 group-hover:bg-zinc-200 dark:group-hover:bg-zinc-700 group-hover:text-zinc-700 dark:group-hover:text-zinc-200' }} transition-colors duration-200">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        Settings
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Section -->
        <div class="border-t border-zinc-100 dark:border-zinc-800/50 p-4">
            <div
                class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-all duration-200 cursor-pointer group border border-transparent hover:border-zinc-200 dark:hover:border-zinc-700/50">
                <div
                    class="h-10 w-10 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center text-sm font-bold text-zinc-600 dark:text-zinc-300 shadow-sm group-hover:scale-105 transition-transform duration-200">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-zinc-900 dark:text-white truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <svg class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                </svg>
            </div>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
        <div
            class="sticky top-0 z-40 flex items-center gap-x-4 glass px-4 py-4 border-b border-zinc-200/50 dark:border-zinc-800/50">
            <button @click="mobileMenuOpen = true" type="button"
                class="p-2 -ml-2 rounded-xl text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800 transition-colors">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <div class="flex-1 flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md shadow-emerald-500/20">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <span class="font-bold text-zinc-900 dark:text-white tracking-tight">FluxSSH</span>
            </div>
            <a href="{{ route('profile.edit') }}" class="p-1">
                <div
                    class="h-8 w-8 rounded-xl bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-700 dark:text-zinc-200 shadow-sm">
                    {{ auth()->user()->initials() }}
                </div>
            </a>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-zinc-900/80 backdrop-blur-sm"
            @click="mobileMenuOpen = false">
        </div>

        <!-- Mobile Menu Panel -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-zinc-950 shadow-2xl border-r border-zinc-200/50 dark:border-zinc-800/50">
            <div class="flex h-20 items-center justify-between px-6 border-b border-zinc-100 dark:border-zinc-800/50">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 font-bold text-zinc-900 dark:text-white">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <span class="text-lg tracking-tight">FluxSSH</span>
                </a>
                <button @click="mobileMenuOpen = false"
                    class="p-2 rounded-xl text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="px-4 py-6 space-y-6 overflow-y-auto h-[calc(100vh-5rem)]">
                <div>
                    <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                        Menu</h3>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Overview
                        </a>
                        <a href="{{ route('servers') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('servers') || request()->routeIs('server.*') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" />
                            </svg>
                            Servers
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="px-3 mb-3 text-xs font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                        Configuration</h3>
                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium {{ request()->routeIs('profile.edit') || request()->routeIs('user-password.edit') || request()->routeIs('two-factor.show') || request()->routeIs('appearance.edit') || request()->routeIs('api-keys.index') ? 'bg-emerald-50/80 text-emerald-900 dark:bg-emerald-500/10 dark:text-emerald-400' : 'text-zinc-600 dark:text-zinc-400' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <main class="lg:pl-64 min-h-screen transition-all duration-300">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>
