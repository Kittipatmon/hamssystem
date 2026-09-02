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
        Schema::table('visitor_reservations', function (Blueprint $table) {
            $table->dateTime('manager_approved_at')->nullable()->after('manager_approved_by');
            $table->dateTime('hams_acknowledged_at')->nullable()->after('hams_acknowledged_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_reservations', function (Blueprint $table) {
            $table->dropColumn(['manager_approved_at', 'hams_acknowledged_at']);
        });
    }
};
