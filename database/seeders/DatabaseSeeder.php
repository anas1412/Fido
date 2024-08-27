<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin account
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'password' => bcrypt('admin123'),
        ]);

        // Create a regular user account
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@mail.com',
            'password' => bcrypt('user123'),
        ]);
    }
}