<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ProjectInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.project-info-widget';

    // Optional: Customize the position of the widget
    protected static ?int $sort = 1; // Adjust position in the dashboard

    public static function canView(): bool
    {
        return true; // Set any visibility restrictions if needed
    }
        public function getViewData(): array
    {
        return [
            'version' => config('app.version'), // Pass version from config
        ];
    }
}