<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // ดึง Scheme จาก Referer เพื่อให้รู้ว่า User เข้ามาจาก HTTP หรือ HTTPS จริงๆ (แก้ปัญหา Proxy หลอก)
        $referer = $request->header('referer');
        $scheme = (strpos($referer, 'https://') === 0) ? 'https://' : 'http://';
        $host = $request->getHttpHost();
        
        // สร้าง URL ปลายทางที่บังคับ Scheme ตามที่ User ใช้จริง
        $intendedUrl = $scheme . $host . '/';

        return redirect($intendedUrl)
            ->with('login-success', 'ยินดีต้อนรับเข้าสู่ระบบคุณ ' . Auth::user()->fullname);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $referer = $request->header('referer');
        $scheme = (strpos($referer, 'https://') === 0) ? 'https://' : 'http://';
        $host = $request->getHttpHost();

        return redirect($scheme . $host . '/')->with('logout-success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}
