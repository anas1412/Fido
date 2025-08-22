<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\HonoraireReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHonoraireReport extends EditRecord
{
    protected static string $resource = HonoraireReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
