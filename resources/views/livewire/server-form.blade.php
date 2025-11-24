<div class="space-y-6">
    <form wire:submit="save">
        <div class="grid gap-6">
            <!-- Basic Information -->
            <div class="grid md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Server Name</flux:label>
                    <flux:input wire:model="name" placeholder="e.g., Production Server" required />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Status</flux:label>
                    <flux:switch wire:model="is_active" />
                    <flux:text size="sm" class="text-gray-600 mt-1">
                        {{ $is_active ? 'Active' : 'Inactive' }}
                    </flux:text>
                </flux:field>
            </div>

            <!-- Connection Details -->
            <div class="grid md:grid-cols-3 gap-4">
                <flux:field class="md:col-span-2">
                    <flux:label>Host/IP Address</flux:label>
                    <flux:input wire:model="host" placeholder="e.g., 192.168.1.100 or server.example.com" required />
                    <flux:error name="host" />
                </flux:field>

                <flux:field>
                    <flux:label>Port</flux:label>
                    <flux:input wire:model="port" type="number" min="1" max="65535" required />
                    <flux:error name="port" />
                </flux:field>
            </div>

            <!-- Username -->
            <flux:field>
                <flux:label>Username</flux:label>
                <flux:input wire:model="username" placeholder="e.g., root, ubuntu, admin" required />
                <flux:error name="username" />
            </flux:field>

            <!-- Authentication Type -->
            <flux:field>
                <flux:label>Authentication Type</flux:label>
                <div class="flex gap-4 mt-2">
                    <flux:radio wire:model="auth_type" value="password" name="auth_type">
                        Password Authentication
                    </flux:radio>
                    <flux:radio wire:model="auth_type" value="key" name="auth_type">
                        Private Key Authentication
                    </flux:radio>
                </div>
                <flux:error name="auth_type" />
            </flux:field>

            <!-- Authentication Credentials -->
            @if ($auth_type === 'password')
                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input wire:model="password" type="password"
                        placeholder="{{ $server ? 'Leave empty to keep current password' : 'Enter password' }}" />
                    <flux:text size="sm" class="text-gray-600 mt-1">
                        @if ($server)
                            Leave empty to keep the current password
                        @else
                            Your password will be encrypted and stored securely
                        @endif
                    </flux:text>
                    <flux:error name="password" />
                </flux:field>
            @else
                <flux:field>
                    <flux:label>Private Key</flux:label>
                    <flux:textarea wire:model="private_key" rows="8"
                        placeholder="{{ $server ? 'Leave empty to keep current key' : 'Paste your private key here...' }}"
                        class="font-mono text-sm" />
                    <flux:text size="sm" class="text-gray-600 mt-1">
                        @if ($server)
                            Leave empty to keep the current private key
                        @else
                            Your private key will be encrypted and stored securely
                        @endif
                    </flux:text>
                    <flux:error name="private_key" />
                </flux:field>
            @endif
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <flux:button wire:click="cancel" variant="outline">
                Cancel
            </flux:button>
            <flux:button type="submit" variant="primary">
                {{ $server ? 'Update Server' : 'Create Server' }}
            </flux:button>
        </div>
    </form>
</div>
