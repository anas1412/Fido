<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use App\Filament\Resources\NoteDeDebitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNoteDeDebits extends ListRecords
{
    protected static string $resource = NoteDeDebitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
