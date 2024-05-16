<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use App\Filament\Resources\HonoraireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHonoraires extends ListRecords
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
