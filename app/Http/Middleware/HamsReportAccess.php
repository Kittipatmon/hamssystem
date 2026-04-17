<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HamsReportAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (!auth()->check()) {
            abort(401);
        }

        $user = auth()->user();

        // 2. Check for Role Admin OR Departments 14, 16
        // Department 14: Human Assets Management & Service Building
        // Department 16: Information Communication Technology
        if ($user->role === 'admin' || in_array($user->dept_id, [14, 16]) || $user->is_hams_editor) {
            return $next($request);
        }

        // 3. Otherwise, return 403 Forbidden
        abort(403);
    }
}
