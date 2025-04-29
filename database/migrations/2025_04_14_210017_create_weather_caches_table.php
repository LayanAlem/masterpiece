<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_caches', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->json('weather_data');
            $table->date('cache_date');
            $table->timestamps();

            $table->unique(['location', 'cache_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_cache');
    }
};
