<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('raisers', function (Blueprint $table) {
            $table->id('raiser_id'); // Primary Key
            $table->unsignedBigInteger('farmer_id'); // Foreign Key
            $table->string('location'); // Purok or area name
            $table->string('updated_by'); // Person who last updated the data
            $table->string('remarks')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raisers');
    }
};
