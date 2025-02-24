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
        Schema::create('Growers', function (Blueprint $table) {
            $table->id('g_id');
            $table->string('crop_name');
            $table->integer('area_hectares');
            $table->integer('yield');
            $table->string('season');
            $table->string('market_outlet');
            $table->timestamps();
        });

        Schema::table('Growers', function (Blueprint $table){
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
