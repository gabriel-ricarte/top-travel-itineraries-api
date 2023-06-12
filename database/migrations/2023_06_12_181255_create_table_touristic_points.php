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
        Schema::create('touristic_points', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('description');
            $table->string('latitude', 30);
            $table->string('longitude', 30);
            $table->foreignId('cityId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('touristic_points');
    }
};
