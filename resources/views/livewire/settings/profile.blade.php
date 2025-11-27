<section class="w-full max-w-4xl mx-auto">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        
        <div class="space-y-6">
            <!-- Profile Information -->
            <div class="bg-bg-surface border border-border-subtle rounded-xl p-6">
                <form wire:submit="updateProfileInformation" class="space-y-6">
                    <!-- Name Input -->
                    <flux:field>
                        <flux:label>{{ __('Name') }}</flux:label>
                        <flux:input wire:model="name" type="text" required autofocus autocomplete="name" />
                        <flux:error name="name" />
                    </flux:field>

                    <!-- Email Input -->
                    <flux:field>
                        <flux:label>{{ __('Email') }}</flux:label>
                        <flux:input wire:model="email" type="email" required autocomplete="email" />
                        <flux:error name="email" />

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
                    </flux:field>

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
