<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HonorairesPerMonth extends ChartWidget
{
    protected static ?string $heading = 'Honoraires par mois (année fiscale en cours)';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;

    protected function getData(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');

        // Fetch data grouped by month, handling cases where the query might return no results
        $honoraires = DB::table('honoraires')
            ->selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereYear('created_at', $currentFiscalYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Initialize data and labels arrays
        $data = [];
        $labels = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT); // Format month as 01, 02, etc.
            $data[] = $honoraires[$monthKey] ?? 0; // Default to 0 if no data for the month
            $labels[] = Carbon::create()->month($i)->format('F'); // Full month name
        }

        // Ensure a valid response structure, even when there is no data
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
        return 'line'; // Line chart type
    }
}