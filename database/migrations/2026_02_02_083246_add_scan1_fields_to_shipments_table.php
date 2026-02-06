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
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('rack_location')->nullable()->after('status');
            $table->integer('received_quantity')->nullable()->after('rack_location');
            $table->enum('product_condition', ['excellent', 'good', 'fair', 'damaged'])->nullable()->after('received_quantity');
            $table->text('scan1_notes')->nullable()->after('product_condition');
            $table->string('scan1_image_path')->nullable()->after('scan1_notes');
            $table->timestamp('scan1_at')->nullable()->after('scan1_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
        });
    }
};
