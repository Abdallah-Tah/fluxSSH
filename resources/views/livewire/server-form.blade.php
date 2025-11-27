<div class="space-y-6">
    <div class="text-center sm:text-left">
        <h2 class="text-xl font-bold text-text-primary tracking-tight">{{ $server ? 'Edit Connection' : 'New Connection' }}</h2>
        <p class="mt-1 text-sm text-text-secondary">Configure the details for your secure shell connection.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Server Name -->
        <flux:field>
            <flux:label>Server Name</flux:label>
            <flux:input wire:model="name" placeholder="e.g. Production DB" />
            <flux:error name="name" />
        </flux:field>

        <!-- Connection Details -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-8">
                <flux:field>
                    <flux:label>Hostname / IP</flux:label>
                    <flux:input wire:model="host" icon="globe-alt" placeholder="192.168.1.1" />
                    <flux:error name="host" />
                </flux:field>
            </div>

            <div class="sm:col-span-4">
                <flux:field>
                    <flux:label>Port</flux:label>
                    <flux:input wire:model="port" type="number" placeholder="22" />
                    <flux:error name="port" />
                </flux:field>
            </div>
        </div>

        <!-- Username -->
        <flux:field>
            <flux:label>Username</flux:label>
            <flux:input wire:model="username" icon="user" placeholder="root" />
            <flux:error name="username" />
        </flux:field>

        <!-- Authentication Type -->
        <div class="space-y-3">
            <flux:label>Authentication Method</flux:label>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" wire:click="$set('auth_type', 'password')"
                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border transition-all {{ $auth_type === 'password' ? 'bg-bg-surface-alt border-primary-500 text-primary-600 ring-1 ring-primary-500' : 'bg-bg-surface border-border-strong text-text-secondary hover:border-border-subtle hover:bg-bg-surface-alt' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span class="font-medium text-sm">Password</span>
                </button>
                <button type="button" wire:click="$set('auth_type', 'key')"
                    class="relative flex items-center justify-center gap-2 px-4 py-3 rounded-lg border transition-all {{ $auth_type === 'key' ? 'bg-bg-surface-alt border-primary-500 text-primary-600 ring-1 ring-primary-500' : 'bg-bg-surface border-border-strong text-text-secondary hover:border-border-subtle hover:bg-bg-surface-alt' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                    </svg>
                    <span class="font-medium text-sm">SSH Key</span>
                </button>
            </div>
            @error('auth_type') <span class="text-xs text-danger mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Authentication Input -->
        @if ($auth_type === 'key')
            <flux:field>
                <flux:label>Private Key</flux:label>
                <flux:textarea wire:model="private_key" rows="6" placeholder="-----BEGIN OPENSSH PRIVATE KEY-----&#10;..." />
                <flux:description>Paste your SSH private key (RSA, ED25519, ECDSA, or DSA).</flux:description>
                <flux:error name="private_key" />

                @if($server && !$private_key)
                    <div class="mt-2 p-3 bg-blue-500/10 border border-blue-500/20 rounded-md">
                        <p class="text-xs text-blue-600 dark:text-blue-400">Private key is already stored. Leave empty to keep existing.</p>
                    </div>
                @endif
            </flux:field>
        @else
            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input type="password" wire:model="password" placeholder="••••••••" />
                <flux:error name="password" />

                @if($server && !$password)
                    <div class="mt-2 p-3 bg-blue-500/10 border border-blue-500/20 rounded-md">
                        <p class="text-xs text-blue-600 dark:text-blue-400">Password is already stored. Leave empty to keep existing.</p>
                    </div>
                @endif
            </flux:field>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 flex justify-center items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">
                    {{ $server ? 'Update Connection' : 'Connect Server' }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>

            @if($server)
                <button type="button" wire:click="cancel" class="px-4 py-2 bg-bg-surface border border-border-strong hover:bg-bg-surface-alt text-text-secondary text-sm font-medium rounded-md transition-colors">
                    Cancel
                </button>
            @endif
        </div>
    </form>

    <!-- Success Message -->
    @if (session('message'))
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-md">
            <p class="text-sm text-emerald-600 dark:text-emerald-400">{{ session('message') }}</p>
        </div>
    @endif
</div>
