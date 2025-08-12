<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FiscalYearSetting;

class CheckFiscalYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiscal:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks the current fiscal year setting.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setting = FiscalYearSetting::first();

        if ($setting) {
            $this->info('Current Fiscal Year: ' . $setting->year);
        } else {
            $this->info('No Fiscal Year setting found.');
        }
    }
}
