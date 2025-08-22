<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Honoraire;
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

        return [
            Stat::make("Total Retenue à la Source (Année Fiscale)", Honoraire::whereYear('date', $currentFiscalYear)->sum('rs'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->description('Montant total de retenue à la source cette année fiscale')
                ->descriptionColor('warning'),
            Stat::make("Total TVA Collectée (Année Fiscale)", Honoraire::whereYear('date', $currentFiscalYear)->sum('tva'))
                ->descriptionIcon('heroicon-m-receipt-percent')
                ->color('success')
                ->description('Montant total de TVA collectée cette année fiscale')
                ->descriptionColor('success'),
            Stat::make("Total TF Collectée (Année Fiscale)", Honoraire::whereYear('date', $currentFiscalYear)->sum('tf'))
                ->descriptionIcon('heroicon-m-calculator')
                ->color('danger')
                ->description('Montant total de TF collectée cette année fiscale')
                ->descriptionColor('danger'),
            Stat::make("Valeur Moyenne des Factures (Année Fiscale)", Honoraire::whereYear('date', $currentFiscalYear)->avg('montantTTC'))
                ->descriptionIcon('heroicon-m-scale')
                ->color('info')
                ->description('Valeur moyenne des factures cette année fiscale')
                ->descriptionColor('info'),
        ];
    }
}
