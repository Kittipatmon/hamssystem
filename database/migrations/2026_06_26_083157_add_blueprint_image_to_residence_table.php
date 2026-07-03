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
        Schema::table('residence', function (Blueprint $table) {
            $table->string('blueprint_image', 255)->nullable()->after('address')->comment('รูปแผนผังรายละเอียดของอาคาร');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residence', function (Blueprint $table) {
            $table->dropColumn('blueprint_image');
        });
    }
};
