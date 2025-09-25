<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Honoraire;
use App\Models\Invoice;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewPart2 extends BaseWidget
{

    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 2; // Sort after the first StatsOverview

    protected static bool $isLazy = true;

    protected int|array|null $columns = 4;

    protected function getStats(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');

        $totalRS = Honoraire::whereYear('date', $currentFiscalYear)->sum('rs');
        $totalTVA = Honoraire::whereYear('date', $currentFiscalYear)->sum('tva');
        $totalTF = Honoraire::whereYear('date', $currentFiscalYear)->sum('tf');

        $formattedTotalRS = number_format($totalRS, 3, '.', ',');
        $formattedTotalTVA = number_format($totalTVA, 3, '.', ',');
        $formattedTotalTF = number_format($totalTF, 3, '.', ',');


        return [
            Stat::make("Total hors tax", Honoraire::whereYear('created_at', $currentFiscalYear)->sum('montantHT'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->description('Total hors tax (Honoraires) cette année fiscale')
                ->descriptionColor('info'),
            Stat::make("Total Retenue à la Source", $formattedTotalRS . ' TND')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->description('Montant total de retenue à la source cette année fiscale')
                ->descriptionColor('warning'),
            Stat::make("Total TVA Collectée", $formattedTotalTVA . ' TND')
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('success')
                ->description('Montant total de TVA collectée cette année fiscale')
                ->descriptionColor('success'),
            Stat::make("Total TF Collectée", $formattedTotalTF . ' TND')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('danger')
                ->description('Montant total de TF collectée cette année fiscale')
                ->descriptionColor('danger'),
        ];
    }
}
