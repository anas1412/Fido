<?php

namespace App\Filament\Resources\RetenueSourcResource\Pages;

use App\Filament\Resources\RetenueSourcResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\Client;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListRetenueSourc extends ListRecords
{
    protected static string $resource = RetenueSourcResource::class;

    protected static ?string $title = "Rapport de retenue à la source par client";


    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label('Générer Rapport')
                ->action(function (array $data) {
                    $client = Client::findOrFail($data['client_id']);
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];

                    $honoraires = Honoraire::where('client_id', $client->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get();

                    $totalRS = $honoraires->sum('rs');

                    $pdf = Pdf::loadView('retenue-source', [
                        'client' => $client,
                        'honoraires' => $honoraires,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'totalRS' => $totalRS,
                    ]);

                    $currentDate = now()->format('Y-m-d');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "rapport_retenue_source_{$client->name}_{$currentDate}.pdf");
                })
                ->form([
                    Forms\Components\Select::make('client_id')
                        ->label('Client')
                        ->options(Client::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Date de début')
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Date de fin')
                        ->required(),
                ]),
        ];
    }
}
