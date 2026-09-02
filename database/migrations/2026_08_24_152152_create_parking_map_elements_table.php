<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_map_elements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->string('type')->comment('text, icon');
            $table->string('content')->comment('text string or fontawesome icon class e.g. fa-lightbulb');
            $table->double('pos_x')->default(100);
            $table->double('pos_y')->default(100);
            $table->double('rotation')->default(0);
            $table->double('scale')->default(1.0);
            $table->string('color')->nullable()->default('#1c3550');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('parking_zones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_map_elements');
    }
};
