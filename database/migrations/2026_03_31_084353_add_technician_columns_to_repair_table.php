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
        Schema::table('residence_repairs', function (Blueprint $table) {
            $table->json('technician_images')->nullable()->after('completion_date');
            $table->text('technician_note')->nullable()->after('technician_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residence_repairs', function (Blueprint $table) {
            $table->dropColumn(['technician_images', 'technician_note']);
        });
    }
};
