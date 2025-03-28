<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('livestock_records', function (Blueprint $table) {
            // Change `updated_by` from unsignedBigInteger to string
            $table->string('updated_by')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('livestock_records', function (Blueprint $table) {
            // Rollback: Change `updated_by` back to unsignedBigInteger
            $table->unsignedBigInteger('updated_by')->nullable()->change();
        });
    }
};
