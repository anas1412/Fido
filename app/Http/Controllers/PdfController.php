<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;


class PdfController extends Controller
{
    public function __invoke(Honoraire $honoraire)
    {
        /* return Pdf::loadView('pdf', ['record' => $honoraire])
            ->download($honoraire->note . '.pdf'); */

        // Format the date
        $formattedDate = $honoraire->created_at->format('d/m/Y');
        /* $amountInWords = NumberToWords::convertToWords($honoraire->amount); */

        return Pdf::loadView('pdf', [
            'record' => $honoraire,
            'formattedDate' => $formattedDate,
            'tva' => config('taxes.tva'),
            'rs' => config('taxes.rs')
        ])
            ->setPaper('A4', 'portrait') // Set paper size and orientation
            ->download($honoraire->note . '.pdf');
    }
}
