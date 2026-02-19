<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity_expected')->default(0);
            $table->integer('quantity_available')->default(0);
            $table->integer('received_quantity')->nullable();
            $table->enum('product_condition', ['excellent', 'good', 'fair', 'damaged'])->nullable();
            $table->string('rack_location')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Migrate existing shipments into shipment_products (one-to-one)
        if (Schema::hasTable('shipments')) {
            $shipments = \DB::table('shipments')->get();
            foreach ($shipments as $s) {
                \DB::table('shipment_products')->insert([
                    'shipment_id' => $s->id,
                    'name' => $s->product_name ?? 'Unnamed Product',
                    'description' => $s->product_description,
                    'quantity_expected' => $s->quantity_total ?? 0,
                    'quantity_available' => $s->quantity_available ?? 0,
                    'received_quantity' => $s->received_quantity,
                    'product_condition' => $s->product_condition,
                    'rack_location' => $s->rack_location,
                    'remarks' => $s->scan1_notes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_products');
    }
};
