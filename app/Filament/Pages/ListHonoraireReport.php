<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use App\Filament\Resources\HonoraireReportResource;
use App\Models\Client;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;

class ListHonoraireReport extends ListRecords
{
    protected static string $resource = HonoraireReportResource::class;

    protected static ?string $title = "Rapport des honoraires";

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReportAll')
                ->label("Générer Rapport de tous les clients")
                ->action(function (array $data) {
                    $startDate = $data['start_date'];
                    $endDate = $data['end_date'];

                    $clients = Client::all();
                    $allHonoraires = collect();
                    $totalRS = 0;

                    foreach ($clients as $client) {
                        $honoraires = Honoraire::where('client_id', $client->id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->get();

                        $allHonoraires = $allHonoraires->concat($honoraires);
                        $totalRS += $honoraires->sum('rs');
                    }

                    $pdf = Pdf::loadView('retenue-source-all', [
                        'clients' => $clients,
                        'honoraires' => $allHonoraires,
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                        'totalRS' => $totalRS,
                    ]);

                    $currentDate = now()->format('Y-m-d');
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
