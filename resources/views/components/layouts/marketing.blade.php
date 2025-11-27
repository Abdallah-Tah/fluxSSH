<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data x-bind:class="$flux.appearance === 'system' ? '' : $flux.appearance">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'FluxSSH' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg-app);
            color: var(--color-text-primary);
        }
        
        /* Beam Effect */
        .beam-container {
            position: relative;
            overflow: hidden;
        }
        
        .beam-border {
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: inherit;
            padding: 1px;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
        
        .beam-border::before {
            content: "";
            position: absolute;
            inset: 0;
            background: conic-gradient(from 0deg at 50% 50%, transparent 0deg, var(--color-primary-500) 60deg, transparent 120deg);
            animation: beam-rotate 4s linear infinite;
        }

        @keyframes beam-rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .beam-btn {
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .beam-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .beam-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: 0.5s;
        }
        
        .beam-btn:hover::after {
            left: 100%;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col selection:bg-primary-500/20 selection:text-primary-600 transition-colors duration-300">
    
    <!-- Header -->
    <header class="fixed top-0 w-full z-50 border-b border-border-subtle bg-bg-surface/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-primary-500 flex items-center justify-center shadow-lg shadow-primary-500/20 group-hover:shadow-primary-500/40 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg tracking-tight text-text-primary leading-none">FluxSSH</span>
                    <span class="text-[10px] text-text-tertiary font-medium tracking-wide uppercase mt-0.5">Mobile Server Management</span>
                </div>
            </a>
            
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('about') }}" class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">About</a>
                <a href="{{ route('contact') }}" class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Contact</a>
                <a href="{{ route('terms') }}" class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Terms</a>
            </nav>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-text-secondary hover:text-text-primary transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="group relative px-4 py-2 rounded-lg bg-text-primary text-bg-app text-sm font-semibold hover:bg-text-secondary transition-colors overflow-hidden">
                        <span class="relative z-10">Get Started</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-24 pb-12 px-6">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-border-subtle bg-bg-surface-alt/50 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 rounded bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="font-bold text-base tracking-tight text-text-primary">FluxSSH</span>
                    </a>
                    <p class="text-text-secondary text-sm leading-relaxed max-w-sm">
                        The next generation of SSH management. Secure, fast, and beautiful. 
                        Manage your servers with confidence and style.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold text-text-primary mb-4">Product</h3>
                    <ul class="space-y-3 text-sm text-text-tertiary">
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Changelog</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Documentation</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-text-primary mb-4">Company</h3>
                    <ul class="space-y-3 text-sm text-text-tertiary">
                        <li><a href="{{ route('about') }}" class="hover:text-primary-500 transition-colors">About</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-primary-500 transition-colors">Contact</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-primary-500 transition-colors">Terms</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition-colors">Privacy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-border-subtle flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-text-tertiary text-xs">
                    &copy; {{ date('Y') }} FluxSSH. All rights reserved.
                </p>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-text-tertiary hover:text-text-primary transition-colors">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                    </a>
                    <a href="#" class="text-text-tertiary hover:text-text-primary transition-colors">
                        <span class="sr-only">GitHub</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    @fluxScripts
</body>
</html>
