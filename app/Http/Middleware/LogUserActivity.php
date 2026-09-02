<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $expiresAt = now()->addMinutes(5); // Keep online for 5 minutes
            Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);
            Cache::put('user-last-activity-' . Auth::user()->id, now(), $expiresAt);
        }

        $oldRole = null;
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if (preg_match('/users\/(\d+)/', $request->path(), $matches)) {
                $userId = $matches[1];
                $targetUser = \App\Models\User::find($userId);
                if ($targetUser) {
                    $oldRole = $targetUser->role;
                }
            }
        }

        $response = $next($request);

        // Log all mutations (POST, PUT, PATCH, DELETE) to record every action
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $input = $request->except(['password', 'password_confirmation', '_token', '_method']);
            
            $causer = Auth::user();
            $causerName = $causer ? $causer->fullname : 'ผู้เยี่ยมชม (Guest)';
            
            $description = $this->getFriendlyDescription($request->method(), $request->path(), $input, $causerName, $oldRole);
            
            activity('user_action')
                ->causedBy($causer)
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'input' => $input,
                    'status_code' => $response->getStatusCode()
                ])
                ->log($description);
        }

        return $response;
    }

    /**
     * Map technical routes and methods to a friendly Thai description.
     */
    private function getFriendlyDescription($method, $path, $input, $causerName, $oldRole = null)
    {
        $action = '';
        switch (strtoupper($method)) {
            case 'POST': $action = 'เพิ่ม/สร้าง'; break;
            case 'PUT':
            case 'PATCH': $action = 'แก้ไข/ปรับปรุง'; break;
            case 'DELETE': $action = 'ลบ'; break;
        }

        // Check specific patterns
        if (str_contains($path, 'users') && (str_contains($path, 'role') || isset($input['role']))) {
            $roleMap = [
                'admin' => 'ผู้ดูแลระบบ (Admin)',
                'editor' => 'ผู้แก้ไข (Editor)',
                'viewer' => 'ผู้เข้าชม (Viewer)',
                'security' => 'เจ้าหน้าที่รักษาความปลอดภัย (Security)',
                'user' => 'ผู้ใช้งานทั่วไป (User)'
            ];
            $targetRole = $input['role'] ?? '';
            $roleThai = $roleMap[$targetRole] ?? $targetRole;
            
            $reason = $input['reason'] ?? '';
            $reasonText = $reason ? " (เหตุผล: {$reason})" : '';

            if ($oldRole && $oldRole !== $targetRole) {
                $oldRoleThai = $roleMap[$oldRole] ?? $oldRole;
                return "{$causerName} ได้เปลี่ยนสิทธิ์ของพนักงาน จาก {$oldRoleThai} เป็น {$roleThai}{$reasonText}";
            }
            
            return "{$causerName} ได้เปลี่ยนระดับสิทธิ์ของพนักงานเป็น: {$roleThai}{$reasonText}";
        }

        if (str_contains($path, 'users')) {
            return "{$causerName} ได้ทำการ{$action}ข้อมูลพนักงาน";
        }

        if (str_contains($path, 'departments')) {
            return "{$causerName} ได้ทำการ{$action}ข้อมูลแผนก";
        }

        if (str_contains($path, 'security-alerts/toggle-ban')) {
            $isBanned = $input['is_banned'] ?? false;
            $ip = $input['ip'] ?? '';
            $statusText = $isBanned ? 'บล็อก (Blacklist)' : 'ปลดบล็อก';
            return "{$causerName} ได้ทำการ{$statusText} IP Address: {$ip}";
        }

        if (str_contains($path, 'parking')) {
            return "{$causerName} ได้ทำการ{$action}ข้อมูลรายการจองที่จอดรถ";
        }

        if (str_contains($path, 'policy')) {
            return "{$causerName} ได้ทำการ{$action}เอกสารนโยบาย/ขั้นตอน";
        }

        return "{$causerName} ทำรายการ {$method} ที่เส้นทาง {$path}";
    }
}
