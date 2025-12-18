<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Info - FluxSSH</title>
    <style>
        body {
            font-family: monospace;
            margin: 20px;
            background: #1a1a1a;
            color: #00ff00;
        }

        .debug-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #333;
            background: #222;
        }

        .debug-title {
            color: #ffff00;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .debug-content {
            white-space: pre-wrap;
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
            font-size: 12px;
            line-height: 1.4;
        }

        .highlight {
            background: #333;
            padding: 2px 5px;
            color: #00ffff;
        }

        .refresh-btn {
            background: #007700;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <button class="refresh-btn" onclick="location.reload()">🔄 Refresh Debug Info</button>

    <h1>🐛 NativePHP Debug Information</h1>

    <div class="debug-section">
        <div class="debug-title">Platform Information</div>
        <div class="debug-content">
            Environment: <span class="highlight">{{ $debugInfo['environment'] }}</span>
            NativePHP Running: <span class="highlight">{{ $debugInfo['nativephp_running'] ? 'YES' : 'NO' }}</span>
            Debug Mode: <span class="highlight">{{ $debugInfo['debug_mode'] ? 'ENABLED' : 'DISABLED' }}</span>
            Platform: <span class="highlight">{{ $debugInfo['platform_info']['nativephp_platform'] }}</span>
            User Agent: {{ $debugInfo['platform_info']['user_agent'] }}
            Is Mobile: <span class="highlight">{{ $debugInfo['platform_info']['is_mobile'] ? 'YES' : 'NO' }}</span>
        </div>
    </div>

    <div class="debug-section">
        <div class="debug-title">Recent Debug Logs</div>
        <div class="debug-content">
            @if (count($debugInfo['recent_logs']) > 0)
                @foreach ($debugInfo['recent_logs'] as $log)
                    {{ trim($log) }}
                @endforeach
            @else
                No debug logs found
            @endif
        </div>
    </div>

    <div class="debug-section">
        <div class="debug-title">Recent Laravel Logs</div>
        <div class="debug-content">
            @if (isset($debugInfo['recent_laravel_logs']) && count($debugInfo['recent_laravel_logs']) > 0)
                @foreach ($debugInfo['recent_laravel_logs'] as $log)
                    {{ trim($log) }}
                @endforeach
            @else
                No Laravel logs found
            @endif
        </div>
    </div>

    <div class="debug-section">
        <div class="debug-title">Session Data</div>
        <div class="debug-content">{{ json_encode($debugInfo['session_data'], JSON_PRETTY_PRINT) }}</div>
    </div>

    <div class="debug-section">
        <div class="debug-title">Request Data</div>
        <div class="debug-content">{{ json_encode($debugInfo['request_data'], JSON_PRETTY_PRINT) }}</div>
    </div>

    <div class="debug-section">
        <div class="debug-title">Timestamp</div>
        <div class="debug-content">{{ $debugInfo['timestamp'] }}</div>
    </div>

    <script>
        // Auto-refresh every 30 seconds for live debugging
        setTimeout(() => location.reload(), 30000);
    </script>
</body>

</html>
