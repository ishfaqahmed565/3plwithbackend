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
        Schema::create('rack_locations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., A1-01, B2-15
            $table->string('zone'); // e.g., Zone A, Zone B
            $table->string('aisle'); // e.g., Aisle 1, Aisle 2
            $table->string('rack'); // e.g., Rack 01, Rack 15
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_locations');
    }
};
