<?php

namespace App\Http\Middleware;

use App\Support\NativeApp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectLandingForNativeApp
{
    /**
     * If the request is coming from a NativePHP Mobile app (Android/iOS),
     * send guests to the login screen instead of the marketing landing page.
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     // if (NativeApp::isNativeMobile($request)) {
    //     //     return redirect()->route('login');
    //     // }

    //     // return $next($request);
    // }
}
