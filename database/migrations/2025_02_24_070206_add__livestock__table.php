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
        Schema::create('Livestock', function (Blueprint $table) {
            $table->id('liv_id');
            $table->timestamps();

        });

        Schema::table('Livestock', function (Blueprint $table){
            $table->unsignedBigInteger('liv_cat_id');
            $table->unsignedBigInteger('sub_id');
            $table->unsignedBigInteger('rec_id');
            $table->unsignedBigInteger('r_id');
         
            $table->foreign('liv_cat_id')->references('liv_cat_id')->on('Livestock_category');
            $table->foreign('sub_id')->references('sub_id')->on('Livestock_Subcategory');
            $table->foreign('rec_id')->references('rec_id')->on('Livestock_Record');
            $table->foreign('r_id')->references('r_id')->on('Raisers');
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
