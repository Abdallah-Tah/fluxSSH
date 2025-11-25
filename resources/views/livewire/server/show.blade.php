<div class="flex h-full flex-col">
    <!-- Header -->
    <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-800">
                    <svg class="h-6 w-6 text-zinc-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.25l.07-.071a1.125 1.125 0 011.664.981C13.925 9.66 13.5 9 13.5 9m-9.75 0h9.75" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $server->name }}</h1>
                        @if ($server->is_active)
                            <span
                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/30 dark:text-green-400">Online</span>
                        @else
                            <span
                                class="inline-flex items-center rounded-md bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10 dark:bg-zinc-400/10 dark:text-zinc-400 dark:ring-zinc-400/20">Offline</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
                        <span class="font-mono">{{ $server->ip_address }}</span>
                        <span>&bull;</span>
                        <span>{{ $server->region ?? 'NYC1' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('console', $server) }}"
                    class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:hover:bg-zinc-700">
                    <svg class="mr-1.5 h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Console
                </a>
                <button type="button"
                    class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-zinc-600 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                    Deploy
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="mt-6 flex gap-6 border-b border-zinc-200 dark:border-zinc-700">
            <button
                class="border-b-2 border-zinc-900 pb-3 text-sm font-medium text-zinc-900 dark:border-white dark:text-white">Overview</button>
            <button
                class="border-b-2 border-transparent pb-3 text-sm font-medium text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-300">Logs</button>
            <button
                class="border-b-2 border-transparent pb-3 text-sm font-medium text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-300">Activity</button>
            <button
                class="border-b-2 border-transparent pb-3 text-sm font-medium text-zinc-500 hover:border-zinc-300 hover:text-zinc-700 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:text-zinc-300">Settings</button>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto bg-zinc-50 p-6 dark:bg-zinc-900/50">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                        <dt class="truncate text-sm font-medium text-zinc-500 dark:text-zinc-400">CPU Usage</dt>
                        <dd class="mt-2 flex items-baseline gap-2">
                            <span
                                class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $server->cpu_usage ?? 0 }}%</span>
                            <span class="text-sm text-green-600 dark:text-green-400">
                                <svg class="inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                                2.5%
                            </span>
                        </dd>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                        <dt class="truncate text-sm font-medium text-zinc-500 dark:text-zinc-400">Memory</dt>
                        <dd class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-semibold text-zinc-900 dark:text-white">4.2 GB</span>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">/ 8 GB</span>
                        </dd>
                    </div>
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                        <dt class="truncate text-sm font-medium text-zinc-500 dark:text-zinc-400">Disk</dt>
                        <dd class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-semibold text-zinc-900 dark:text-white">45 GB</span>
                            <span class="text-sm text-zinc-500 dark:text-zinc-400">/ 100 GB</span>
                        </dd>
                    </div>
                </div>

                <!-- Recent Deployments -->
                <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="border-b border-zinc-200 px-4 py-4 dark:border-zinc-700">
                        <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-white">Recent Deployments
                        </h3>
                    </div>
                    <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <li class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-2 w-2 rounded-full bg-green-500"></div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-zinc-900 dark:text-white">Update
                                        configuration</span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Deployed by Alex &bull; 2m
                                        ago</span>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-zinc-500 dark:text-zinc-400">#8f3a21</span>
                        </li>
                        <li class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-2 w-2 rounded-full bg-green-500"></div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-zinc-900 dark:text-white">Security
                                        patch</span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400">Deployed by System &bull; 1h
                                        ago</span>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-zinc-500 dark:text-zinc-400">#1b9d4e</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="space-y-6">
                <!-- Server Info -->
                <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-4 text-sm font-medium text-zinc-900 dark:text-white">Information</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">OS</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white">Ubuntu 22.04 LTS</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">Kernel</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white">5.15.0-91-generic</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-500 dark:text-zinc-400">Uptime</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-white">14d 2h 12m</dd>
                        </div>
                    </dl>
                </div>

                <!-- Quick Actions -->
                <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="mb-4 text-sm font-medium text-zinc-900 dark:text-white">Actions</h3>
                    <div class="space-y-2">
                        <button
                            class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Restart Server
                        </button>
                        <button
                            class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                            </svg>
                            Power Off
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
