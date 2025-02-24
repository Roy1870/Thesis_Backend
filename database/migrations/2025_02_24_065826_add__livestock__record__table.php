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
        Schema::create('Livestock_Record', function (Blueprint $table) {
            $table->id('rec_id');
            $table->integer('quantity');
            $table->integer('year');
            $table->string('month');
            $table->string('remarks');
            $table->timestamps();

        });

        Schema::table('Livestock_Record', function (Blueprint $table){
            $table->unsignedBigInteger('sub_id');
         
            $table->foreign('sub_id')->references('sub_id')->on('Livestock_Subcategory');
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
