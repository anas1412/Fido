<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use App\Filament\Resources\HonoraireReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHonoraireReports extends ListRecords
{
    protected static string $resource = HonoraireReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
