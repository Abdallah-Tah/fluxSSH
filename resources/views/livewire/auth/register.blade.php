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
        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" id="register-form" onsubmit="return validateForm(event)">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Full name') }}
                </label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('name')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Email address') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="name@example.com"
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
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    {{ __('Must be at least 8 characters') }}
                </p>
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    {{ __('Confirm password') }}
                </label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                />
                @error('password_confirmation')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms & Conditions -->
            <div class="flex items-start gap-2">
                <div class="flex h-5 items-center">
                    <input
                        type="checkbox"
                        name="terms"
                        id="terms"
                        value="1"
                        class="w-4 h-4 rounded border-zinc-300 dark:border-zinc-700 text-emerald-600 focus:ring-emerald-500 dark:bg-zinc-800"
                    />
                </div>
                <label for="terms" class="text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('I agree to the') }}
                    <a href="{{ route('terms') }}" target="_blank" class="font-medium text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                        {{ __('Terms of Service') }}
                    </a>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full px-4 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold shadow-lg shadow-emerald-500/20 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900"
            >
                {{ __('Create account') }}
            </button>
        </form>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-zinc-200 dark:border-zinc-800"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white dark:bg-zinc-900 px-2 text-zinc-500 dark:text-zinc-400">
                    {{ __('Already have an account?') }}
                </span>
            </div>
        </div>

        <!-- Sign In Link -->
        <div class="text-center">
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white transition-colors group">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>{{ __('Sign in instead') }}</span>
            </a>
        </div>
    </div>

    <script>
        function validateForm(e) {
            e.preventDefault(); // Always prevent default to handle manually
            console.log('Form submission triggered');

            // Get form element
            const form = document.getElementById('register-form');
            if (!form) {
                console.error('Form not found');
                return false;
            }

            // Get all input elements by their IDs (more reliable than name attribute)
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const csrfInput = form.querySelector('input[name="_token"]');

            // Get values
            const name = nameInput?.value || '';
            const email = emailInput?.value || '';
            const password = passwordInput?.value || '';
            const passwordConfirmation = passwordConfirmationInput?.value || '';
            const csrfToken = csrfInput?.value || '';

            // Log values for debugging
            console.log('Form values:', {
                name: name,
                email: email,
                password: password ? '***' : '',
                password_confirmation: passwordConfirmation ? '***' : '',
                csrf_token: csrfToken ? 'present' : 'missing'
            });

            // Client-side validation
            if (!name || !email || !password || !passwordConfirmation) {
                alert('Please fill in all required fields:\n\n' +
                      (!name ? '- Full name is required\n' : '') +
                      (!email ? '- Email is required\n' : '') +
                      (!password ? '- Password is required\n' : '') +
                      (!passwordConfirmation ? '- Password confirmation is required' : ''));
                return false;
            }

            if (password.length < 8) {
                alert('Password must be at least 8 characters');
                return false;
            }

            if (password !== passwordConfirmation) {
                alert('Passwords do not match');
                return false;
            }

            console.log('Form validation passed, submitting via fetch...');

            // Use fetch API to submit form data manually (more reliable in WebView)
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('password_confirmation', passwordConfirmation);

            // Log FormData contents
            console.log('FormData entries:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + (pair[0].includes('password') ? '***' : pair[1]));
            }

            // Submit via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.redirected) {
                    console.log('Redirecting to:', response.url);
                    window.location.href = response.url;
                } else if (response.ok) {
                    return response.json();
                } else {
                    return response.json().then(data => {
                        throw new Error(JSON.stringify(data));
                    });
                }
            })
            .then(data => {
                if (data) {
                    console.log('Response data:', data);
                    if (data.errors) {
                        let errorMsg = 'Validation errors:\n\n';
                        for (let field in data.errors) {
                            errorMsg += `${field}: ${data.errors[field].join(', ')}\n`;
                        }
                        alert(errorMsg);
                    }
                }
            })
            .catch(error => {
                console.error('Form submission error:', error);
                alert('An error occurred during registration. Please try again.');
            });

            return false;
        }
    </script>
</x-layouts.auth>
