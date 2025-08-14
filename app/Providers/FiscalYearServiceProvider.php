<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FiscalYearSetting;
use Illuminate\Support\Facades\Schema; // <-- ADD THIS LINE
use Illuminate\Support\Facades\Cache;

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
        // ADD THIS CHECK: Only try to query the database if the application's
        // database has been migrated and the 'fiscal_year_settings' table exists.
        if (Schema::hasTable('fiscal_year_settings')) {
            $fiscalYearSetting = Cache::rememberForever('fiscal_year_setting', function () {
                return FiscalYearSetting::first();
            });

            if ($fiscalYearSetting) {
                config(['fiscal_year.current_year' => $fiscalYearSetting->year]);
            } else {
                // Default to current year if the table is empty
                config(['fiscal_year.year' => date('Y')]);
            }
        } else {
            // Default to current year if the table doesn't even exist yet (e.g., during migration)
            config(['fiscal_year.year' => date('Y')]);
        }
    }
}