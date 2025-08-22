<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\HonoraireResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHonoraires extends ListRecords
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('Honoraires');
    }
}
