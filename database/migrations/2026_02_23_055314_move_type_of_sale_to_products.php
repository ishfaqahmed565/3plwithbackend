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
        Schema::table('shipment_products', function (Blueprint $table) {
            $table->enum('type_of_sale', ['FDA', 'FDM', 'WFS'])->nullable()->after('link_url');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('type_of_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->enum('type_of_sale', ['FDA', 'FDM', 'WFS'])->nullable()->after('delivery_partner');
        });

        Schema::table('shipment_products', function (Blueprint $table) {
            $table->dropColumn('type_of_sale');
        });
    }
};
