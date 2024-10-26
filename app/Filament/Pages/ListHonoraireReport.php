<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Models\Client;
use App\Models\Honoraire;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\HonoraireReportResource;

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
                    $currentDateFormatted = now()->format('d/m/Y');
                    $clients = Client::all();
                    $allHonoraires = collect();
                    $totalRS = 0;

                    $hs = Honoraire::whereBetween('date', [$startDate, $endDate])->get();
                    /* $hs = Honoraire::all(); */

                    /* foreach ($clients as $client) {
                        $honoraires = Honoraire::where('client_id', $client->id)
                            ->whereBetween('date', [$startDate, $endDate])
                            ->get();

                        $allHonoraires = $allHonoraires->concat($honoraires);
                        $totalRS += $honoraires->sum('rs');
                    } */

                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));

                    $tva = config('taxes.tva') * 100;
                    $rs = config('taxes.rs') * 100;
                    $tf = config('taxes.tf') * 100;

                    $pdf = Pdf::loadView('honoraire-report', [
                        'clients' => $clients,
                        'honoraires' => $allHonoraires,
                        'hs' => $hs,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'totalRS' => $totalRS,
                        'tva' => $tva,
                        'rs' => $rs,
                        'tf' => $tf,
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
