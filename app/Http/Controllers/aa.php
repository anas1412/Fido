<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Honoraire;
use App\Models\TaxSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HonoraireReportController extends Controller
{
    public function generate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $currentDateFormatted = now()->format('d/m/Y');
        $clients = Client::all();
        $allHonoraires = collect();

        $hs = Honoraire::whereBetween('date', [$startDate, $endDate])->get();

        $startDateFormatted = date('d/m/Y', strtotime($startDate));
        $endDateFormatted = date('d/m/Y', strtotime($endDate));

        $taxSettings = TaxSetting::first();
        $tva = $taxSettings->tva * 100;
        $rs = $taxSettings->rs * 100;
        $tf = $taxSettings->tf * 100;

        // Initialize totals
        $totalHT = $hs->sum('montantHT');
        $totalTVA = $hs->sum(function ($honoraire) use ($taxSettings) {
            return $honoraire->montantHT * $taxSettings->tva;
        });
        $totalRS = $hs->sum(function ($honoraire) use ($taxSettings) {
            return ($honoraire->montantHT + $honoraire->montantHT * $taxSettings->tva) * $taxSettings->rs;
        });
        $totalTTC = $hs->sum(function ($honoraire) use ($taxSettings) {
            return $honoraire->montantHT + ($honoraire->montantHT * $taxSettings->tva);
        });
        $totalTF = $hs->sum(function () use ($taxSettings) {
            return $taxSettings->tf;
        });
        $totalNetapayer = $totalTTC - $totalRS + $totalTF;

        $companySetting = CompanySetting::first();

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
            'companySetting' => $companySetting,
        ]);

        $currentDate = now()->format('d-m-Y');
        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="rapport_des_honoraires_{$currentDate}.pdf"',
        ]);
    }
}
