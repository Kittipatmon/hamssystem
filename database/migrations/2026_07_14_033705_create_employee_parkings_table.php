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
        Schema::create('employee_parkings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id'); // Referencing users(id) without strict foreign key
            $table->string('car_registration');
            $table->foreignId('slot_id')->nullable()->constrained('parking_slots')->onDelete('set null');
            $table->dateTime('time_in');
            $table->dateTime('time_out')->nullable();
            $table->enum('status', ['parking', 'left'])->default('parking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_parkings');
    }
};
