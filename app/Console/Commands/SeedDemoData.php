<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DemoUserSeeder;

class SeedDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed demo data (including demo user, clients, honoraires, etc.) into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding demo data...');
        $this->call(DemoUserSeeder::class);
        $this->info('Demo data seeded successfully.');
    }
}
