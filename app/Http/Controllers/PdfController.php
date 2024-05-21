<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Honoraire;
use Barryvdh\DomPDF\Facade\Pdf;


class PdfController extends Controller
{
    public function __invoke(Honoraire $honoraire)
    {
        return Pdf::loadView('pdf', ['record' => $honoraire])
            ->download($honoraire->note . '.pdf');
    }
}
