<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rice', function (Blueprint $table) {
            $table->bigIncrements('rice_id'); // Primary Key (PostgreSQL prefers bigIncrements)
            $table->unsignedBigInteger('grower_id'); // Foreign Key
            
            $table->string('area_type'); // e.g., Irrigated or Rainfed
            $table->string('seed_type'); // e.g., Certified, Hybrid, Good seeds
            
            $table->integer('area_harvested');
            $table->integer('production');
            $table->integer('ave_yield');
            
            $table->timestamps(); // created_at and updated_at
            
            // Define foreign key constraint
            $table->foreign('grower_id')->references('grower_id')->on('growers')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rice');
    }
};
