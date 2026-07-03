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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->index('approve_status');
            $table->index('status');
            $table->index('requester_id');
            $table->index('packing_staff_status');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropIndex(['approve_status']);
            $table->dropIndex(['status']);
            $table->dropIndex(['requester_id']);
            $table->dropIndex(['packing_staff_status']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
