<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\File;

class ProjectInfoWidget extends Widget
{
    protected string $view = 'filament.widgets.project-info-widget';

    // Optional: Customize the position of the widget
    protected static ?int $sort = 1; // Adjust position in the dashboard

    public static function canView(): bool
    {
        return true; // Set any visibility restrictions if needed
    }
        public function getViewData(): array
    {
        $version = 'N/A'; // Default value

        $packageJsonPath = base_path('package.json');

        if (File::exists($packageJsonPath)) {
            $packageJsonContent = File::get($packageJsonPath);
            $packageData = json_decode($packageJsonContent, true);

            if (isset($packageData['version'])) {
                $version = $packageData['version'];
            }
        }

        return [
            'version' => $version,
        ];
    }
}