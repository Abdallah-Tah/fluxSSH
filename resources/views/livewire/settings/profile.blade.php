<section class="w-full max-w-4xl mx-auto">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        
        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6">
                <form wire:submit="updateProfileInformation" class="space-y-6">
                    <!-- Name Input -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-text-primary">{{ __('Name') }}</label>
                        <input wire:model="name" type="text" id="name" required autofocus autocomplete="name" 
                            class="w-full rounded-md border-border-strong bg-bg-surface-alt text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                        @error('name') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-text-primary">{{ __('Email') }}</label>
                        <input wire:model="email" type="email" id="email" required autocomplete="email" 
                            class="w-full rounded-md border-border-strong bg-bg-surface-alt text-text-primary placeholder-text-tertiary focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors">
                        @error('email') <span class="text-xs text-danger">{{ $message }}</span> @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                            <div class="mt-2 p-3 rounded-md bg-yellow-500/10 border border-yellow-500/20">
                                <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                    {{ __('Your email address is unverified.') }}

                                    <button wire:click.prevent="resendVerificationNotification" class="underline ml-1 hover:text-yellow-500 transition-colors focus:outline-none">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-emerald-600 dark:text-emerald-400">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-subtle">
                        <x-action-message class="mr-3 text-text-tertiary" on="profile-updated">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <button type="submit" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Delete User -->
            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6">
                <livewire:settings.delete-user-form />
            </div>
        </div>
       
    </x-settings.layout>
</section>
