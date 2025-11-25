<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 hidden lg:flex lg:flex-col">
            <div class="flex h-16 shrink-0 items-center px-6">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold text-zinc-900 dark:text-white">
                    <x-app-logo class="h-8 w-8" />
                    <span>FluxSSH</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-6 py-4">
                <ul role="list" class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('dashboard') ? 'bg-zinc-200 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-700 hover:text-zinc-900 hover:bg-zinc-200 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800' }}">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Overview
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-zinc-700 hover:text-zinc-900 hover:bg-zinc-200 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.25l.07-.071a1.125 1.125 0 011.664.981C13.925 9.66 13.5 9 13.5 9m-9.75 0h9.75" />
                            </svg>
                            Servers
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold text-zinc-700 hover:text-zinc-900 hover:bg-zinc-200 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800">
                            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                            </svg>
                            Activity
                        </a>
                    </li>
                </ul>

                <div class="mt-8">
                    <h3 class="px-2 text-xs font-semibold leading-6 text-zinc-500 dark:text-zinc-400">Settings</h3>
                    <ul role="list" class="mt-2 space-y-1">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('profile.edit') ? 'bg-zinc-200 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-700 hover:text-zinc-900 hover:bg-zinc-200 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-800' }}">
                                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="border-t border-zinc-200 p-4 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-sm font-medium text-zinc-900 dark:text-white">
                        {{ auth()->user()->initials() }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden dark:bg-zinc-900">
            <button type="button" class="-m-2.5 p-2.5 text-zinc-700 lg:hidden dark:text-zinc-400">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <div class="flex-1 text-sm font-semibold leading-6 text-zinc-900 dark:text-white">Dashboard</div>
            <a href="#">
                <span class="sr-only">Your profile</span>
                <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-medium text-zinc-900 dark:text-white">
                    {{ auth()->user()->initials() }}
                </div>
            </a>
        </div>

        <main class="lg:pl-64 h-full">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>
