<?php

namespace App\Http\Middleware;

use App\Services\MobileDebugger;
use Closure;
use Illuminate\Http\Request;

class DebugRegisterRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Debug only registration requests
        if ($request->is('register') && $request->isMethod('POST')) {
            MobileDebugger::debug([
                'url' => $request->url(),
                'method' => $request->method(),
                'all_input' => $request->all(),
                'headers' => array_filter($request->headers->all(), function ($key) {
                    return !in_array(strtolower($key), ['authorization', 'cookie']);
                }, ARRAY_FILTER_USE_KEY),
                'user_agent' => $request->userAgent(),
                'has_name' => $request->has('name'),
                'has_email' => $request->has('email'),
                'has_password' => $request->has('password'),
                'name_value' => $request->input('name', 'NOT_PROVIDED'),
                'email_value' => $request->input('email', 'NOT_PROVIDED'),
                'content_type' => $request->header('Content-Type'),
                'request_size' => strlen($request->getContent()),
            ], 'Registration Request Debug');
        }

        $response = $next($request);

        // Also debug the response for registration requests
        if ($request->is('register') && $request->isMethod('POST')) {
            MobileDebugger::debug([
                'status_code' => $response->getStatusCode(),
                'response_headers' => array_filter($response->headers->all(), function ($key) {
                    return !in_array(strtolower($key), ['set-cookie']);
                }, ARRAY_FILTER_USE_KEY),
                'has_validation_errors' => session()->has('errors'),
                'validation_errors' => session('errors') ? session('errors')->all() : [],
            ], 'Registration Response Debug');
        }

        return $response;
    }
}
