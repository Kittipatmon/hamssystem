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
        Schema::table('parking_slots', function (Blueprint $table) {
            if (!Schema::hasColumn('parking_slots', 'pos_x')) {
                $table->double('pos_x')->default(100)->after('status');
            }
            if (!Schema::hasColumn('parking_slots', 'pos_y')) {
                $table->double('pos_y')->default(100)->after('pos_x');
            }
            if (!Schema::hasColumn('parking_slots', 'rotation')) {
                $table->double('rotation')->default(0)->after('pos_y');
            }
            if (!Schema::hasColumn('parking_slots', 'width')) {
                $table->double('width')->default(34)->after('rotation');
            }
            if (!Schema::hasColumn('parking_slots', 'height')) {
                $table->double('height')->default(76)->after('width');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parking_slots', function (Blueprint $table) {
            $table->dropColumn(['pos_x', 'pos_y', 'rotation', 'width', 'height']);
        });
    }
};
