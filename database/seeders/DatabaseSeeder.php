<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed rack locations first
        // $this->call(RackLocationSeeder::class);
        
        // Create test admin
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@3pl.com',
            'password' => Hash::make('password'),
        ]);

        // Create test client
        Client::create([
            'name' => 'Test Client Company',
            'email' => 'client@example.com',
            'phone' => '1234567890',
            'group_id' => 'GRP-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'password' => Hash::make('password'),
        ]);

        // Create test agent
        Agent::create([
            'name' => 'Test Agent',
            'email' => 'agent@3pl.com',
            'password' => Hash::make('password'),
            'warehouse' => 1,
        ]);

        $this->command->info('✅ Test users created:');
        $this->command->info('Admin: admin@3pl.com / password');
        $this->command->info('Client: client@example.com / password');
        $this->command->info('Agent: agent@3pl.com / password');
    }
}
