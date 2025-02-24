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
        Schema::create('Veg_sub_category', function (Blueprint $table) {
            $table->id('sub_id');
            $table->string('sub_name');
            $table->timestamps();

        });

        Schema::table('Veg_sub_category', function (Blueprint $table){
            $table->unsignedBigInteger('veg_cat_id');
         
            $table->foreign('veg_cat_id')->references('veg_cat_id')->on('Vegetable_Category');
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
