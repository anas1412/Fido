<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Honoraire;

class HonorairesPerMonth extends ChartWidget
{
    protected static ?string $heading = 'Honoraires par mois (année en cours)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $currentYear = Carbon::now()->year;

        $honoraires = Honoraire::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[] = $honoraires[$month] ?? 0;
        }

        return [
            'labels' => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
            'datasets' => [
                [
                    'label' => 'Honoraires',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
