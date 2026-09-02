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
        Schema::table('hams_permissions', function (Blueprint $table) {
            $table->string('role')->default('viewer')->after('is_hams_editor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hams_permissions', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
