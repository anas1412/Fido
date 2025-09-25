<?php

namespace App\Http\Controllers;

use App\Helpers\FiscalHelper;
use App\Models\TaxSetting;
use Illuminate\Http\Request;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use App\Models\Client;
use App\Models\CompanySetting;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function __invoke(Honoraire $honoraire)
    {
        $formattedDate = Carbon::parse($honoraire->date)->format('d/m/Y');
        $currentDate = Carbon::now()->format('d-m-Y');

        $paddedNote = str_pad($honoraire->note, 6, '0', STR_PAD_LEFT);

        $fileName = "Honoraire_{$paddedNote}_{$currentDate}.pdf";


        $companySetting = CompanySetting::first();
        if (!$companySetting) {
            $companySetting = new CompanySetting([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }

        $taxSettings = TaxSetting::first();
        $template = config('honoraire.template', 'pdf-default');

        $pdf = Pdf::loadView($template, [
            'record' => $honoraire,
            'formattedDate' => $formattedDate,
            'tva' => $taxSettings->tva,
            'rs' => $taxSettings->rs,
            'companySetting' => $companySetting,
        ])
            ->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function generateRetenueSourcReport(Request $request)
    {
        $currentDate = Carbon::now()->format('d/m/Y');

        $companySetting = CompanySetting::first();
        if (!$companySetting) {
            $companySetting = new CompanySetting([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }

        // Assuming 'honoraires' are passed or fetched based on some criteria
        // For now, fetching all honoraires. This might need to be refined based on actual POST data.
        $honoraires = Honoraire::all();

        // Calculate totals
        $totalTTC = $honoraires->sum('montantTTC');
        $TotalRS = $honoraires->sum('rs');
        $totalNET = $honoraires->sum('netapayer');

        

        return Pdf::loadView('retenue-source', [
            'currentDate' => $currentDate,
            'companySetting' => $companySetting,
            'honoraires' => $honoraires,
            'totalTTC' => $totalTTC,
            'TotalRS' => $TotalRS,
            'totalNET' => $totalNET,
            'nom_beneficiaire' => $companySetting->company_name,
            'adresse_beneficiaire' => $companySetting->address_line1 . ' ' . $companySetting->address_line2,
            // Parse MF number using helper
            'matricule_fiscal_beneficiaire' => FiscalHelper::parseMfNumber($companySetting->mf_number)['matricule_fiscal'],
            'code_tva_beneficiaire' => FiscalHelper::parseMfNumber($companySetting->mf_number)['code_tva'],
            'code_categorie_beneficiaire' => FiscalHelper::parseMfNumber($companySetting->mf_number)['code_categorie'],
            'no_et_secondaire_beneficiaire' => FiscalHelper::parseMfNumber($companySetting->mf_number)['no_et_secondaire'],
        ])
            ->setPaper('A4', 'portrait')
            ->download('Certificat_Retenue_Source_' . $currentDate . '.pdf');
    }

    public function generateHonoraireReportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $honorairesQuery = Honoraire::query();

        if ($startDate && $endDate) {
            $honorairesQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $hs = $honorairesQuery->get(); // Renamed to $hs

        $companySetting = CompanySetting::first();
        if (!$companySetting) {
            $companySetting = new CompanySetting([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }

        $taxSettings = TaxSetting::first();

        // Calculate totals
        $totalHT = $hs->sum('montantHT');
        $totalTVA = $hs->sum('tva');
        $totalRS = $hs->sum('rs');
        $totalMontantTTC = $hs->sum('montantTTC');
        $totalTF = $hs->sum('tf');
        $totalNetapayer = $hs->sum('netapayer');

        $currentDate = Carbon::now()->format('d/m/Y'); // Format for the view
        $fileName = "Honoraire_Report_{$startDate}_to_{$endDate}_{$currentDate}.pdf";

        $pdf = Pdf::loadView('honoraire-report', [ // Changed view name
            'hs' => $hs, // Renamed variable
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companySetting' => $companySetting,
            'tva' => $taxSettings->tva, // Passed directly
            'rs' => $taxSettings->rs,   // Passed directly
            'currentDate' => $currentDate, // Passed currentDate
            'totalHT' => $totalHT,
            'totalTVA' => $totalTVA,
            'totalRS' => $totalRS,
            'totalTTC' => $totalMontantTTC,
            'totalTF' => $totalTF,
            'totalNetapayer' => $totalNetapayer,
        ])
            ->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function generateNoteDeDebitReportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $noteDeDebitsQuery = \App\Models\NoteDeDebit::query();

        if ($startDate && $endDate) {
            $noteDeDebitsQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $noteDeDebits = $noteDeDebitsQuery->get();

        $companySetting = \App\Models\CompanySetting::first();
        if (!$companySetting) {
            $companySetting = new \App\Models\CompanySetting([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }

        // Calculate totals
        $totalAmount = $noteDeDebits->sum('amount');

        $currentDate = Carbon::now()->format('d/m/Y');
        $fileName = "Note_De_Debit_Report_{$startDate}_to_{$endDate}_{$currentDate}.pdf";

        $pdf = Pdf::loadView('note-de-debit-report', [
            'noteDeDebits' => $noteDeDebits,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'companySetting' => $companySetting,
            'totalAmount' => $totalAmount,
            'currentDate' => $currentDate,
        ])
            ->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function generateRetenueSourceReportPdf(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $honorairesQuery = \App\Models\Honoraire::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->where('rs', '>', 0);

        $honoraires = $honorairesQuery->get();

        $clients = \App\Models\Client::all();
        $totalTTC = 0;
        $totalRS = 0;

        foreach ($clients as $client) {
            $clientHonoraires = $honoraires->where('client_id', $client->id);
            $client->totalClientTTC = $clientHonoraires->sum('montantTTC');
            $client->totalClientRS = $clientHonoraires->sum('rs');

            $totalTTC += $client->totalClientTTC;
            $totalRS += $client->totalClientRS;
        }

        // Filter clients to only include those with totalClientRS > 0
        $clients = $clients->filter(function ($client) {
            return $client->totalClientRS > 0;
        });

        $companySetting = \App\Models\CompanySetting::first();
        if (!$companySetting) {
            $companySetting = new \App\Models\CompanySetting([
                'company_name' => env('COMPANY_NAME', 'Default Company Name'),
                'slogan' => env('COMPANY_SLOGAN', 'Default Slogan'),
                'mf_number' => env('COMPANY_MF_NUMBER', 'Default MF Number'),
                'location' => env('COMPANY_LOCATION', 'Default Location'),
                'address_line1' => env('COMPANY_ADDRESS_LINE1', 'Default Address Line 1'),
                'address_line2' => env('COMPANY_ADDRESS_LINE2', 'Default Address Line 2'),
                'phone1' => env('COMPANY_PHONE1', 'Default Phone 1'),
                'phone2' => env('COMPANY_PHONE2', 'Default Phone 2'),
                'fax' => env('COMPANY_FAX', 'Default Fax'),
                'email' => env('COMPANY_EMAIL', 'default@example.com'),
            ]);
        }

        $taxSettings = \App\Models\TaxSetting::first();
        $rs = $taxSettings->rs * 100;
        $fiscalYear = config('fiscal_year.current_year');
        $parsedMf = \App\Helpers\FiscalHelper::parseMfNumber($companySetting->mf_number);

        $currentDate = Carbon::now()->format('d-m-Y');
        $fileName = "Retenue_Source_Report_{$startDate}_to_{$endDate}_{$currentDate}.pdf";

        $pdf = Pdf::loadView('retenue-source-report', [
            'fiscalYear' => $fiscalYear,
            'clients' => $clients,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentDate' => $currentDate,
            'rs' => $rs,
            'totalTTC' => $totalTTC,
            'totalRS' => $totalRS,
            'companySetting' => $companySetting,
            'matricule_fiscal_beneficiaire' => $parsedMf['matricule_fiscal'],
            'code_tva_beneficiaire' => $parsedMf['code_tva'],
            'code_categorie_beneficiaire' => $parsedMf['code_categorie'],
            'no_et_secondaire_beneficiaire' => $parsedMf['no_et_secondaire'],
        ])
            ->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }
}