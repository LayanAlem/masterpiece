<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_type_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->integer('capacity');
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('season', ['winter', 'spring', 'summer', 'autumn']);
            $table->boolean('is_family_friendly')->default(false);
            $table->boolean('is_accessible')->default(false);
            $table->boolean('has_images')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        // Create table for activity images
        Schema::create('activity_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_images');
        Schema::dropIfExists('activities');
    }
};
