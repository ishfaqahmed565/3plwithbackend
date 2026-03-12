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
           $table->enum('type_of_sale', ['FDA', 'FDM', 'WFS'])->nullable()->after('delivery_partner');
        });

        Schema::table('shipment_products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('quantity_available');
            $table->string('link_url')->nullable()->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('type_of_sale');
        });

        Schema::table('shipment_products', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'link_url']);
        });
    }
};
