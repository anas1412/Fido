<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use App\Filament\Resources\NoteDeDebitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNoteDeDebit extends EditRecord
{
    protected static string $resource = NoteDeDebitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('Edit Note de Débit');
    }
}
