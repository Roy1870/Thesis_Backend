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
        Schema::create('Vegetable_Category', function (Blueprint $table) {
            $table->id('veg_cat_id');
            $table->string('veg_category_name');
            $table->timestamps();

        });

        Schema::table('Vegetable_Category', function (Blueprint $table){
            $table->unsignedBigInteger('g_id');
         
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
