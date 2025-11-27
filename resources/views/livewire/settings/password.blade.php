<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <div class="bg-bg-surface border border-border-subtle rounded-xl p-6">
            <form method="POST" wire:submit="updatePassword" class="space-y-6">
                <flux:input
                    wire:model="current_password"
                    :label="__('Current password')"
                    type="password"
                    required
                    autocomplete="current-password"
                />
                <flux:input
                    wire:model="password"
                    :label="__('New password')"
                    type="password"
                    required
                    autocomplete="new-password"
                />
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                />

                <div class="flex items-center justify-end gap-4 pt-2">
                    <x-action-message class="text-text-tertiary" on="password-updated">
                        {{ __('Saved.') }}
                    </x-action-message>

                    <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
                </div>
            </form>
        </div>
    </x-settings.layout>
</section>
