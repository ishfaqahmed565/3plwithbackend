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
        // Update existing data: FDA -> FBA, FDM -> FBM
        DB::table('shipment_products')
            ->where('type_of_sale', 'FDA')
            ->update(['type_of_sale' => 'FBA']);
        
        DB::table('shipment_products')
            ->where('type_of_sale', 'FDM')
            ->update(['type_of_sale' => 'FBM']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data: FBA -> FDA, FBM -> FDM
        DB::table('shipment_products')
            ->where('type_of_sale', 'FBA')
            ->update(['type_of_sale' => 'FDA']);
        
        DB::table('shipment_products')
            ->where('type_of_sale', 'FBM')
            ->update(['type_of_sale' => 'FDM']);
    }
};
