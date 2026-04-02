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
        Schema::table('residence_leaves', function (Blueprint $table) {
            $table->string('residence_type', 255)->change();
            $table->string('room_number', 50)->change();
            $table->string('floor', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residence_leaves', function (Blueprint $table) {
            // Keep it at 255 to avoid going back to small sizes
        });
    }
};
