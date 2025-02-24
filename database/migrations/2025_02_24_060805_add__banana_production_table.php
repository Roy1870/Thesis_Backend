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
        Schema::create('Banana_production', function (Blueprint $table) {
            $table->id('b_id');
            $table->integer('area');
            $table->integer('lakatan_cnt')->default(0);
            $table->integer('latundan_cnt')->default(0);
            $table->integer('cardava_cnt')->default(0);
            $table->string('month');
            $table->integer('year');
            $table->string('remarks');
            $table->timestamps();

        });

        Schema::table('Banana_production', function (Blueprint $table){
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
