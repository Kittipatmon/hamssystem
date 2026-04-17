<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use App\Models\serviceshams\Requisitions;

class RequisitionConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Requisitions $requisition)
    {
    }

    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $req = $this->requisition->loadMissing(['user.department']);
        $user = $req->user;

        $fullname = $user?->fullname ?: '-';
        $position = $user?->position ?: '-';
        $department = $user?->department?->department_name
            ?? $user?->division?->division_name
            ?? $user?->section?->section_code
            ?? '-';

        $itemsCount = $req->requisition_items()->count();
        $total = number_format((float)($req->total_price ?? 0), 2);
        $code = $req->requisitions_code ?: ($req->request_number ?: '-');
        $date = now()->format('d/m/Y H:i');
        $remarks = trim((string)($req->remarks ?? '')) ?: '-';

        $lines = [
            '📁 มีการยืนยันการเบิกอุปกรณ์',
            "เลขที่เบิก: {$code}",
            "ผู้ขอเบิก: {$fullname} แผนก: {$department} ตำแหน่ง: {$position} เบอร์โทร: -",
            "จำนวนรายการ: {$itemsCount}",
            "ยอดรวม: {$total} บาท",
            "วันที่: {$date}",
            "หมายเหตุ: {$remarks}",
            '',
            'กรุณาอย่าลืมตรวจสอบรายการเบิกในระบบเพื่อความถูกต้อง',
        ];

        $text = implode("\n", $lines);

        // When used with Notification::route('telegram', <chat_id>)
        // the recipient is provided by the route, so we only set content.
        return TelegramMessage::create()
            ->content($text);
    }
}
