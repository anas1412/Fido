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
                ->description('')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make("Nombre d'honoraires traités", Honoraire::count())
                ->description('')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make("Nombre d'utilisateurs", User::count())
                ->description('')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
        ];
    }
}
