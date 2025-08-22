<?php

namespace App\Filament\Resources\NoteDeDebitReportResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\NoteDeDebitReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNoteDeDebitReport extends EditRecord
{
    protected static string $resource = NoteDeDebitReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
