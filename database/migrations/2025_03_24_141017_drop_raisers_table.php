<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Step 1: Drop the foreign key constraint in livestock_records
        Schema::table('livestock_records', function (Blueprint $table) {
            $table->dropForeign(['raiser_id']); // ✅ Drop the foreign key
            $table->dropColumn('raiser_id'); // ✅ Remove the column if needed
        });

        // Step 2: Now, drop the raisers table safely
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
