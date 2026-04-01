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
        Schema::create('residence_repairs', function (Blueprint $table) {
            $table->id();
            $table->string('repair_code')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('residence_room_id');
            $table->string('title');
            $table->text('description');
            $table->json('images')->nullable();
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: Pending, 1: Process, 2: Done, 3: Cancel');
            $table->text('admin_comment')->nullable();
            $table->date('repair_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residence_repairs');
    }
};
