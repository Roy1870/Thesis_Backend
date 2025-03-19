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
        Schema::create('growers', function (Blueprint $table) {
            $table->id('grower_id'); // Primary Key
            $table->unsignedBigInteger('farmer_id'); // Foreign Key
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
        Schema::dropIfExists('growers');
    }
};
