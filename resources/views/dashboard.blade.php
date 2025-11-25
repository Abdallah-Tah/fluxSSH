<x-layouts.app title="Dashboard">
    <div class="flex h-full flex-col">
        <div
            class="flex items-center justify-between border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center gap-2">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-zinc-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ml-1 text-sm font-medium text-zinc-500 md:ml-2 dark:text-zinc-400">All
                                    Servers</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <a href="{{ route('servers') }}"
                class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                New Server
            </a>
        </div>

        <div class="flex-1 p-6">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div class="flex flex-1 items-center gap-2">
                    <div class="relative w-full max-w-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-zinc-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text"
                            class="block w-full rounded-md border-0 py-1.5 pl-10 text-zinc-900 ring-1 ring-inset ring-zinc-300 placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-zinc-600 sm:text-sm sm:leading-6 dark:bg-zinc-800 dark:text-white dark:ring-zinc-700 dark:focus:ring-zinc-500"
                            placeholder="Search servers... (⌘K)">
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                IP Address</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Region</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                                CPU</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                        @php
                            $servers = \App\Models\Server::all();
                            if ($servers->isEmpty()) {
                                $servers = collect([
                                    (object) [
                                        'id' => 1,
                                        'name' => 'prod-web-01',
                                        'ip_address' => '192.168.1.10',
                                        'region' => 'NYC1',
                                        'is_active' => true,
                                        'cpu_usage' => 45,
                                    ],
                                    (object) [
                                        'id' => 2,
                                        'name' => 'prod-db-01',
                                        'ip_address' => '192.168.1.11',
                                        'region' => 'NYC1',
                                        'is_active' => true,
                                        'cpu_usage' => 12,
                                    ],
                                    (object) [
                                        'id' => 3,
                                        'name' => 'staging-01',
                                        'ip_address' => '10.0.0.5',
                                        'region' => 'SFO2',
                                        'is_active' => false,
                                        'cpu_usage' => 0,
                                    ],
                                    (object) [
                                        'id' => 4,
                                        'name' => 'dev-sandbox',
                                        'ip_address' => '10.0.0.20',
                                        'region' => 'AMS3',
                                        'is_active' => true,
                                        'cpu_usage' => 5,
                                    ],
                                ]);
                            }
                        @endphp

                        @foreach ($servers as $server)
                            <tr class="group hover:bg-zinc-50 dark:hover:bg-zinc-800/50 cursor-pointer"
                                onclick="window.location='{{ route('servers.show', $server) }}'">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                                    <a href="{{ route('servers.show', $server) }}"
                                        class="hover:underline">{{ $server->name }}</a>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 font-mono dark:text-zinc-400">
                                    {{ $server->ip_address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $server->region ?? 'NYC1' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($server->is_active)
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/30 dark:text-green-400">Online</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-600 ring-1 ring-inset ring-zinc-500/10 dark:bg-zinc-400/10 dark:text-zinc-400 dark:ring-zinc-400/20">Offline</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-16 rounded-full bg-zinc-200 dark:bg-zinc-700">
                                            <div class="h-1.5 rounded-full bg-zinc-900 dark:bg-white"
                                                style="width: {{ $server->cpu_usage ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ $server->cpu_usage ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-zinc-400 hover:text-zinc-900 dark:hover:text-white">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path
                                                d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
