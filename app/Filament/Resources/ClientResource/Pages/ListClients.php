<?php

namespace App\Filament\Resources\ClientResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->disabled(auth()->user()?->is_demo)
                ->tooltip(auth()->user()?->is_demo ? __('Disabled in demo mode') : null),
        ];
    }

    public function getTitle(): string
    {
        return __('Clients');
    }
}
