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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('usage_limit')->nullable(); // Optional: limit the number of uses
            $table->integer('usage_count')->default(0); // Track how many times it has been used
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
