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
        Schema::create('Vegetable_Record', function (Blueprint $table) {
            $table->id('vegrec_id');
            $table->integer('quantity');
            $table->integer('year');
            $table->string('month');
            $table->string('remarks');
            $table->timestamps();

        });

        Schema::table('Vegetable_Record', function (Blueprint $table){
            $table->unsignedBigInteger('sub_id');
         
            $table->foreign('sub_id')->references('sub_id')->on('Veg_sub_category');
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
