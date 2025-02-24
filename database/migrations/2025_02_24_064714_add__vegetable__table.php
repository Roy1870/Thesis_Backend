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
        Schema::create('Vegetable', function (Blueprint $table) {
            $table->id('veg_id');
            $table->timestamps();

        });

        Schema::table('Vegetable', function (Blueprint $table){
            $table->unsignedBigInteger('veg_cat_id');
            $table->unsignedBigInteger('sub_id');
            $table->unsignedBigInteger('vegrec_id');
            $table->unsignedBigInteger('g_id');
         
            $table->foreign('veg_cat_id')->references('veg_cat_id')->on('Vegetable_Category');
            $table->foreign('sub_id')->references('sub_id')->on('Veg_sub_category');
            $table->foreign('vegrec_id')->references('vegrec_id')->on('Vegetable_Record');
            $table->foreign('g_id')->references('g_id')->on('Growers');
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
