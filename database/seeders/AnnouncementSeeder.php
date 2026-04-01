<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('announcement')->truncate();

        DB::table('announcement')->insert([
            [
                'announcement_id' => 2,
                'title' => 'ออกแบบ UI',
                'content' => 'ใช้เวลาการออกแบบประมาณ 2 เดือน ถ้าหากเกินกำหนดสามารถสอบถามได้ว่าเพราะอะไร',
                'published_date' => '2025-05-29',
                'image_path' => 'images/announcements/1749009373_683fc3dd03886.jpg',
                'is_urgent' => 1,
                'created_at' => '2025-05-28 07:45:28',
                'updated_at' => '2025-06-06 08:19:20',
            ],
            [
                'announcement_id' => 6,
                'title' => 'testing',
                'content' => 'testtttttttttttt',
                'published_date' => '2025-06-13',
                'image_path' => 'images/announcements/1749809862_684bfac6a6c06.jpg',
                'is_urgent' => 1,
                'created_at' => '2025-06-13 10:17:42',
                'updated_at' => '2025-06-13 10:17:42',
            ],
            [
                'announcement_id' => 7,
                'title' => 'testing',
                'content' => 'testtttttttttttttttttttttttttttttttttttttttt',
                'published_date' => '2025-06-16',
                'image_path' => 'images/announcements/1749809922_684bfb0260857.jpg',
                'is_urgent' => 1,
                'created_at' => '2025-06-13 10:18:42',
                'updated_at' => '2025-06-13 10:18:42',
            ],
            [
                'announcement_id' => 16,
                'title' => 'เจ้าหน้าที่ Coway เข้าดำเนินการ',
                'content' => "เจ้าหน้าที่ Coway เข้าดำเนินการเปลี่ยนไส้กรองเครื่องกดน้ำร้อน-น้ำเย็น\r\nบริเวณชั้น 2,3,5,6 จึงทำให้ไม่สามารถใช้งานได้ในขณะนี้\r\nขออภัยในความไม่สะดวก",
                'published_date' => '2025-07-18',
                'image_path' => 'images/announcements/1752826786_687a03a2dd701.jpg',
                'is_urgent' => 1,
                'created_at' => '2025-07-18 08:19:46',
                'updated_at' => '2025-07-18 08:19:46',
            ],
            [
                'announcement_id' => 17,
                'title' => 'ออกแบบreport',
                'content' => 'testtt',
                'published_date' => '2025-07-29',
                'image_path' => 'images/announcements/1753764132_68885124cff79.jpg',
                'is_urgent' => 1,
                'created_at' => '2025-07-29 04:42:12',
                'updated_at' => '2025-07-29 04:42:12',
            ],
        ]);
    }
}
