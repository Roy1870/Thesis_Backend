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
        Schema::create('Livestock_category', function (Blueprint $table) {
            $table->id('liv_cat_id');
            $table->string('category_name');
            $table->timestamps();

        });

        Schema::table('Livestock_category', function (Blueprint $table){
            $table->unsignedBigInteger('r_id');
         
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
