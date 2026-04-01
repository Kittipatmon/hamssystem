<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * รายชื่อกรรมการบ้านพักพนักงาน (Organization Chart)
 * 
 * @property int $user_id - อ้างอิง ID ของพนักงาน
 * @property string $role - ตำแหน่ง (หัวหน้าบ้านพัก, ผู้ช่วยหัวหน้าบ้านพัก)
 * @property int $order - ลำดับการแสดงผลในผังองค์กร
 */
class HousingCommittee extends Model
{
    protected $table = 'housing_committees';
    protected $fillable = [
        'user_id', // ไอดีอ้างอิงพนักงาน
        'role',    // ตำแหน่งกรรมการ
        'order',   // ลำดับแสดงผล
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
