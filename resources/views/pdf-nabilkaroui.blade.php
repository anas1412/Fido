@php
    use App\Helpers\NumberToWords;

    // Values
    $ht   = $record->montantHT;
    $tvaV = $record->tva;
    $ttc  = $record->montantTTC;
    $rsV  = $record->rs;
    $tfV  = $record->tf;
    $nap  = $record->netapayer;

    $tvaRate = $tva ?? 0.19;
    $rsRate  = $rs  ?? 0.03;

    // Invoice number format
    $year = isset($record->date) ? date('Y', strtotime($record->date)) : date('Y');
    $seq  = str_pad(($record->note ?? 0), 3, '0', STR_PAD_LEFT);
    $invoiceNo = "{$seq}/{$year}";

    // Amount in words
    $dinars   = floor($nap);
    $millimes = round(($nap - $dinars) * 1000);
    $dinarsInWords = NumberToWords::convertToWords($dinars);
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Honoraire N°{{ substr_replace(str_pad($record->note, 8, '0', STR_PAD_LEFT), '/', -4, 0) }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 13.5px; color:#000; }

        /* Header, Date, Title, Debtor styles */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-left { width: 50%; text-align: left; vertical-align: top; text-transform: uppercase; }
        .header-left .slogan { font-weight: normal; text-transform: none; margin-top: 2px; }
        .header-right { width: 50%; text-align: right; vertical-align: top; }
        .header-right img { width: 120px; height: auto; margin-bottom: 4px; }
        .date { text-transform:uppercase; font-size:13px; }
        .title { text-align:center; margin:20px 0 95px; font-size:18px; font-weight:bold; text-decoration:underline; }
        .debtor { margin-bottom:8px;  text-transform:uppercase; display:block;}
        .debtor .mf { margin-top:2px; }

        /* Main Table Styles */
        table.box { width:100%; border-collapse:collapse; margin-top:10px; }
        table.box th, table.box td { border:2px solid #000; }
        table.box th { background:#fff; font-weight:bold; text-align:center; padding:8px 10px; }
        
        table.box td.desc-main {
            text-align: center;
            vertical-align: middle;
            padding-top: 35px;
            padding-bottom: 35px;
            width: 70%;
        }
        table.box td.amt-main {
            text-align: right;
            vertical-align: middle;
            padding-top: 35px;
            padding-bottom: 35px;
            padding-right: 10px;
            width: 30%;
        }

        /* Totals Section Styling */
        .totals-row td {
            padding: 0; /* Remove padding from cells themselves */
        }
        .totals-label {
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
            padding: 4px 10px; /* Add padding here */
        }

        /* --- THE CLEAN SOLUTION FOR ROWSPAN ALIGNMENT --- */
        .merged-values-cell {
            padding: 0;
            vertical-align: top; /* This aligns the cell to the top, fixing the row spacing. */
        }
        .flex-wrapper {
            /* This container now just holds the numbers. Vertical centering is no longer needed. */
        }
        .flex-wrapper div {
            text-align: right;
            padding: 4px 10px; /* Consistent padding for each number */
        }
        /* --- END OF SOLUTION --- */
        
        /* Footer styles */
        .inwords { margin:15px 0 30px; font-size:14px; }
        .sign { text-align:right; font-weight:bold; margin-top:20px; }
        .footer { position:fixed; bottom:15mm; width:100%; text-align:center; font-size:12px; }
        .footer hr { border:none; border-top:1px solid #000; margin-bottom:4px; }
        .footer div { margin:2px 0; }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-left">
                <div>FIDUCIAIRE KAROUI NABIL</div>
                <div class="slogan">{{ $companySetting->slogan ?? 'BUREAU DE COMPTABILITÉ' }}</div>
            </td>
            <td class="header-right">
                <img src="{{ public_path('images/CCT.jpg') }}" alt="Logo">
                <div class="date">HAMMAMET <u>Le {{ $formattedDate }}</u></div>
            </td>
        </tr>
    </table>

    <div class="title">HONORAIRE N°{{ substr_replace(str_pad($record->note, 8, '0', STR_PAD_LEFT), '/', -4, 0) }}</div>
    <div class="debtor"><u><b>DOIT: {{ $record->client->name }} <br><span class="mf">M.F.: {{ $record->client->mf }}</span></b></u></div>

    <!-- Table with rowspan and correct alignment -->
    <table class="box">
        <thead>
            <tr>
                <th>DESIGNATIONS</th>
                <th>MONTANTS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="desc-main"><strong>{{ $record->object }}</strong></td>
                <td class="amt-main">{{ number_format($ht, 3, '.', ',') }}</td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">TOTAL H.T</td>
                <td class="merged-values-cell" rowspan="5">
                    <div class="flex-wrapper">
                        <div>{{ number_format($ht, 3, '.', ',') }}</div>
                        <div>{{ number_format($tvaV, 3, '.', ',') }}</div>
                        <div>{{ number_format($ttc, 3, '.', ',') }}</div>
                        <div>{{ number_format($rsV, 3, '.', ',') }}</div>
                        <div>{{ number_format($tfV, 3, '.', ',') }}</div>
                    </div>
                </td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">T.V.A {{ (int)round($tvaRate*100) }}%</td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">TOTAL T.T.C</td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">RETENUE A LA SOURCE {{ (int)round($rsRate*100) }}%</td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">DROIT DE TIMBRE</td>
            </tr>
            <tr class="totals-row">
                <td class="totals-label">NET A PAYER</td>
                <td style="text-align: right; padding: 4px 10px;">
                    <b>{{ number_format($nap, 3, '.', ',') }}</b>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="inwords"><u>Arrêter la présente facture à la somme de {{ ucfirst($dinarsInWords) }} Dinars{{ $millimes ? ' et '.$millimes.' millimes' : '' }}.</u></div>
    <br><br>
    <div class="sign"><u>LE COMPTABLE</u><br>{{ $companySetting->accountant_name ?? 'NABIL KAROUI' }}</div>
    <div class="footer">
        <strong><div>{{ $companySetting->address_line1 }} {{ $companySetting->address_line2 }}</div>
        <div>T.V.A.: 729 544 A A P 000 / RNE: 0729544A</div>
        <div>TEL/FAX: {{ $companySetting->fax }} / MOBILE: {{ $companySetting->phone1 }}/{{ $companySetting->phone2 }}</div></strong>
    </div>

</body>
</html>