<div>
    <section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('API Keys')" :subheading="__('Manage your personal access tokens')">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-text-secondary">
                    API keys allow you to authenticate with the FluxSSH API programmatically.
                </p>
                <button type="button" class="inline-flex justify-center rounded-md bg-primary-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600 transition-colors">
                    Create New Key
                </button>
            </div>

            <div class="overflow-hidden rounded-xl border border-border-subtle bg-bg-surface">
                <table class="min-w-full divide-y divide-border-subtle">
                    <thead class="bg-bg-surface-alt">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-tertiary uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-tertiary uppercase tracking-wider">Last Used</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-tertiary uppercase tracking-wider">Created</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        <!-- Mock Data -->
                        <tr class="hover:bg-bg-surface-alt/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">CLI Access</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">2 hours ago</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">Nov 20, 2023</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" class="text-danger hover:text-red-700 dark:hover:text-red-300 transition-colors">Revoke</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-bg-surface-alt/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">CI/CD Pipeline</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">Never</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">Nov 24, 2023</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" class="text-danger hover:text-red-700 dark:hover:text-red-300 transition-colors">Revoke</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </x-settings.layout>
</section>
</div>
