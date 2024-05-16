<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use App\Filament\Resources\HonoraireResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHonoraire extends EditRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
