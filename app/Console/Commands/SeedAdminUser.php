<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AdminUserSeeder;

class SeedAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the admin user into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding admin user...');
        $this->call(AdminUserSeeder::class);
        $this->info('Admin user seeded successfully.');
    }
}
