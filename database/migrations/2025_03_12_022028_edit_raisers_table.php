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
         // Modify `remarks` in `raisers` to be a string
         Schema::table('raisers', function (Blueprint $table) {
            $table->string('remarks')->change();
        });

        // Add `remarks` column to `operators`
        Schema::table('operators', function (Blueprint $table) {
            $table->string('remarks')->nullable()->after('year');
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
