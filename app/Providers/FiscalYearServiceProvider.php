<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FiscalYearSetting;

class FiscalYearServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure the database is connected before trying to access it
        if (app()->runningInConsole() || app()->runningUnitTests()) {
            return;
        }

        try {
            $fiscalYearSetting = FiscalYearSetting::firstOrCreate(
                [],
                ['year' => date('Y')]
            );
            config(['fiscal_year.current_year' => $fiscalYearSetting->year ?? date('Y')]);
        } catch (\Exception $e) {
            // Log the error, but don't prevent the application from booting
            // This can happen if migrations haven't run yet
            
        }
    }
}