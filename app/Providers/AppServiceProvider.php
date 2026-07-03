<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ---------------------------------------------------------
// 1. เพิ่ม 3 บรรทัดนี้ที่ส่วนบนสุด (ใต้ namespace)
// ---------------------------------------------------------
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Azure\AzureExtendSocialite;

use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ปล่อยให้ Laravel จัดการ Scheme ตาม Request จริง (เข้า HTTP เป็น HTTP, เข้า HTTPS เป็น HTTPS)

        // ---------------------------------------------------------
        // 2. เพิ่มโค้ดนี้ในฟังก์ชัน boot()
        // ---------------------------------------------------------
        Event::listen(
            SocialiteWasCalled::class,
            [AzureExtendSocialite::class, 'handle']
        );
    }
}