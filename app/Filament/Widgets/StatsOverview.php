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
        return [
            Stat::make('Nombre des clients enregistrés', Client::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre de clients enregistrés')
                ->descriptionColor('success'),
            Stat::make("Nombre d'honoraires traités", Honoraire::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre d\'honoraires traités')
                ->descriptionColor('success'),
            Stat::make("Nombre d'utilisateurs", User::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->description('Nombre d\'utilisateurs enregistrés')
                ->descriptionColor('success'),
        ];
    }
}
