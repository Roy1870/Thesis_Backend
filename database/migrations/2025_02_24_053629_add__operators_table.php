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
        Schema::create('Operators', function (Blueprint $table) {
            $table->id('o_id');
            $table->string('fishpond_location');
            $table->string('cultured_species');
            $table->integer('productive_area');
            $table->integer('stocking_density');
            $table->integer('production');
            $table->date('harvest_date');
            $table->string('month');
            $table->integer('year');
            $table->timestamps();

        });

        Schema::table('Operators', function (Blueprint $table){
            $table->unsignedBigInteger('farmer_id');
         
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
