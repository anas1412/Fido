<?php

namespace App\Filament\Resources\RetenueSourcResource\Pages;

use App\Filament\Resources\RetenueSourcResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRetenueSourc extends EditRecord
{
    protected static string $resource = RetenueSourcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
