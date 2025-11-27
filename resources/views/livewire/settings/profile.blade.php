<section class="w-full max-w-4xl mx-auto">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        
        <!-- Card Wrapper with Gradient Border -->
        <div class="relative group rounded-2xl p-[1px] bg-gradient-to-b from-white/10 to-white/5 mt-6">
            
            <!-- Ambient Glow Effect -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-[200px] bg-purple-500/20 blur-[100px] rounded-full mix-blend-screen pointer-events-none"></div>

            <!-- Card Content -->
            <div class="relative bg-[#18181b] rounded-2xl p-8 ring-1 ring-white/5 overflow-hidden">
                
                <!-- Spotlight Effect (Subtle top highlight) -->
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-50"></div>

                <form wire:submit="updateProfileInformation" class="space-y-6 relative z-10">
                    
                    <!-- Name Input -->
                    <div class="group/input">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-400 group-focus-within/input:text-white transition-colors duration-200">{{ __('Name') }}</label>
                        <div class="mt-2 relative">
                            <input wire:model="name" type="text" id="name" required autofocus autocomplete="name" 
                                class="block w-full rounded-lg border-0 bg-zinc-900/50 py-2.5 text-white shadow-inner ring-1 ring-white/10 placeholder:text-zinc-600 focus:ring-2 focus:ring-indigo-500/50 focus:bg-zinc-900 transition-all duration-200 sm:text-sm sm:leading-6">
                            <!-- Inner Glow on Focus -->
                            <div class="absolute inset-0 rounded-lg ring-1 ring-white/20 opacity-0 group-focus-within/input:opacity-100 pointer-events-none transition-opacity duration-300"></div>
                        </div>
                        @error('name') <span class="mt-1 text-sm text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="group/input">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-400 group-focus-within/input:text-white transition-colors duration-200">{{ __('Email') }}</label>
                        <div class="mt-2 relative">
                            <input wire:model="email" type="email" id="email" required autocomplete="email" 
                                class="block w-full rounded-lg border-0 bg-zinc-900/50 py-2.5 text-white shadow-inner ring-1 ring-white/10 placeholder:text-zinc-600 focus:ring-2 focus:ring-indigo-500/50 focus:bg-zinc-900 transition-all duration-200 sm:text-sm sm:leading-6">
                             <!-- Inner Glow on Focus -->
                             <div class="absolute inset-0 rounded-lg ring-1 ring-white/20 opacity-0 group-focus-within/input:opacity-100 pointer-events-none transition-opacity duration-300"></div>
                        </div>
                        @error('email') <span class="mt-1 text-sm text-red-400">{{ $message }}</span> @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                            <div class="mt-3 p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                <p class="text-sm text-yellow-200">
                                    {{ __('Your email address is unverified.') }}

                                    <button wire:click.prevent="resendVerificationNotification" class="underline ml-1 text-yellow-400 hover:text-yellow-300 transition-colors focus:outline-none">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-400">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-white/5">
                        <x-action-message class="mr-3 text-gray-400" on="profile-updated">
                            {{ __('Saved.') }}
                        </x-action-message>

                        <button type="submit" 
                            class="relative inline-flex items-center justify-center rounded-lg bg-white/5 px-4 py-2 text-sm font-medium text-white shadow-lg ring-1 ring-white/10 transition-all duration-200 hover:bg-white/10 hover:ring-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 active:scale-95">
                            <span class="absolute inset-0 rounded-lg bg-gradient-to-t from-white/5 to-transparent opacity-0 hover:opacity-100 transition-opacity"></span>
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-10">
             <livewire:settings.delete-user-form />
        </div>
       
    </x-settings.layout>
</section>
