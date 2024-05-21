<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use App\Filament\Resources\HonoraireResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHonoraire extends ViewRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
