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

        return Pdf::loadView($template, [
            'record' => $honoraire,
            'formattedDate' => $formattedDate,
            'tva' => $taxSettings->tva,
            'rs' => $taxSettings->rs,
            'companySetting' => $companySetting,
        ])
            ->setPaper('A4', 'portrait')
            ->download($fileName);
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
}
