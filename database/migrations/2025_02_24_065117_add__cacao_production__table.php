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
        Schema::create('Cacao_production', function (Blueprint $table) {
            $table->id('c_id');
            $table->integer('area');
            $table->string('variety');
            $table->integer('jan_cnt')->default(0);
            $table->integer('feb_cnt')->default(0);
            $table->integer('march_cnt')->default(0);
            $table->integer('april_cnt')->default(0);
            $table->integer('may_cnt')->default(0);
            $table->integer('june_cnt')->default(0);
            $table->integer('july_cnt')->default(0);
            $table->integer('aug_cnt')->default(0);
            $table->integer('sept_cnt')->default(0);
            $table->integer('oct_cnt')->default(0);
            $table->integer('nov_cnt')->default(0);
            $table->integer('dec_cnt')->default(0);
            $table->string('remarks');
            $table->timestamps();

        });

        Schema::table('Cacao_production', function (Blueprint $table){
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
