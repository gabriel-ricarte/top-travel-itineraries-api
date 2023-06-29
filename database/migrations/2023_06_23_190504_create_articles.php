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
    {Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('snake_case_name')->unique();
            $table->text('description');
            $table->text('activities');
            $table->string('hours');
            $table->string('admission');
            $table->foreignId('countryId');
            $table->foreignId('cityId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};