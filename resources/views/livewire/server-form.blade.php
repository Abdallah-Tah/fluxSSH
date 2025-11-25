<div class="space-y-6">
    <form wire:submit="save">
        <!-- Basic Information Section -->
        <div class="space-y-6">
            <div class="grid gap-5 sm:grid-cols-2">
                <!-- Server Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Server
                        Name</label>
                    <input wire:model="name" type="text" id="name" placeholder="e.g., Production Server"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                        required />
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Host/IP Address -->
                <div>
                    <label for="host" class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Host / IP
                        Address</label>
                    <input wire:model="host" type="text" id="host" placeholder="e.g., 192.168.1.100"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-mono"
                        required />
                    @error('host')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Port -->
                <div>
                    <label for="port"
                        class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Port</label>
                    <input wire:model="port" type="number" id="port" min="1" max="65535"
                        placeholder="22"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-mono"
                        required />
                    @error('port')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username"
                        class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Username</label>
                    <input wire:model="username" type="text" id="username" placeholder="e.g., root, ubuntu"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all"
                        required />
                    @error('username')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Toggle -->
                <div>
                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Status</label>
                    <button type="button" wire:click="$toggle('is_active')"
                        class="relative inline-flex h-12 w-full items-center justify-between rounded-xl border {{ $is_active ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800' }} px-4 transition-all">
                        <span
                            class="text-sm {{ $is_active ? 'text-emerald-700 dark:text-emerald-400' : 'text-zinc-500' }}">
                            {{ $is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $is_active ? 'bg-emerald-500' : 'bg-zinc-200 dark:bg-zinc-600' }}">
                            <span
                                class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Authentication Type -->
            <div>
                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-3">Authentication
                    Method</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="auth_type" value="password" class="peer sr-only" />
                        <div
                            class="flex items-center gap-3 p-4 rounded-xl border-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600">
                            <div
                                class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 peer-checked:bg-emerald-100 dark:peer-checked:bg-emerald-900/50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">Password</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Use password authentication</p>
                            </div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model="auth_type" value="key" class="peer sr-only" />
                        <div
                            class="flex items-center gap-3 p-4 rounded-xl border-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600">
                            <div
                                class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 peer-checked:bg-emerald-100 dark:peer-checked:bg-emerald-900/50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white">SSH Key</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Use private key authentication</p>
                            </div>
                        </div>
                    </label>
                </div>
                @error('auth_type')
                    <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Authentication Credentials -->
            @if ($auth_type === 'password')
                <div>
                    <label for="password"
                        class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Password</label>
                    <input wire:model="password" type="password" id="password"
                        placeholder="{{ $server ? 'Leave empty to keep current' : 'Enter password' }}"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all" />
                    <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        {{ $server ? 'Leave empty to keep the current password' : 'Your password will be encrypted and stored securely' }}
                    </p>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <div>
                    <label for="private_key"
                        class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Private Key</label>
                    <textarea wire:model="private_key" id="private_key" rows="6"
                        placeholder="{{ $server ? 'Leave empty to keep current key' : 'Paste your private key here...' }}"
                        class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all font-mono text-sm resize-none"></textarea>
                    <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        {{ $server ? 'Leave empty to keep the current private key' : 'Your private key will be encrypted and stored securely' }}
                    </p>
                    @error('private_key')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-zinc-200 dark:border-zinc-800">
            <button type="button" wire:click="cancel"
                class="px-5 py-2.5 rounded-xl text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                Cancel
            </button>
            <button type="submit"
                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-500 hover:bg-emerald-600 shadow-lg shadow-emerald-500/25 transition-all duration-200">
                {{ $server ? 'Update Server' : 'Create Server' }}
            </button>
        </div>
    </form>
</div>
