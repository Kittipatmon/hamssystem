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
        Schema::create('policy', function (Blueprint $description) {
            $description->id();
            $description->string('title');
            $description->text('content')->nullable();
            $description->string('type')->comment('policy or operation');
            $description->integer('order')->default(0);
            $description->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy');
    }
};
