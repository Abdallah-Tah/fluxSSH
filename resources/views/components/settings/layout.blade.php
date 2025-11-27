<div class="flex items-start gap-8 max-md:flex-col">
    <!-- Sidebar Navigation -->
    <div class="w-full pb-4 md:w-56 lg:w-64 shrink-0">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}"
                class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('profile.edit') ? 'bg-bg-surface-alt text-text-primary' : 'text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary' }}"
                wire:navigate>
                <svg class="w-4 h-4 {{ request()->routeIs('profile.edit') ? 'text-primary-500' : 'text-text-tertiary group-hover:text-text-secondary' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                {{ __('Profile') }}
            </a>
            <a href="{{ route('user-password.edit') }}"
                class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('user-password.edit') ? 'bg-bg-surface-alt text-text-primary' : 'text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary' }}"
                wire:navigate>
                <svg class="w-4 h-4 {{ request()->routeIs('user-password.edit') ? 'text-primary-500' : 'text-text-tertiary group-hover:text-text-secondary' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                {{ __('Password') }}
            </a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}"
                    class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('two-factor.show') ? 'bg-bg-surface-alt text-text-primary' : 'text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary' }}"
                    wire:navigate>
                    <svg class="w-4 h-4 {{ request()->routeIs('two-factor.show') ? 'text-primary-500' : 'text-text-tertiary group-hover:text-text-secondary' }}"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    {{ __('Two-Factor Auth') }}
                </a>
            @endif
            <a href="{{ route('appearance.edit') }}"
                class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('appearance.edit') ? 'bg-bg-surface-alt text-text-primary' : 'text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary' }}"
                wire:navigate>
                <svg class="w-4 h-4 {{ request()->routeIs('appearance.edit') ? 'text-primary-500' : 'text-text-tertiary group-hover:text-text-secondary' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z" />
                </svg>
                {{ __('Appearance') }}
            </a>
            <a href="{{ route('api-keys.index') }}"
                class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('api-keys.index') ? 'bg-bg-surface-alt text-text-primary' : 'text-text-secondary hover:bg-bg-surface-alt hover:text-text-primary' }}"
                wire:navigate>
                <svg class="w-4 h-4 {{ request()->routeIs('api-keys.index') ? 'text-primary-500' : 'text-text-tertiary group-hover:text-text-secondary' }}"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                {{ __('API Keys') }}
            </a>
        </nav>
    </div>

    <!-- Divider for Mobile -->
    <div class="md:hidden h-px w-full bg-border-subtle -mt-2 -mb-2"></div>

    <!-- Main Content -->
    <div class="flex-1 self-stretch max-md:pt-4">
        <div class="mb-8">
            <h2 class="text-xl font-bold text-text-primary">{{ $heading ?? '' }}</h2>
            <p class="mt-1 text-sm text-text-secondary">{{ $subheading ?? '' }}</p>
        </div>

        <div class="w-full max-w-xl">
            {{ $slot }}
        </div>
    </div>
</div>
