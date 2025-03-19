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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id('farmer_id'); // Primary Key
            $table->string('name');
            $table->string('contact_number');
            $table->string('facebook_email')->nullable();
            $table->string('home_address')->nullable();
            $table->string('farm_address')->nullable();
            $table->float('farm_location_longitude', 10, 6)->nullable();
            $table->float('farm_location_latitude', 10, 6)->nullable();
            $table->string('market_outlet_location')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('association_organization')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
