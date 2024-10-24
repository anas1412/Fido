<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Models\Honoraire;
use Illuminate\Support\Facades\DB;


class HonorairesPerMonth extends ChartWidget
{
    protected static ?string $heading = 'Honoraires par mois (année fiscale en cours)';

    protected static ?int $sort = 3;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $currentDate = Carbon::now();
        $fiscalYearStart = $currentDate->month >= 4
            ? $currentDate->copy()->startOfYear()->addMonths(3)
            : $currentDate->copy()->subYear()->startOfYear()->addMonths(3);
        $fiscalYearEnd = $fiscalYearStart->copy()->addYear()->subDay();

        /* $honoraires = Honoraire::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$fiscalYearStart, $fiscalYearEnd])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray(); */

        $honoraires = DB::table('honoraires')
            ->selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereBetween('created_at', [$fiscalYearStart, $fiscalYearEnd])
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();


        $data = [];
        $labels = [];
        for ($i = 0; $i < 12; $i++) {
            $currentMonth = $fiscalYearStart->copy()->addMonths($i);
            $monthKey = $currentMonth->format('n');
            $data[] = $honoraires[$monthKey] ?? 0;
            $labels[] = $currentMonth->format('F');
        }

        return [
            'labels' => $labels,
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
