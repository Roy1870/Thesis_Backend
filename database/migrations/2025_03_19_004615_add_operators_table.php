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
        Schema::create('operators', function (Blueprint $table) {
            $table->id('profile_id'); // Primary Key
            $table->unsignedBigInteger('farmer_id'); // Foreign Key
            $table->string('fishpond_location');
            $table->string('geotagged_photo_url')->nullable();
            $table->string('cultured_species');
            $table->float('productive_area_sqm')->nullable();
            $table->float('stocking_density')->nullable();
            $table->date('date_of_stocking')->nullable();
            $table->float('production_kg')->nullable();
            $table->date('date_of_harvest')->nullable();
            $table->string('operational_status');
            $table->string('remarks')->nullable();
            $table->timestamps(); // created_at & updated_at

            // Foreign Key Constraint
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
