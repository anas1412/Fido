<?php

namespace App\Filament\Resources\HonoraireReportResource\Pages;

use App\Filament\Resources\HonoraireReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Client;
use App\Models\Honoraire;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;

use Filament\Forms\Components\Builder;

class ListHonoraireReports extends ListRecords
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

                    $hs = Honoraire::whereBetween('date', [$startDate, $endDate])->get();

                    $startDateFormatted = date('d/m/Y', strtotime($data['start_date']));
                    $endDateFormatted = date('d/m/Y', strtotime($data['end_date']));

                    $tva = config('taxes.tva') * 100;
                    $rs = config('taxes.rs') * 100;
                    $tf = config('taxes.tf') * 100;

                    // Initialize totals
                    $totalHT = $hs->sum('montantHT');
                    $totalTVA = $hs->sum(function ($honoraire) {
                        return $honoraire->montantHT * config('taxes.tva');
                    });
                    $totalRS = $hs->sum(function ($honoraire) use ($totalTVA) {
                        return ($honoraire->montantHT + $honoraire->montantHT * config('taxes.tva')) * config('taxes.rs');
                    });
                    $totalTTC = $hs->sum(function ($honoraire) {
                        return $honoraire->montantHT + ($honoraire->montantHT * config('taxes.tva'));
                    });
                    $totalTF = $hs->sum(function () {
                        return config('taxes.tf');
                    });
                    $totalNetapayer = $totalTTC - $totalRS + $totalTF;


                    $pdf = Pdf::loadView('honoraire-report', [
                        'clients' => $clients,
                        'honoraires' => $allHonoraires,
                        'hs' => $hs,
                        'startDate' => $startDateFormatted,
                        'endDate' => $endDateFormatted,
                        'currentDate' => $currentDateFormatted,
                        'tva' => $tva,
                        'rs' => $rs,
                        'tf' => $tf,
                        'totalHT' => $totalHT,
                        'totalTVA' => $totalTVA,
                        'totalRS' => $totalRS,
                        'totalTTC' => $totalTTC,
                        'totalTF' => $totalTF,
                        'totalNetapayer' => $totalNetapayer,

                    ]);

                    $currentDate = now()->format('d-m-Y');
                    return response()->streamDownload(function () use ($pdf) {

                        echo $pdf->output();
                    }, "rapport_des_honoraires_{$currentDate}.pdf");
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
