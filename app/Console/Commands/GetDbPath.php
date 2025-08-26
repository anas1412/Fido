<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class GetDbPath extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fido:db-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the configured database path';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $databasePath = Config::get('database.connections.sqlite.database');
        
        // Ensure the directory exists before outputting the path
        $directory = dirname($databasePath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $this->line($databasePath);
        return 0;
    }
}
