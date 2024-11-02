<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use App\Models\Client;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function __invoke(Honoraire $honoraire)
    {
        $formattedDate = Carbon::parse($honoraire->date)->format('d/m/Y');
        $currentDate = Carbon::now()->format('d-m-Y');

        $paddedNote = str_pad($honoraire->note, 6, '0', STR_PAD_LEFT);

        $fileName = "Honoraire_{$paddedNote}_{$currentDate}.pdf";


        return Pdf::loadView('pdf', [
            'record' => $honoraire,
            'formattedDate' => $formattedDate,
            'tva' => config('taxes.tva'),
            'rs' => config('taxes.rs')
        ])
            ->setPaper('A4', 'portrait')
            ->download($fileName);
    }
}
