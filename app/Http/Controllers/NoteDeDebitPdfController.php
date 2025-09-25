<?php

namespace App\Http\Controllers;

use App\Models\TaxSetting;
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

        $companySetting = CompanySetting::first();

        $taxSettings = TaxSetting::first();
        return Pdf::loadView('note-de-debit', [
            'record' => $noteDeDebit,
            'formattedDate' => $formattedDate,
            'tva' => $taxSettings->tva,
            'rs' => $taxSettings->rs,
            'companySetting' => $companySetting,
        ])
            ->setPaper('A4', 'portrait')
            ->stream($fileName);
    }
}
