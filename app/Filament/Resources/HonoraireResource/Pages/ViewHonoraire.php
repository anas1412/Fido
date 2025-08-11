<?php

namespace App\Filament\Resources\HonoraireResource\Pages;

use App\Filament\Resources\HonoraireResource;
use App\Models\Honoraire;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewHonoraire extends ViewRecord
{
    protected static string $resource = HonoraireResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('PDF')
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(Honoraire $record) => route('pdf', $record)),
                
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
                        'tva' => \App\Models\TaxSetting::first()->tva,
                        'rs' => \App\Models\TaxSetting::first()->rs

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
