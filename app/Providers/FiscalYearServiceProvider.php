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
        $fiscalYearSetting = FiscalYearSetting::first();

        if ($fiscalYearSetting) {
            config(['fiscal_year.current_year' => $fiscalYearSetting->year]);
        } else {
            // Default to current year if no setting is found
            config(['fiscal_year.year' => date('Y')]);
        }
    }
}