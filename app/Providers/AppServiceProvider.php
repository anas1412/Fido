<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->displayLocale('fr') // Sets French as the language for label localization
                ->locales(['fr', 'en']); // also accepts a closure
        });
    }
}
