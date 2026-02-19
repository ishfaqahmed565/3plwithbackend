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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_code')->unique();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tracking_id')->nullable();
            $table->string('source');
            $table->string('product_name')->nullable();
            $table->text('product_description')->nullable();
            $table->string('category')->nullable();
            $table->string('product_image_path')->nullable();
            $table->integer('quantity_total');
            $table->integer('quantity_available');
            $table->enum('status', ['pending', 'received_in_warehouse'])->default('pending');
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
