@php
    use App\Helpers\NumberToWords;

    $netapayer = $record->netapayer;
    $dinarsInWords = NumberToWords::convertToWords($netapayer);
    $millimes = number_format($netapayer * 1000, 0, '.', '');
    $millimes = substr($millimes, -3);
    if (strlen($millimes) < 3) {
        $millimes = str_pad($millimes, 3, '0', STR_PAD_LEFT);
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note d'honoraires</title>
    <style>
        @page {
            size: A4;
            /* Set page size */
            margin-top: 15px;
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
            margin-bottom: 20px;
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
            right: 0;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            border: 1px;
            border-radius: 50%;
            text-align: center;
            line-height: 60px;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .logo img {
            width: 100px;
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
        }

        .header-line {
            border-top: 1px solid #000;
            margin-top: 10px;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details p {
            font-size: 1.2em;
            /* Increases the size of the text */
            margin: 0;
        }

        .invoice-details p strong {
            font-weight: bold;
            text-decoration: underline;
        }

        .client-info {
            text-align: right;
            margin-bottom: 20px;
        }

        .client-box {
            border: 1px solid #000;
            padding: 10px;
            display: inline-block;
            margin-left: 50%;
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
            /* Center the table */
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .total-in-words {
            margin-bottom: 80px;
            font-style: italic;
            position: relative;
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
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: auto;
            /* Pushes footer to the bottom */
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
                <img src="https://i.imgur.com/Yg33GrT.jpeg" alt="Logo">
            </div>
            <br>
            <div class="mf-number">M.F. : 729831 E-A-P- 000</div>
        </div>
        <div class="header-line"></div>
    </div>

    <div class="invoice-details">
        <p><strong>Note d'honoraires :</strong> N°{{ str_pad($record->note, 8, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="client-info">
        <p>Hammamet le : {{ $formattedDate }}</p>
        <div class="client-box">
            <p>Client: {{ $record->client->name }}</p>
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
                <th>Description</th>
                <th>Montant</th>
            </tr>
            <tr>
                <td>Montant H.T</td>
                <td>{{ number_format($record->montantHT, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>T.V.A 19%</td>
                <td>{{ number_format($record->tva, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Montant T.T.C</td>
                <td>{{ number_format($record->montantTTC, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>R/S 3%</td>
                <td>{{ number_format($record->rs, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td>Timbre Fiscal</td>
                <td>{{ number_format($record->tf, 3, '.', ',') }}</td>
            </tr>
            <tr>
                <td><strong>Net à payer</strong></td>
                <td><strong>{{ number_format($record->netapayer, 3, '.', ',') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="total-in-words">
        <p>Arrêtée la présente note d'honoraires à la somme de :
            {{ $dinarsInWords }} dinars et {{ $millimes }} millimes.</p>
        {{-- {{ $frenchWords }} dinars et {{ number_format(($record->netapayer * 1000), 0, '.', ',') | slice(-3) | replace({' ': '0'}) }} millimes.</p> --}}</p>
        <p class="signature-text"><strong>Cachet et signature</strong></p>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>Av. Mohamed Ali Hammi</td>
                <td>Tél : 72 26 38 83</td>
                <td>GSM : 97 43 69 22 / 26 43 69 22</td>
            </tr>
            <tr>
                <td>8050 Hammamet</td>
                <td>Fax : 72 26 38 79</td>
                <td>Email : ezzeddine.haouel@yahoo.fr</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Email : ezzeddine.haouel@topnet.tn</td>
            </tr>
        </table>
    </div>
</body>

</html>
