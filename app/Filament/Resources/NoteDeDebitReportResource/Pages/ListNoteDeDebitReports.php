<?php

namespace App\Filament\Resources\NoteDeDebitReportResource\Pages;

use App\Filament\Resources\NoteDeDebitReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNoteDeDebitReports extends ListRecords
{
    protected static string $resource = NoteDeDebitReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
