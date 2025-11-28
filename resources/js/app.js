// NativePHP event listeners
window.addEventListener('native:activity-created', (event) => {
    console.log('Activity created:', event.detail);
    // Trigger Livewire refresh
    if (window.Livewire) {
        window.Livewire.dispatch('activity-created', event.detail);
    }
});

window.addEventListener('native:server-status-changed', (event) => {
    console.log('Server status changed:', event.detail);
    // Trigger Livewire refresh
    if (window.Livewire) {
        window.Livewire.dispatch('server-status-changed', event.detail);
    }
});
