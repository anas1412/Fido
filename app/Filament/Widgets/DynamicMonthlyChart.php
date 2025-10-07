<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Honoraire;
use App\Models\Invoice;
use App\Models\NoteDeDebit;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DynamicMonthlyChart extends ChartWidget
{
    protected ?string $heading = 'Entrées par mois';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'clients';

    protected function getFilters(): ?array
    {
        return [
            'clients' => 'Clients',
            'invoices' => 'Factures',
        ];
    }

    protected function getData(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');
        $activeFilter = $this->filter;

        $colorMap = [
            'clients' => 'rgba(54, 162, 235, 0.2)',
            'invoices' => 'rgba(75, 192, 192, 0.2)',
        ];

        $borderColorMap = [
            'clients' => 'rgba(54, 162, 235, 1)',
            'invoices' => 'rgba(75, 192, 192, 1)',
        ];

        $modelData = $this->getModelData($activeFilter, $currentFiscalYear);

        $data = [];
        $labels = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data[] = $modelData[$monthKey] ?? 0;
            $labels[] = Carbon::create()->month($i)->translatedFormat('F');
        }

        return [
            'datasets' => [
                [
                    'label' => ucfirst(str_replace('_', ' ', $activeFilter)),
                    'data' => $data,
                    'backgroundColor' => $colorMap[$activeFilter] ?? 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => $borderColorMap[$activeFilter] ?? 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getModelData(string $model, int $year): array
    {
        $tableMap = [
            'clients' => 'clients',
            'invoices' => 'invoices',
        ];

        $dateColumnMap = [
            'clients' => 'created_at',
            'invoices' => 'date',
        ];

        $tableName = $tableMap[$model];
        $dateColumn = $dateColumnMap[$model];

        return DB::table($tableName)
            ->selectRaw("strftime('%m', {$dateColumn}) as month, COUNT(*) as count")
            ->whereYear($dateColumn, $year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }
}