<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MobileDebugger
{
    /**
     * Debug data for mobile environments where dd() doesn't work
     * Uses NativePHP-compatible logging methods
     */
    public static function debug($data, string $label = 'DEBUG')
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $debugMessage = "[$timestamp] $label: " . (is_array($data) || is_object($data) ? json_encode($data, JSON_PRETTY_PRINT) : $data);

        // Use error_log like NativePHP's exception handler
        error_log("[NATIVEPHP_DEBUG]: $debugMessage");

        // Also log to Laravel logs which will be picked up by NativePHP's LogWatcher
        Log::info($debugMessage);

        // Write to debug file as fallback
        $debugFile = storage_path('logs/mobile_debug.log');
        file_put_contents($debugFile, $debugMessage . "\n", FILE_APPEND | LOCK_EX);

        // Force immediate log write for mobile debugging
        if (function_exists('flush')) {
            flush();
        }

        return $data;
    }

    /**
     * Dump and die for mobile - logs then throws exception to stop execution
     */
    public static function dd($data, string $label = 'DD')
    {
        static::debug($data, $label);

        // Use error_log for immediate output
        error_log("[NATIVEPHP_DD]: Debug stop point - data logged above");

        throw new \Exception("Debug stop point - check logs for data");
    }

    /**
     * Create a visible alert in the mobile app interface
     */
    public static function alert($message, $title = 'Debug Alert')
    {
        $alertData = ['title' => $title, 'message' => $message, 'timestamp' => now()];

        static::debug($alertData, 'ALERT');

        // Store in session for display in the UI
        session()->flash('debug_alert', $alertData);

        return $message;
    }
}
