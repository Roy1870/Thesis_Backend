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
        Schema::table('operators', function (Blueprint $table) {
            // Drop existing primary key
            $table->dropPrimary();

            // Rename column profile_id to operator_id
            $table->renameColumn('profile_id', 'operator_id');

            // Set operator_id as new primary key
            $table->primary('operator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operators', function (Blueprint $table) {
            // Drop new primary key
            $table->dropPrimary();

            // Rename column back to profile_id
            $table->renameColumn('operator_id', 'profile_id');

            // Set profile_id as primary key again
            $table->primary('profile_id');
        });
    }
};
