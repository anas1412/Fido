<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\NoteDeDebitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNoteDeDebits extends ListRecords
{
    protected static string $resource = NoteDeDebitResource::class;

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
        return __('Note de Débit');
    }
}
