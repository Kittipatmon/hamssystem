<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcement', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('title')->comment('หัวข้อประกาศ');
            $table->text('content')->comment('รายละเอียดประกาศ');
            $table->date('published_date')->comment('วันที่ประกาศ');
            $table->text('image_path')->nullable()->comment('รูปภาพประกอบประกาศ (เช่น ป้ายเตือน)');
            $table->tinyInteger('is_urgent')->default(0)->comment('เร่งด่วนหรือไม่');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement');
    }
};
