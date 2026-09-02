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
            $table->enum('manager_approval', ['pending', 'approved', 'rejected'])->default('pending')->after('checkout_datetime');
            $table->bigInteger('manager_approved_by')->nullable()->after('manager_approval');
            $table->enum('hams_status', ['pending', 'acknowledged'])->default('pending')->after('manager_approved_by');
            $table->bigInteger('hams_acknowledged_by')->nullable()->after('hams_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_reservations', function (Blueprint $table) {
            $table->dropColumn(['manager_approval', 'manager_approved_by', 'hams_status', 'hams_acknowledged_by']);
        });
    }
};
