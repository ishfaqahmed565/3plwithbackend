<?php

namespace Database\Seeders;

use App\Models\RackLocation;
use Illuminate\Database\Seeder;

class RackLocationSeeder extends Seeder
{
    public function run(): void
    {
        $zones = ['A', 'B', 'C', 'D'];
        $aisles = range(1, 5);
        $racks = range(1, 20);

        foreach ($zones as $zone) {
            foreach ($aisles as $aisle) {
                foreach ($racks as $rack) {
                    RackLocation::create([
                        'code' => sprintf('%s%d-%02d', $zone, $aisle, $rack),
                        'zone' => "Zone {$zone}",
                        'aisle' => "Aisle {$aisle}",
                        'rack' => sprintf('Rack %02d', $rack),
                        'status' => 'available',
                    ]);
                }
            }
        }
    }
}
