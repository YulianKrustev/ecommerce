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
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('offer_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_offer_id')->constrained('special_offers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('offer_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_offer_id')->constrained('special_offers')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_product');
        Schema::dropIfExists('offer_category');
        Schema::dropIfExists('special_offers');
    }
};
