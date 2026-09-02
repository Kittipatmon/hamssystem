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
        Schema::create('visitor_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('car_registration');
            $table->bigInteger('contact_user_id'); // Referencing users(id) without strict foreign key
            $table->dateTime('checkin_datetime');
            $table->dateTime('checkout_datetime')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->foreignId('slot_id')->nullable()->constrained('parking_slots')->onDelete('set null');
            $table->enum('status', ['reserved', 'checked_in', 'checked_out', 'cancelled'])->default('reserved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_reservations');
    }
};
