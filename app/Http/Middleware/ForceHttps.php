<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if running behind a proxy (like Railway)
        $isSecure = $request->secure() || 
                    $request->header('X-Forwarded-Proto') === 'https' ||
                    $request->header('X-Forwarded-Ssl') === 'on';
        
        if (!$isSecure && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
