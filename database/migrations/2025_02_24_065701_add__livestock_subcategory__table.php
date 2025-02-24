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
        Schema::create('Livestock_Subcategory', function (Blueprint $table) {
            $table->id('sub_id');
            $table->string('sub_name');
            $table->timestamps();

        });

        Schema::table('Livestock_Subcategory', function (Blueprint $table){
            $table->unsignedBigInteger('liv_cat_id');
         
            $table->foreign('liv_cat_id')->references('liv_cat_id')->on('Livestock_category');
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
