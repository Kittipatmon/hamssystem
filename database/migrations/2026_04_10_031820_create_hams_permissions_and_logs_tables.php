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
        Schema::create('hams_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index(); // ID matches employees table
            $table->boolean('is_hams_editor')->default(false);
            $table->timestamps();
        });

        Schema::create('hams_permission_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('target_user_id')->index();
            $table->integer('granted_by_user_id')->index();
            $table->string('action'); // 'granted', 'revoked'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hams_permissions');
        Schema::dropIfExists('hams_permission_logs');
    }
};
