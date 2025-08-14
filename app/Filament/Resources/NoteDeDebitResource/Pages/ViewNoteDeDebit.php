<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use App\Filament\Resources\NoteDeDebitResource;
use App\Models\NoteDeDebit;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewNoteDeDebit extends ViewRecord
{
    protected static string $resource = NoteDeDebitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(NoteDeDebit $record) => route('pdf.note-de-debit', ['noteDeDebit' => $record->id])),

            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('View Note de Débit');
    }
}
