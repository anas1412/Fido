<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NoteDeDebit;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use App\Models\Client;
use App\Models\CompanySetting;
use Carbon\Carbon;

class NoteDeDebitPdfController extends Controller
{
    public function __invoke(NoteDeDebit $noteDeDebit)
    {
        $formattedDate = Carbon::parse($noteDeDebit->date)->format('d/m/Y');
        $currentDate = Carbon::now()->format('d-m-Y');

        $paddedNote = str_pad($noteDeDebit->note, 6, '0', STR_PAD_LEFT);

        $fileName = "NoteDeDebit_{$paddedNote}_{$currentDate}.pdf";

        $companySetting = CompanySetting::firstOrCreate(
            [],
            [
                'company_name' => 'Cabinet Ezzeddine Haouel',
                'slogan' => 'Comptable Commissaire aux comptes Membre de la compagnie des comptables de Tunisie',
                'mf_number' => '0729831E-A-P-000',
                'location' => 'Hammamet',
                'address_line1' => 'Av. Mohamed Ali Hammi',
                'address_line2' => '8050 Hammamet',
                'phone1' => '72 26 38 83',
                'phone2' => '26 43 69 22 - 27 43 69 22 - 28 43 69 22',
                'fax' => '72 26 38 79',
                'email' => 'ezzeddine.haouel@yahoo.fr',
            ]
        );

        $taxSettings = \App\Models\TaxSetting::first();
        return Pdf::loadView('note-de-debit', [
            'record' => $noteDeDebit,
            'formattedDate' => $formattedDate,
            'tva' => $taxSettings->tva,
            'rs' => $taxSettings->rs,
            'companySetting' => $companySetting,
        ])
            ->setPaper('A4', 'portrait')
            ->download($fileName);
    }
}
