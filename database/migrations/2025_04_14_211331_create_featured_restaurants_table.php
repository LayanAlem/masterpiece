<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0); // For controlling the order on the homepage
            $table->decimal('payment_amount', 10, 2)->nullable(); // Amount paid for the feature
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_restaurants');
    }
};
