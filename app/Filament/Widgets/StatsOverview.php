<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Honoraire;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected static bool $isLazy = true;

    protected int | string | array $columns = 4;

    protected function getStats(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');

        return [
            Stat::make("Années de l'exercice", $currentFiscalYear)
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary')
                ->description('Pour changer l\'année, veuillez cliquer içi')
                ->descriptionColor('success')
                ->url('dashboard/modify-fiscal-year'),
            Stat::make('Nombre des clients enregistrés', Client::count())
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->description('Nombre total de clients enregistrés')
                ->descriptionColor('success'),
            Stat::make("Nombre d'honoraires traités", Honoraire::whereYear('created_at', $currentFiscalYear)->count())
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info')
                ->description('Nombre d\'honoraires traités cette année fiscale')
                ->descriptionColor('success'),
            Stat::make("Total Facturé (Année Fiscale)", Honoraire::whereYear('date', $currentFiscalYear)->sum('montantTTC'))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success')
                ->description('Montant total facturé cette année fiscale')
                ->descriptionColor('info'),
        ];
    }
}
