<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        /* A4 Page Formatting & Margins */
        @page {
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 1.5cm 2.5cm; 
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding-bottom: 3cm; 
        }
        
        /* --- Header & Logo --- */
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .logo-container img {
            width: 220px;
        }
        .header-date {
            text-align: right;
            margin-bottom: 30px;
        }

        /* --- Client & Invoice Info --- */
        .client-info {
            margin-bottom: 30px;
        }
        
        /* MODIFIED: This class is now for the inline address block */
        .client-details-inline {
            display: inline-block;
            vertical-align: top; /* Aligns the top of the address with the "Client:" label */
            margin-left: 10px; /* Adds a small space after the colon */
        }
        
        .invoice-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        /* --- Items Table --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }
        .items-table th {
            font-weight: bold;
        }
        .items-table .designation {
            text-align: left;
            width: 35%;
        }
        .total-row td {
            font-weight: bold;
        }

        /* --- Totals & Payment Details --- */
        .amount-in-words {
            margin-top: 25px;
            margin-bottom: 40px;
        }
        .amount-centered {
            text-align: center;
            margin-top: 5px;
        }
        .payment-details {
            position: relative;
            margin-bottom: 20px;
        }
        .payment-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-details td {
            padding: 3px 0;
            vertical-align: top;
        }
        .payment-details .label {
            width: 160px;
        }
        .payment-details .spacer-row {
            height: 15px;
        }
        .stamp {
            position: absolute;
            right: 20px;
            bottom: -20px;
            width: 120px;
            height: 120px;
        }

        /* --- Footer --- */
        .footer {
            position: fixed;
            bottom: 1.5cm;
            left: 2.5cm;
            right: 2.5cm;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    @php
        use App\Helpers\NumberToWords; 
        
        $totalPackages = 0;
        $totalGrossWeight = 0;
        $totalNetWeight = 0;
        $totalAmount = 0;
        foreach ($invoiceItems as $item) {
            $totalPackages += $item->quantity;
            $totalGrossWeight += $item->commercial_details['poids_brut_kg'] ?? 0;
            $totalNetWeight += $item->commercial_details['poids_net_kg'] ?? 0;
            $totalAmount += $item->total_price;
        }
        
        $euros = floor($totalAmount);
        $amountInWords = NumberToWords::convertToWords($euros, 'fr');
    @endphp

    <div class="container">
        
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('images/NetuFreshLogo.jpg') }}" alt="Netu Fresh Logo">
            </div>
        </div>
        
        <div class="header-date">
            Hammamet le {{ $formattedDate }}
        </div>

        <div class="client-info">
            {{-- MODIFIED HTML STRUCTURE FOR INLINE DISPLAY --}}
            <strong style="margin-left: 100px; display: inline-block; vertical-align: top;">Client:</strong>
            <div class="client-details-inline">
                {{ $invoice->client->name}}<br>
                {{ $invoice->client->address}}<br>
                {{ $invoice->client->city}}
            </div>
        </div>

        <div class="invoice-title">
            Facture N° {{ $invoice->invoice_number }}
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="designation">Designation</th>
                    <th>Nombre de colis/Paloxe</th>
                    <th>Poids Brut Kg</th>
                    <th>Poids Net Kg</th>
                    <th>Prix Unitaire(€)</th>
                    <th>Total (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceItems as $item)
                    <tr>
                        <td class="designation">{{ $item->object }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->commercial_details['poids_brut_kg'] ?? 0, 0, ',', ' ') }}</td>
                        <td>{{ number_format($item->commercial_details['poids_net_kg'] ?? 0, 0, ',', ' ') }}</td>
                        <td>{{ number_format($item->single_price, 2, ',', ' ') }}</td>
                        <td>{{ number_format($item->total_price, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td class="designation">Total</td>
                    <td>{{ $totalPackages }}</td>
                    <td>{{ number_format($totalGrossWeight, 0, ',', ' ') }}</td>
                    <td>{{ number_format($totalNetWeight, 0, ',', ' ') }}</td>
                    <td></td>
                    <td>{{ number_format($totalAmount, 2, ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="amount-in-words">
            Arrêtée la présente facture à la somme de :
            <div class="amount-centered">
                <strong>{{ ucfirst($amountInWords) }} EUROS</strong>
            </div>
        </div>

        <div class="payment-details">
            <table>
                <tr>
                    <td class="label">* Mode de paiement:</td>
                    <td>{{ $invoice->mode_de_paiement }}</td>
                </tr>
                <tr>
                    <td class="label">* Mode de livraison:</td>
                    <td>{{ $invoice->mode_de_livraison }}</td>
                </tr>
                <tr class="spacer-row"><td colspan="2"></td></tr>
                <tr>
                    <td class="label">* Banque:</td>
                    <td>
                        {{ $invoice->banque }}
                    </td>
                </tr>
                <tr>
                    <td class="label">* IBAN:</td>
                    <td>{{ $invoice->iban }}</td>
                </tr>
                <tr>
                    <td class="label">* Swift:</td>
                    <td>{{ $invoice->swift }}</td>
                </tr>
                <tr class="spacer-row"><td colspan="2"></td></tr>
                <tr>
                    <td class="label">N° de lot</td>
                    <td>{{ $invoice->nombre_de_lot}}</td>
                </tr>
            </table>

            <img src="{{ public_path('images/netu-fresh-stamp.png') }}" alt="Stamp" class="stamp">
        </div>

    </div>

    <div class="footer">
        Siege Sociale : {{ $companySetting->address_line1 }} {{ $companySetting->address_line2 }}<br>
        Gsm : {{ $companySetting->phone1 }} - {{ $companySetting->phone2 }}<br>
        Email : {{ $companySetting->email }}<br>
        Rc : 1922773C - MF: {{ $companySetting->mf_number }}
    </div>

</body>
</html>