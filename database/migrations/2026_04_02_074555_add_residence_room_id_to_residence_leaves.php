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
        Schema::table('residence_leaves', function (Blueprint $table) {
            $table->unsignedInteger('residence_room_id')->nullable()->after('user_id')->comment('รหัสห้องจากตาราง residence_room');
        });

        // Loop through existing records to populate residence_room_id
        $leaves = DB::table('residence_leaves')->get();
        foreach ($leaves as $leave) {
            $room = DB::table('residence_room')
                ->join('residence', 'residence_room.residence_id', '=', 'residence.residence_id')
                ->where('residence_room.room_number', $leave->room_number)
                ->where('residence.name', $leave->residence_type)
                ->select('residence_room.residence_room_id')
                ->first();

            if ($room) {
                DB::table('residence_leaves')
                    ->where('residence_leaves_id', $leave->residence_leaves_id)
                    ->update(['residence_room_id' => $room->residence_room_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('residence_leaves', function (Blueprint $table) {
            $table->dropColumn('residence_room_id');
        });
    }
};
