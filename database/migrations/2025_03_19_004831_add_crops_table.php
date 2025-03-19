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
        Schema::create('crops', function (Blueprint $table) {
            $table->id('crop_id'); // Primary Key
            $table->unsignedBigInteger('grower_id'); // Foreign Key

            $table->string('crop_type'); // e.g., Vegetable, Banana, Cacao
            $table->string('variety_clone')->nullable(); // For Cacao (nullable)
            $table->float('area_hectare'); // Area in hectares
            $table->string('production_type'); // Monthly or Total Production
            $table->json('production_data'); // Production quantities stored as JSON

            $table->timestamps(); // created_at & updated_at

            // Foreign Key Constraint
            $table->foreign('grower_id')->references('grower_id')->on('growers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crops');
    }
};
