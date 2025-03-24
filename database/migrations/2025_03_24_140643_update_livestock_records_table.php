<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('livestock_records', function (Blueprint $table) {
            // Step 1: Add new farmer_id column (nullable initially)
            $table->unsignedBigInteger('farmer_id')->nullable()->after('record_id');
            
            // Step 2: Add updated_by column
            $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');

            // Set foreign key constraint for farmer_id (after data is migrated)
        });

        // Step 3: Copy data from raiser_id to farmer_id
        DB::statement('UPDATE livestock_records SET farmer_id = raiser_id');

        Schema::table('livestock_records', function (Blueprint $table) {
            // Step 4: Set foreign key constraints for farmer_id
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');

            // Step 5: Remove raiser_id column
            $table->dropForeign(['raiser_id']);
            $table->dropColumn('raiser_id');
        });
    }

    public function down()
    {
        Schema::table('livestock_records', function (Blueprint $table) {
            // Rollback: Add raiser_id back
            $table->unsignedBigInteger('raiser_id')->nullable()->after('record_id');
            $table->foreign('raiser_id')->references('id')->on('raisers')->onDelete('cascade');

            // Remove farmer_id and updated_by
            $table->dropForeign(['farmer_id']);
            $table->dropColumn('farmer_id');
            $table->dropColumn('updated_by');
        });
    }
};

