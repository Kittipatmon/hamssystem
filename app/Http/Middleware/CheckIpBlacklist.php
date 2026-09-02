<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIpBlacklist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        
        $isBanned = \App\Models\IpBlacklist::where('ip_address', $ip)->exists();
        
        if ($isBanned) {
            abort(403, 'Your IP address has been blocked from accessing this system.');
        }

        return $next($request);
    }
}
