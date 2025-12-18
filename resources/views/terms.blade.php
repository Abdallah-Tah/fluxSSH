<x-layouts.marketing title="Terms of Service - FluxSSH">
    <!-- Background Pattern -->
    <div class="fixed inset-0 z-0 grid grid-cols-[repeat(auto-fit,minmax(100px,1fr))] opacity-20 pointer-events-none">
        @for ($i = 0; $i < 20; $i++)
            <div class="h-full border-r border-white/5 bg-gradient-to-b from-transparent via-white/5 to-transparent"
                 style="animation: fade-in-slide-up-blur 1s cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: {{ $i * 0.1 }}s;"></div>
        @endfor
    </div>

    <div class="relative z-10 max-w-4xl mx-auto py-12 md:py-20 px-4">
        <!-- Header -->
        <div class="text-center mb-16 space-y-4 animate-fade-in-slide-up-blur">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-text-primary">Terms of Service</h1>
            <p class="text-lg text-text-secondary">
                Last updated: <time datetime="2025-11-25">November 25, 2025</time>
            </p>
        </div>

        <!-- Introduction -->
        <div class="mb-12 p-6 rounded-2xl bg-bg-surface border border-border-subtle">
            <p class="text-lg text-text-secondary leading-relaxed">
                Please read these Terms of Service ("Terms", "Terms of Service") carefully before using the FluxSSH application operated by FluxSSH ("us", "we", or "our"). Your access to and use of the Service is conditioned on your acceptance of and compliance with these Terms.
            </p>
        </div>

        <!-- Terms Sections -->
        <div class="space-y-8">
            <!-- Section 1 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">1</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-text-primary mb-4">Acceptance of Terms</h2>
                        <p class="text-text-secondary leading-relaxed">
                            By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 2 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">2</span>
                    </div>
                    <div class="flex-1 space-y-4">
                        <h2 class="text-2xl font-bold text-text-primary">Account Responsibilities</h2>
                        <p class="text-text-secondary leading-relaxed">
                            When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.
                        </p>
                        <p class="text-text-secondary leading-relaxed">
                            You are responsible for safeguarding the password that you use to access the Service and for any activities or actions under your password, whether your password is with our Service or a third-party service.
                        </p>
                        <div class="p-4 rounded-lg bg-amber-500/10 border border-amber-500/20">
                            <p class="text-sm text-amber-600 dark:text-amber-400">
                                <strong>Important:</strong> You agree not to disclose your password to any third party and to take sole responsibility for any activities or actions under your account.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 3 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">3</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-text-primary mb-4">Intellectual Property</h2>
                        <p class="text-text-secondary leading-relaxed">
                            The Service and its original content, features and functionality are and will remain the exclusive property of FluxSSH and its licensors. The Service is protected by copyright, trademark, and other laws of both the United States and foreign countries. Our trademarks and trade dress may not be used in connection with any product or service without the prior written consent of FluxSSH.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 4 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">4</span>
                    </div>
                    <div class="flex-1 space-y-4">
                        <h2 class="text-2xl font-bold text-text-primary">Links To Other Websites</h2>
                        <p class="text-text-secondary leading-relaxed">
                            Our Service may contain links to third-party websites or services that are not owned or controlled by FluxSSH.
                        </p>
                        <p class="text-text-secondary leading-relaxed">
                            FluxSSH has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party websites or services. You further acknowledge and agree that FluxSSH shall not be responsible or liable, directly or indirectly, for any damage or loss caused or alleged to be caused by or in connection with use of or reliance on any such content, goods or services available on or through any such websites or services.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 5 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">5</span>
                    </div>
                    <div class="flex-1 space-y-4">
                        <h2 class="text-2xl font-bold text-text-primary">Termination</h2>
                        <p class="text-text-secondary leading-relaxed">
                            We may terminate or suspend access to our Service immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.
                        </p>
                        <p class="text-text-secondary leading-relaxed">
                            All provisions of the Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity and limitations of liability.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 6 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">6</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-text-primary mb-4">Governing Law</h2>
                        <p class="text-text-secondary leading-relaxed">
                            These Terms shall be governed and construed in accordance with the laws of the United States, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Section 7 -->
            <section class="p-8 rounded-2xl bg-bg-surface border border-border-subtle hover:border-primary-500/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                        <span class="text-lg font-bold text-primary-500">7</span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-text-primary mb-4">Changes to Terms</h2>
                        <p class="text-text-secondary leading-relaxed">
                            We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect. What constitutes a material change will be determined at our sole discretion.
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <!-- Contact Section -->
        <div class="mt-16 p-8 rounded-2xl bg-gradient-to-r from-primary-500/10 to-orange-500/10 border border-primary-500/20">
            <div class="text-center space-y-4">
                <h3 class="text-xl font-bold text-text-primary">Questions About Our Terms?</h3>
                <p class="text-text-secondary">
                    If you have any questions about these Terms, please don't hesitate to contact us.
                </p>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-500 hover:bg-primary-600 text-white font-semibold transition-colors shadow-lg shadow-primary-500/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</x-layouts.marketing>
