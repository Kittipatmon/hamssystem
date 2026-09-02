<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RealIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract real IP from common proxy headers
        $realIp = null;
        
        if ($request->headers->has('CF-Connecting-IP')) {
            $realIp = $request->header('CF-Connecting-IP');
        } elseif ($request->headers->has('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            $realIp = trim($ips[0]);
        } elseif ($request->headers->has('X-Real-IP')) {
            $realIp = $request->header('X-Real-IP');
        }

        if ($realIp) {
            $request->server->set('REMOTE_ADDR', $realIp);
            // Also override the X-Forwarded-For so Laravel's native ip() method picks it up properly
            // when trustProxies is set to *
            $request->headers->set('X-Forwarded-For', $realIp);
        }

        return $next($request);
    }
}
