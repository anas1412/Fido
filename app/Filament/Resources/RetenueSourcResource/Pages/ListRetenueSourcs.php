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

class ListRetenueSourcs extends ListRecords
{
    protected static string $resource = RetenueSourcResource::class;

    protected static ?string $title = "Rapport des retenues à la source";

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label("Générer Rapport d'un client")
                ->action(function (array $data) {
                    $client = Client::findOrFail($data['client_id']);
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));
                    $currentDateFormatted = now()->format('d/m/Y');

                    $honoraires = Honoraire::where('client_id', $client->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get();

                    $totalRS = $honoraires->sum('rs');
                    $totalTTC = $honoraires->sum('montantTTC');
                    $totalNET = $honoraires->sum('netapayer');

                    $pdf = Pdf::loadView('retenue-source', [
                        'client' => $client,
                        'honoraires' => $honoraires,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'totalRS' => $totalRS,
                        'totalTTC' => $totalTTC,
                        'totalNET' => $totalNET,
                        'rs' => \App\Models\TaxSetting::first()->rs * 100,
                    ]);

                    $currentDate = now()->format('d-m-Y');
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
            Action::make('generateReportAll')
                ->label("Générer Rapport de tous les clients")
                ->action(function (array $data) {
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));
                    $currentDateFormatted = now()->format('d/m/Y');

                    $clients = Client::all(); // Fetch all clients
                    $totalTTC = 0; // Overall total TTC for all clients
                    $totalRS = 0; // Overall total RS for all clients

                    // Loop through each client and calculate their TTC and RS sums
                    foreach ($clients as $client) {
                        // Get honoraires for the client within the specified date range
                        $honoraires = $client->honoraires()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->get();

                        // Sum TTC and RS for this client
                        $client->totalClientTTC = $honoraires->sum('montantTTC');
                        $client->totalClientRS = $honoraires->sum('rs');

                        // Add client totals to overall totals
                        $totalTTC += $client->totalClientTTC;
                        $totalRS += $client->totalClientRS;
                    }

                    $fiscalYear = config('fiscal_year.current_year');
                    $taxSettings = \App\Models\TaxSetting::first();
                    $rs = $taxSettings->rs * 100;

                    // Generate PDF
                    $pdf = Pdf::loadView('retenue-source-all', [
                        'fiscalYear' => $fiscalYear,
                        'clients' => $clients,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'rs' => $rs,
                        'totalTTC' => $totalTTC,
                        'totalRS' => $totalRS,
                    ]);

                    $currentDate = now()->format('d-m-Y');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "rapport_retenue_source_tous_clients_{$currentDate}.pdf");
                })

                ->form([
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