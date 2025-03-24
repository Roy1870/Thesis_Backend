<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('crops', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['grower_id']);
            // Rename column
            $table->renameColumn('grower_id', 'farmer_id');
            // Add new foreign key constraint
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');

        });

        Schema::table('rice', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['grower_id']);
            // Rename column
            $table->renameColumn('grower_id', 'farmer_id');
            // Add new foreign key constraint
            $table->foreign('farmer_id')->references('farmer_id')->on('farmers')->onDelete('cascade');
        });

        // Now it's safe to drop the growers table
        Schema::dropIfExists('growers');
    }

    public function down() {
        // Reverse the changes
        Schema::create('growers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('crops', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
            $table->renameColumn('farmer_id', 'grower_id');
            $table->foreign('grower_id')->references('farmer_id')->on('growers')->onDelete('cascade');
        });

        Schema::table('rice', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
            $table->renameColumn('farmer_id', 'grower_id');
            $table->foreign('grower_id')->references('farmer_id')->on('growers')->onDelete('cascade');
        });
    }
};
