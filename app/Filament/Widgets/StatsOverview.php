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

    protected function getStats(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');

        return [
            Stat::make("Années de l'exercice", $currentFiscalYear)
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Pour changer l\'année, veuillez cliquer içi')
                ->descriptionColor('success')
                ->url('dashboard/modify-fiscal-year'),
            Stat::make('Nombre des clients enregistrés', Client::whereYear('created_at', $currentFiscalYear)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre de clients enregistrés cette année fiscale')
                ->descriptionColor('success'),
            Stat::make("Nombre d'honoraires traités", Honoraire::whereYear('created_at', $currentFiscalYear)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre d\'honoraires traités cette année fiscale')
                ->descriptionColor('success'),
            Stat::make("Nombre d'utilisateurs", User::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre d\'utilisateurs enregistrés')
                ->descriptionColor('success'),
        ];
    }
}
