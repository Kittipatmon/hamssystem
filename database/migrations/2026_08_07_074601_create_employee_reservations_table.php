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
        Schema::create('employee_reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // Employee ID
            $table->string('car_registration');
            $table->foreignId('slot_id')->nullable()->constrained('parking_slots')->onDelete('set null');
            $table->bigInteger('dept_id')->nullable();
            $table->dateTime('checkin_datetime');
            $table->dateTime('checkout_datetime')->nullable();
            $table->enum('manager_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->bigInteger('manager_approved_by')->nullable();
            $table->dateTime('manager_approved_at')->nullable();
            $table->enum('hams_status', ['pending', 'acknowledged'])->default('pending');
            $table->bigInteger('hams_acknowledged_by')->nullable();
            $table->dateTime('hams_acknowledged_at')->nullable();
            $table->enum('status', ['reserved', 'checked_in', 'checked_out', 'cancelled'])->default('reserved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_reservations');
    }
};
