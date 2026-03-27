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
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'last_maintenance_mileage')) {
                $table->integer('last_maintenance_mileage')->default(0)->after('latest_mileage');
            }
            if (!Schema::hasColumn('vehicles', 'next_maintenance_mileage')) {
                $table->integer('next_maintenance_mileage')->default(10000)->after('last_maintenance_mileage');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['last_maintenance_mileage', 'next_maintenance_mileage']);
        });
    }
};
