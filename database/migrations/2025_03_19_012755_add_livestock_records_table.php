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
        Schema::create('livestock_records', function (Blueprint $table) {
            $table->id('record_id'); // Primary Key
            $table->unsignedBigInteger('raiser_id'); // Foreign Key

            $table->string('animal_type'); // e.g., Cattle, Carabao, Goat, Chicken, etc.
            $table->string('subcategory'); // e.g., Carabull, Doe, Sow, Broiler, etc.
            $table->integer('quantity'); // Quantity of livestock

            $table->timestamps(); // created_at & updated_at

            // Foreign Key Constraint
            $table->foreign('raiser_id')->references('raiser_id')->on('raisers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock_records');
    }
};
