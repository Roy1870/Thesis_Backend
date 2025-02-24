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
        Schema::create('Raisers', function (Blueprint $table) {
            $table->id('r_id');
            $table->string('species');
            $table->integer('remarks');
            $table->timestamps();

        });

        Schema::table('Raisers', function (Blueprint $table){
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
