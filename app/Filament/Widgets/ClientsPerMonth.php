<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Client;

class ClientsPerMonth extends ChartWidget
{
    protected static ?string $heading = 'Clients par mois (année en cours)';

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $currentDate = Carbon::now();
        $fiscalYearStart = $currentDate->month >= 4
            ? $currentDate->copy()->startOfYear()->addMonths(3)
            : $currentDate->copy()->subYear()->startOfYear()->addMonths(3);
        $fiscalYearEnd = $fiscalYearStart->copy()->addYear()->subDay();

        $clients = Client::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$fiscalYearStart, $fiscalYearEnd])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data = [];
        $labels = [];
        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $fiscalYearStart->copy()->addMonths($i);
            $monthKey = $currentMonth->format('n');
            $data[] = $clients[$monthKey] ?? 0;
            $labels[] = $currentMonth->translatedFormat('F');
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Clients',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
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
