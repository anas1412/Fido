<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppVersionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $packageJsonPath = base_path('package.json');

        if (file_exists($packageJsonPath)) {
            $packageJson = json_decode(file_get_contents($packageJsonPath), true);

            if (isset($packageJson['version'])) {
                config(['app.package_version' => $packageJson['version']]);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
