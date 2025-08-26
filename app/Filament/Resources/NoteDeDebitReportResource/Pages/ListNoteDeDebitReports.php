<?php

namespace App\Filament\Resources\NoteDeDebitReportResource\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\NoteDeDebitReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Models\Client;
use App\Models\NoteDeDebit;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;

class ListNoteDeDebitReports extends ListRecords
{
    protected static string $resource = NoteDeDebitReportResource::class;

    protected static ?string $title = "Rapport des note des debits";

    protected static ?int $navigationSort = 60;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label("Générer Rapport d'un client")
                ->disabled()
                ->tooltip(__('Work in progress'))
                ->action(function (array $data) {
                    $client = Client::findOrFail($data['client_id']);
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));
                    $currentDateFormatted = now()->format('d/m/Y');

                    $noteDeDebits = NoteDeDebit::where('client_id', $client->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->get();

                    $total = $noteDeDebits->sum('amount');

                    $pdf = Pdf::loadView('note-de-debit', [
                        'client' => $client,
                        'noteDesSebits' => $noteDeDebits,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'total' => $total,
                    ]);

                    $currentDate = now()->format('d-m-Y');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "rapport_note_de_debit_{$client->name}_{$currentDate}.pdf");
                })

                ->schema([
                    Select::make('client_id')
                        ->label('Client')
                        ->options(Client::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Date de début')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Date de fin')
                        ->required(),
                ]),
            Action::make('generateReportAll')
                ->label("Générer Rapport de tous les clients")
                ->disabled()
                ->tooltip(__('Work in progress'))
                ->action(function (array $data) {
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];
                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));
                    $currentDateFormatted = now()->format('d/m/Y');

                    $clients = Client::all(); // Fetch all clients
                    $total = 0; // Overall total for all clients

                    // Loop through each client and calculate their Total sums
                    foreach ($clients as $client) {
                        // Get honoraires for the client within the specified date range
                        $noteDeDebits = $client->honoraires()
                            ->whereBetween('date', [$startDate, $endDate])
                            ->get();

                        // Sum TTC and RS for this client
                        $client->totalClient = $noteDeDebits->sum('amount');

                        // Add client totals to overall totals
                        $total += $client->totalClient;
                    }

                    $fiscalYear = config('fiscal_year.current_year');

                    // Generate PDF
                    $pdf = Pdf::loadView('note-de-debit-all', [
                        'fiscalYear' => $fiscalYear,
                        'clients' => $clients,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'total' => $total,
                    ]);

                    $currentDate = now()->format('d-m-Y');
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, "rapport_note_debit_tous_clients_{$currentDate}.pdf");
                })

                ->schema([
                    DatePicker::make('start_date')
                        ->label('Date de début')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Date de fin')
                        ->required(),
                ]),
        ];
    }
}
