<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use App\Filament\Resources\NoteDeDebitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNoteDeDebit extends CreateRecord
{
    protected static string $resource = NoteDeDebitResource::class;

    /* protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    } */

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
