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
        Schema::table('special_offers', function (Blueprint $table) {
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('slug')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('slug');
            $table->dropColumn('image_alt');
        });
    }
};
