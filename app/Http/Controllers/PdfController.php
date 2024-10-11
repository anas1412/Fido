<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use App\Models\Client;


class PdfController extends Controller
{
    public function __invoke(Honoraire $honoraire)
    {
        /* return Pdf::loadView('pdf', ['record' => $honoraire])
            ->download($honoraire->note . '.pdf'); */

        // Format the date
        $formattedDate = \Carbon\Carbon::parse($honoraire->date)->format('d/m/Y');
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

    public function generateRetenueSourcReport(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $honoraires = Honoraire::where('client_id', $client->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Calculate total amount and total retenue source
        $totalAmount = $honoraires->sum('amount');
        $totalRetenueSource = $honoraires->sum(function ($honoraire) {
            return $honoraire->amount * (config('taxes.rs') / 100);
        });

        return Pdf::loadView('retenue-source', [
            'client' => $client,
            'honoraires' => $honoraires,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalAmount' => $totalAmount,
            'totalRetenueSource' => $totalRetenueSource,
        ])
            ->setPaper('A4', 'portrait')
            ->download("rapport_retenue_source_{$client->name}.pdf");
    }
}
