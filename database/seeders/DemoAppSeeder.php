<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(DemoUserSeeder::class);
        // Add any other demo-specific seeding here, but no admin users
    }
}
