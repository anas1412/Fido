<?php

namespace App\Filament\Resources\RetenueSourcResource\Pages;

use App\Filament\Resources\RetenueSourcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRetenueSourcs extends ListRecords
{
    protected static string $resource = RetenueSourcResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
