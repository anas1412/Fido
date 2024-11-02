@php
    use App\Helpers\NumberToWords;

    $netapayer = $record->netapayer;
    $dinars = floor($netapayer);
    $millimes = round(($netapayer - $dinars) * 1000);
    $dinarsInWords = NumberToWords::convertToWords($dinars);
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note d'honoraire</title>
    <style>
        @page {
            size: A4;
            /* Set page size */
            margin-top: 10px;
            /* Set margins to zero */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            /* Increased from 12px to 14px */
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            margin-top: 0;
            margin-bottom: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .header {
            position: relative;
            margin-bottom: 0px;
        }

        .header h1 {
            font-size: 22px;
            margin: 0 0 5px 0;
        }

        .header p {
            margin: 0;
            font-size: 20px;
        }

        .logo-container {
            position: absolute;
            top: 0;
            right: 0px;
            /* Moved 20px to the right */
            text-align: center;
        }

        .logo {
            width: 100px;
            height: 100px;
            border: 2px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            font-size: 10px;
            margin-bottom: 0px;
        }

        .logo img {
            width: 130px;
            /* Adjust the width as needed */
            height: auto;
            /* Adjust the height automatically to maintain aspect ratio */
            border: 1px;
            border-radius: 50%;
            object-fit: cover;
            /* Ensures the image fits well within the container */
        }

        .mf-number {
            font-size: 12px;
            text-decoration: underline;
            /* Underline the text */
            text-align: right;
            /* Align the text to the right */
        }

        .header-line {
            border-top: 1px solid #000;
            margin-top: 8px;
        }

        .invoice-details {
            margin: 0;
            padding-top: 0px;
            font-size: 1.2em;
        }

        .client-info {
            text-align: right;
            margin-bottom: 5px;
            font-size: 1.2em;
        }

        .client-box {
            border: 1px solid #000;
            padding: 5px;
            display: inline-block;
            margin-left: 30%;
            text-align: left;
            line-height: 1.2;
            /* Reduce line height to bring paragraphs closer */
        }

        .client-box p {
            margin: 0 0 5px 0;
            /* Reduce bottom margin of paragraphs */
        }

        .client-box p:last-child {
            margin-bottom: 0;
            /* Remove bottom margin from last paragraph */
        }

        .invoice-purpose {
            margin-bottom: 20px;
        }

        .invoice-purpose p {
            font-size: 1.2em;
            /* Increases the size of the text */
        }

        .invoice-purpose p strong {
            font-weight: bold;
            text-decoration: underline;
        }

        .invoice-table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Full viewport height */
            width: 100%;
            /* Full viewport width */
        }

        .invoice-table {
            width: 50%;
            border-collapse: collapse;
            margin: auto;
            margin-bottom: 10px;
            font-size: 18px;
            /* Center the table */
        }

        .invoice-table th,
        .invoice-table td {
            border: none;
            padding: 8px;
        }

        .invoice-table th:first-child,
        .invoice-table td:first-child {
            text-align: left;
            width: 40%;
        }

        .invoice-table th:nth-child(2),
        .invoice-table td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
            width: 40%;
        }

        .total-in-words {
            margin-bottom: 80px;
            position: relative;
            font-size: 15px;
            /* This allows positioning of the signature text */
        }

        .signature-text {
            text-align: right;
            /* Aligns the text to the right */
            margin-top: 20px;
            /* Adds space between the text above */
            font-weight: bold;
            /* Makes the text bold */
        }

        .footer {
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 3px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            padding: 0px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <h1>Cabinet Ezzeddine Haouel</h1>
            <p>Comptable Commissaire aux comptes Membre de la</p>
            <p>compagnie des comptables de Tunisie</p>
        </div>
        <div class="logo-container">
            <div class="logo">
                <img src="{{ public_path('images/CCT.jpg') }}" alt="Logo">
            </div>

        </div>
        <br>
        <div class="mf-number">M.F. : 0729831E-A-P-000</div>
        <div class="header-line"></div>
    </div>

    <div class="invoice-details">
        <p><u>Note d'honoraires :</u> N°{{ substr_replace(str_pad($record->note, 8, '0', STR_PAD_LEFT), '/', -4, 0) }}
        </p>
    </div>

    <div class="client-info">
        <p>Hammamet le : {{ $formattedDate }}</p>
        <div class="client-box">
            <p>Client: <strong>{{ $record->client->name }}</strong></p>
            <p>Adresse: {{ $record->client->address }}</p>
            <p>M.F.: {{ $record->client->mf }}</p>
        </div>
    </div>

    <div class="invoice-purpose">
        <p><strong>Objet d'honoraire :</strong> {{ $record->object }}.</p>
    </div>

    <div class="invoice-table-container">
        <table class="invoice-table">
            <tr>
                <td>Montant H.T</td>
                <td>:</td>
                <td>{{ number_format($record->montantHT, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>T.V.A {{ $tva * 100 }}%</td>
                <td>:</td>
                <td>{{ number_format($record->tva, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Montant T.T.C</td>
                <td>:</td>
                <td>{{ number_format($record->montantTTC, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>R/S {{ $rs * 100 }}%</td>
                <td>:</td>
                <td>{{ number_format($record->rs, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Timbre Fiscal</td>
                <td>:</td>
                <td>{{ number_format($record->tf, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Net à payer</strong></td>
                <td>:</td>
                <td><strong>{{ number_format($record->netapayer, 3, '.', ',') }}</strong></td>
            </tr>
        </table>
    </div>
    <br>
    <div class="total-in-words">
        <p>Arrêtée la présente note d'honoraires à la somme de :
            {{ $dinarsInWords }} dinars et {{ $millimes }} millimes.
        </p>
        <p class="signature-text"><strong>Cachet et signature</strong></p>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>Av. Mohamed Ali Hammi</td>
                <td>Tél : 72 26 38 83</td>
                <td>GSM : 26 43 69 22 - 27 43 69 22 - 28 43 69 22 </td>
            </tr>
            <tr>
                <td>8050 Hammamet</td>
                <td>Fax : 72 26 38 79</td>
                <td>Email : ezzeddine.haouel@yahoo.fr</td>
            </tr>
        </table>
    </div>
</body>

</html>
