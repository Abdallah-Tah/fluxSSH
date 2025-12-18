<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-bind:class="$flux.appearance === 'system' ? '' : $flux.appearance">

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
            background: var(--color-border-strong);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-text-tertiary);
        }
        
        /* Mobile Bottom Nav Safe Area */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>

<body class="min-h-screen bg-bg-app font-sans antialiased text-text-primary selection:bg-primary-500/20 selection:text-primary-600 transition-colors duration-300">
    <!-- Desktop Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-bg-surface border-r border-border-subtle hidden lg:flex lg:flex-col">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center px-6 border-b border-border-subtle">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="relative w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center shadow-sm shadow-primary-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold tracking-tight text-text-primary">FluxSSH</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-8">
            <div>
                <h3 class="px-3 mb-2 text-[11px] font-mono uppercase tracking-wider text-text-tertiary">Platform</h3>
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('servers') }}"
                        class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('servers') || request()->routeIs('server.*') ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z" />
                        </svg>
                        Servers
                    </a>
                </div>
            </div>

            <div>
                <h3 class="px-3 mb-2 text-[11px] font-mono uppercase tracking-wider text-text-tertiary">Settings</h3>
                <div class="space-y-0.5">
                    <a href="{{ route('profile.edit') }}"
                        class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-primary-subtle text-primary-600' : 'text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.149-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Configuration
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Section -->
        <div class="border-t border-border-subtle p-4 space-y-2">
            <div class="flex items-center gap-3 p-2 rounded-md hover:bg-bg-surface-alt transition-all duration-200 cursor-pointer group">
                <div class="h-8 w-8 rounded-md bg-bg-surface-alt flex items-center justify-center text-xs font-bold text-text-secondary group-hover:text-text-primary">
                    {{ auth()->user()?->initials() ?? '?' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-text-primary truncate">
                        {{ auth()->user()?->name ?? 'Guest' }}
                    </p>
                    <p class="text-[11px] text-text-tertiary truncate">{{ auth()->user()?->email ?? 'Not logged in' }}</p>
                </div>
            </div>

            @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-text-secondary hover:text-text-primary hover:bg-bg-surface-alt rounded-md transition-all duration-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    Logout
                </button>
            </form>
            @endauth
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="lg:hidden sticky top-0 z-40 bg-bg-surface/80 backdrop-blur-xl border-b border-border-subtle px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-text-primary">FluxSSH</span>
        </div>
        <div class="h-8 w-8 rounded-full bg-bg-surface-alt flex items-center justify-center text-xs font-bold text-text-primary">
            {{ auth()->user()?->initials() ?? '?' }}
        </div>
    </div>

    <!-- Main Content -->
    <main class="lg:pl-72 min-h-screen pb-32 lg:pb-0 bg-bg-app">
        {{ $slot }}
    </main>

    <!-- Global Floating Bottom Navigation (Mobile Only) -->
    @unless(request()->is('console/*'))
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-md px-6 py-3 bg-bg-surface/90 backdrop-blur-xl border border-border-subtle rounded-full flex items-center justify-between shadow-2xl z-50 lg:hidden">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-text-tertiary hover:text-text-primary' }} transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
        </a>
        <a href="{{ route('servers') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('servers') ? 'text-primary-500' : 'text-text-tertiary hover:text-text-primary' }} transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.1 7.5" /></svg>
        </a>
        
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('profile.edit') ? 'text-primary-500' : 'text-text-tertiary hover:text-text-primary' }} transition-colors">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.149-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
        </a>
    </div>
    @endunless

    @fluxScripts
    @stack('scripts')
</body>

</html>
