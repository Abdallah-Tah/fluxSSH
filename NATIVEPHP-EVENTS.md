# NativePHP Real-Time Events

## Overview

FluxSSH uses NativePHP's native event system for real-time updates between the Laravel backend and the frontend (both web and mobile).

## How It Works

### 1. Backend Events (PHP)

When activities are created, the `ActivityLog` model dispatches a NativePHP event:

```php
// app/Models/ActivityLog.php
protected static function booted()
{
    static::created(function ($activityLog) {
        if (class_exists('\Native\Laravel\Facades\Window')) {
            \Native\Laravel\Facades\Window::current()->dispatch('activity-created', [
                'id' => $activityLog->id,
                'type' => $activityLog->type,
                'action' => $activityLog->action,
                'description' => $activityLog->description,
                'created_at' => $activityLog->created_at?->toISOString(),
            ]);
        }
    });
}
```

### 2. Frontend Listeners (JavaScript)

The JavaScript code listens for these events and forwards them to Livewire:

```javascript
// resources/js/app.js
window.addEventListener('native:activity-created', (event) => {
    console.log('Activity created:', event.detail);
    if (window.Livewire) {
        window.Livewire.dispatch('activity-created', event.detail);
    }
});
```

### 3. Livewire Components (PHP)

Livewire components listen for the dispatched events and update the UI:

```php
// app/Livewire/Dashboard.php
protected $listeners = [
    'activity-created' => 'handleActivityCreated',
    'server-status-changed' => 'handleServerStatusChanged',
];

public function handleActivityCreated(): void
{
    $this->clearCaches();
    // UI automatically refreshes
}
```

## Event Flow

```
User executes command
         ↓
ActivityLog::create() called
         ↓
ActivityLog booted() hook fires
         ↓
NativePHP Window::dispatch('activity-created')
         ↓
JavaScript receives 'native:activity-created'
         ↓
Livewire.dispatch('activity-created')
         ↓
Dashboard->handleActivityCreated()
         ↓
Cache cleared, UI refreshes
```

## Available Events

### activity-created
Fired when a new activity log entry is created.

**Payload:**
```json
{
    "id": 123,
    "type": "command",
    "action": "executed",
    "description": "Executed command: ls -la",
    "created_at": "2025-11-27T14:30:00.000Z"
}
```

### server-status-changed
Fired when a server's status changes (to be implemented).

**Payload:**
```json
{
    "server_id": 1,
    "is_active": true,
    "name": "Production Server"
}
```

## Adding New Events

### Step 1: Dispatch from Backend

```php
// In your model or controller
if (class_exists('\Native\Laravel\Facades\Window')) {
    \Native\Laravel\Facades\Window::current()->dispatch('your-event-name', [
        'data' => 'your data here'
    ]);
}
```

### Step 2: Listen in JavaScript

```javascript
// In resources/js/app.js
window.addEventListener('native:your-event-name', (event) => {
    console.log('Event received:', event.detail);
    if (window.Livewire) {
        window.Livewire.dispatch('your-event-name', event.detail);
    }
});
```

### Step 3: Handle in Livewire

```php
// In your Livewire component
protected $listeners = [
    'your-event-name' => 'handleYourEvent',
];

public function handleYourEvent($data): void
{
    // Handle the event
    $this->refresh();
}
```

## Benefits

✅ **No WebSocket Server Needed** - Uses NativePHP's built-in event system
✅ **Works Everywhere** - Web browsers and native mobile apps
✅ **Simple Setup** - No additional configuration required
✅ **Lightweight** - Minimal overhead
✅ **Real-Time** - Instant updates without polling

## Performance

- **No polling overhead** - Events only fire when something happens
- **Automatic cleanup** - NativePHP handles connection management
- **Efficient** - Only sends data when needed, not every X seconds
- **Battery friendly** - No constant background requests on mobile

## Testing

### Web Browser

1. Open dashboard: `http://localhost:8000/dashboard`
2. Open browser console (F12)
3. Execute a command on a server
4. Watch console for: `Activity created: {...}`
5. Dashboard should refresh automatically

### NativePHP Mobile

1. Build app: `php artisan native:android` or `native:ios`
2. Run on device/emulator
3. Execute commands
4. Dashboard updates in real-time

## Troubleshooting

### Events not firing in web browser

NativePHP events only work within the NativePHP runtime. For web browsers, you'd need to implement a different solution (like polling or WebSockets).

### Events not received in Livewire

Check browser console for JavaScript errors. Ensure `window.Livewire` is available before dispatching.

### No updates on mobile

Verify the NativePHP facade is available:
```php
dd(class_exists('\Native\Laravel\Facades\Window'));
// Should return true in NativePHP apps
```

## Alternative: Polling Fallback

The application already has a polling fallback (5-minute intervals) that works when NativePHP events are not available:

```php
// Dashboard auto-refresh (fallback)
public bool $autoRefresh = false;
public int $refreshInterval = 300000; // 5 minutes
```

Users can toggle auto-refresh on/off as needed.

---

**Documentation Date:** 2025-11-27
**For:** FluxSSH with NativePHP Mobile Support
