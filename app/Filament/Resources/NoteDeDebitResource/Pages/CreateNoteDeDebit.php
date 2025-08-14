<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use App\Filament\Resources\NoteDeDebitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateNoteDeDebit extends CreateRecord
{
    protected static string $resource = NoteDeDebitResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            return parent::handleRecordCreation($data);
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function getTitle(): string
    {
        return __('Create Note de Débit');
    }
}
