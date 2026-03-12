<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change type_of_sale from ENUM to VARCHAR in shipment_products table
        DB::statement("ALTER TABLE shipment_products MODIFY COLUMN type_of_sale VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to ENUM
        DB::statement("ALTER TABLE shipment_products MODIFY COLUMN type_of_sale ENUM('FDA', 'FDM', 'WFS') NULL");
    }
};
