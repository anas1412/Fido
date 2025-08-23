<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
                CreateAction::make()
                    ->disabled(auth()->user()?->is_demo)
                    ->tooltip(auth()->user()?->is_demo ? __('Disabled in demo mode') : null),
            ];
    }
}
