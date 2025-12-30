<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 text-center">
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white">
                {{ __('Create your account') }}
            </h1>
            <p class="text-base text-zinc-600 dark:text-zinc-400">
                {{ __('Get started with FluxSSH today') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" onsubmit="handleSubmit(event)">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Name') }}
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('name')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Email') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Confirm Password') }}
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('password_confirmation')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('login') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-zinc-800">
                    {{ __('Already registered?') }}
                </a>

                <button type="submit" class="ms-4 px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold shadow-lg shadow-emerald-500/20 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function handleSubmit(event) {
            console.log('handleSubmit called');
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            console.log('FormData created, entries:');
            for (const [key, value] of formData.entries()) {
                console.log(`  ${key}: ${key.includes('password') ? '***' : value}`);
            }

            // Convert FormData to URLSearchParams for proper encoding
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                params.append(key, value);
            }

            const bodyString = params.toString();
            console.log('Body string length:', bodyString.length);
            console.log('Submitting to:', form.action);

            // Submit using fetch with proper URL encoding
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: bodyString
            })
            .then(response => {
                console.log('Response received, status:', response.status);
                console.log('Response redirected:', response.redirected);
                console.log('Response URL:', response.url);

                if (response.ok || response.redirected) {
                    const redirectUrl = response.url || '{{ route('dashboard') }}';
                    console.log('Redirecting to:', redirectUrl);
                    window.location.href = redirectUrl;
                } else {
                    return response.text().then(html => {
                        console.log('Rendering error response HTML');
                        document.open();
                        document.write(html);
                        document.close();
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Registration failed: ' + error.message);
            });

            return false;
        }
    </script>
</x-layouts.auth>
