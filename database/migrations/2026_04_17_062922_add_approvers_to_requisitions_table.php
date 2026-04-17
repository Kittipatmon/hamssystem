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
            $table->unsignedBigInteger('commander_id')->nullable()->after('requester_comment');
            $table->integer('commander_status')->default(0)->after('commander_id');
            $table->text('commander_comment')->nullable()->after('commander_status');
            $table->timestamp('commander_date')->nullable()->after('commander_comment');

            $table->unsignedBigInteger('managerhams_id')->nullable()->after('commander_date');
            $table->integer('managerhams_status')->default(0)->after('managerhams_id');
            $table->text('managerhams_comment')->nullable()->after('managerhams_status');
            $table->timestamp('managerhams_date')->nullable()->after('managerhams_comment');

            $table->unsignedBigInteger('Committee_id')->nullable()->after('managerhams_date');
            $table->integer('Committee_status')->default(0)->after('Committee_id');
            $table->text('Committee_comment')->nullable()->after('Committee_status');
            $table->timestamp('Committee_date')->nullable()->after('Committee_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn([
                'commander_id', 'commander_status', 'commander_comment', 'commander_date',
                'managerhams_id', 'managerhams_status', 'managerhams_comment', 'managerhams_date',
                'Committee_id', 'Committee_status', 'Committee_comment', 'Committee_date'
            ]);
        });
    }
};
