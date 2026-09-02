<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? $event->credentials['username'] ?? 'unknown';
        $ip = request()->ip();

        // Count failed attempts in the last 15 minutes for this IP/email
        $recentFails = \Spatie\Activitylog\Models\Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->where(function($query) use ($ip, $email) {
                $query->where('properties->ip', $ip)
                      ->orWhere('properties->email', $email);
            })->count();

        $isRisk = $recentFails >= 2; // This is the 3rd or more attempt

        activity('login_failed')
            ->withProperties([
                'email' => $email,
                'ip' => $ip,
                'risk' => $isRisk,
                'attempts' => $recentFails + 1,
                'user_agent' => request()->userAgent()
            ])
            ->log($isRisk ? 'Multiple failed login attempts detected (Risk)' : 'Failed login attempt');
    }
}
