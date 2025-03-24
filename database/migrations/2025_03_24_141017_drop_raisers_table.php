<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        // Step 2: Drop the raisers table
        Schema::dropIfExists('raisers');
    }

    public function down()
    {
        // Rollback: Recreate the raisers table
        Schema::create('raisers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Restore the foreign key in livestock_records
        Schema::table('livestock_records', function (Blueprint $table) {
            $table->unsignedBigInteger('raiser_id')->nullable();
            $table->foreign('raiser_id')->references('id')->on('raisers')->onDelete('cascade');
        });
    }
};
