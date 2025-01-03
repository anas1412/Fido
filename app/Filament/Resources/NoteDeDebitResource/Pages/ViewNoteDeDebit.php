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
                ->url(fn(NoteDeDebit $record) => route('pdf', $record))
                ->openUrlInNewTab(),
            /* Actions\Action::make('pdf')
                ->label("Générer PDF")
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (array $data) {
                    $honoraire = $this->getRecord();

                    $formattedDate = \Carbon\Carbon::parse($honoraire->date)->format('d/m/Y');


                    $pdf = Pdf::loadView('honoraire-report', [
                        'record' => $honoraire,
                        'formattedDate' => $formattedDate,
                        'tva' => config('taxes.tva'),
                        'rs' => config('taxes.rs')

                    ])->setPaper('A4', 'portrait')->download($honoraire->note . '.pdf');;

                    $currentDate = now()->format('d-m-Y');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "Honoraire_{$honoraire->note}_{$currentDate}.pdf");
                }), */
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
