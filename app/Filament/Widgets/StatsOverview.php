<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Honoraire;
use App\Models\NoteDeDebit;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected ?string $pollingInterval = '15s';

    protected static ?int $sort = 1;

    protected static bool $isLazy = true;

    protected int|array|null $columns = 2;

    protected function getStats(): array
    {
        $currentFiscalYear = config('fiscal_year.current_year');

        return [
            Stat::make('Nombre des clients enregistrés', Client::count())
                ->descriptionIcon('heroicon-o-users')
                ->color('success')
                ->description('Nombre total de clients enregistrés')
                ->descriptionColor('success'),
            Stat::make("Nombre de facture traités", NoteDeDebit::whereYear('created_at', $currentFiscalYear)->count())
                ->descriptionIcon('heroicon-o-document-text')
                ->color('warning')
                ->description('Nombre de facture traités cette année fiscale')
                ->descriptionColor('warning'),
        ];
    }
}

