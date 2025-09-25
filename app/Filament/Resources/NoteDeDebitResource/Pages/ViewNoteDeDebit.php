<?php

namespace App\Filament\Resources\NoteDeDebitResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
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
            Action::make('print')
                ->label(__('Print'))
                ->color('info')
                ->icon('heroicon-o-printer')
                ->url(fn(NoteDeDebit $record) => route('pdf.note-de-debit', ['noteDeDebit' => $record->id]))
                ->openUrlInNewTab(),

            EditAction::make()->visible(!auth()->user()?->is_demo),
            DeleteAction::make()->visible(!auth()->user()?->is_demo),
        ];
    }

    public function getTitle(): string
    {
        return __('View Note de Débit');
    }
}
