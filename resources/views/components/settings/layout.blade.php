<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" class="group flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}" wire:navigate>
                {{ __('Profile') }}
            </a>
            <a href="{{ route('user-password.edit') }}" class="group flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('user-password.edit') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}" wire:navigate>
                {{ __('Password') }}
            </a>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <a href="{{ route('two-factor.show') }}" class="group flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('two-factor.show') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}" wire:navigate>
                    {{ __('Two-Factor Auth') }}
                </a>
            @endif
            <a href="{{ route('appearance.edit') }}" class="group flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('appearance.edit') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}" wire:navigate>
                {{ __('Appearance') }}
            </a>
            <a href="{{ route('api-keys.index') }}" class="group flex items-center rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs('api-keys.index') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white' }}" wire:navigate>
                {{ __('API Keys') }}
            </a>
        </nav>
    </div>

    <div class="md:hidden h-px w-full bg-zinc-200 dark:bg-zinc-700 my-4"></div>

    <div class="flex-1 self-stretch max-md:pt-6">
        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $heading ?? '' }}</h2>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
