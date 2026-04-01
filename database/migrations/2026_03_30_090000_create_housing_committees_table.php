<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('housing_committees', function (Blueprint $row) {
            $row->id();
            $row->unsignedBigInteger('user_id')->comment('ไอดีอ้างอิงพนักงาน (User ID)');
            $row->string('role')->nullable()->comment('ตำแหน่งกรรมการ (เช่น หัวหน้าบ้านพัก, ผู้ช่วยหัวหน้าบ้านพัก)');
            $row->integer('order')->default(0)->comment('ลำดับการแสดงผลในผังองค์กร');
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('housing_committees');
    }
};
