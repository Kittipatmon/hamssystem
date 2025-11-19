<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class MicrosoftAuthController extends Controller
{
    public function redirect()
    {
        // ✅ แก้ไขจุดที่ 1: เพิ่ม 'Mail.Send' เพื่อขอสิทธิ์ส่งเมล
        $scopes = ['offline_access', 'User.Read', 'Mail.Send'];
        
        return Socialite::driver('azure')->scopes($scopes)->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $oauthUser = Socialite::driver('azure')->user();
            
            // เก็บข้อมูลเข้า Session
            session([
                'ms_oauth' => [
                    'id' => $oauthUser->getId(),
                    'name' => $oauthUser->getName(),
                    'email' => $oauthUser->getEmail(),
                    'token' => $oauthUser->token,
                    'refreshToken' => $oauthUser->refreshToken ?? null,
                    'expiresIn' => $oauthUser->expiresIn ?? null,
                ]
            ]);

            // ตรวจสอบว่ามี News ID ที่รอดำเนินการส่งหรือไม่ (กรณี Login เพื่อมาส่งโดยเฉพาะ)
            $newsId = session('post_login_notify_news_id');
            
            if ($newsId) {
                session()->forget('post_login_notify_news_id');
                
                // Redirect ไปยัง Route ที่ทำหน้าที่ส่งเมลจริง
                return redirect()->route('datamanage.news.notifyOutlook.afterLogin', ['news' => $newsId])
                    ->with('success', 'เข้าสู่ระบบ Microsoft สำเร็จ กำลังดำเนินการส่งอีเมลแจ้งเตือน...');
            }

            // กรณี Login ปกติ ไม่ได้กดมาจากปุ่มส่งข่าว
            return redirect()->route('datamanage.news.index')
                ->with('success', 'เข้าสู่ระบบ Microsoft สำเร็จ');

        } catch (\Throwable $e) {
            // ✅ แก้ไขจุดที่ 2: เปลี่ยน key เป็น 'error' เพื่อให้ Frontend แสดงสีแดง (ถ้า Template รองรับ)
            return redirect()->route('datamanage.news.index')
                ->with('error', 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ Microsoft: ' . $e->getMessage());
        }
    }
}