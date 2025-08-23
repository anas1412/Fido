<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\CompanySetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoicePdfController extends Controller
{
    public function __invoke(Invoice $invoice)
    {
        $formattedDate = Carbon::parse($invoice->invoice_date)->format('d/m/Y');
        $currentDate = Carbon::now()->format('d-m-Y');

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

        // Fetch invoice items
        $invoiceItems = $invoice->invoiceItems ?? collect(); // Ensure it's a collection even if null

        $fileName = "Facture_{$invoice->invoice_number}_{$currentDate}.pdf";

        return Pdf::loadView('invoice-pdf', [
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
            'formattedDate' => $formattedDate,
            'companySetting' => $companySetting,
        ])
            ->setPaper('A4', 'portrait')
            ->download($fileName);
    }
}
