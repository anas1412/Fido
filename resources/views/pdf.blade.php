<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Note d'Honoraires</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 21cm;
            margin: 0 auto;
            padding: 2cm;

        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header .left {
            text-align: left;
        }

        .header .right {
            text-align: right;
        }

        .header .right img {
            max-width: 100px;
        }

        .details,
        .amounts,
        .footer {
            margin-bottom: 20px;
        }

        .details p,
        .amounts p {
            margin: 5px 0;
        }

        .client-info {
            text-align: right;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .amounts table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 2cm;
        }

        .amounts th,
        .amounts td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }

        .amounts th {
            text-align: left;
        }

        .footer {
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="left">
                <p><strong>Cabinet Ezzeddine Haouel</strong></p>
                <p>Comptable Commissaire aux comptes</p>
                <p>Membre de la compagnie des comptables de Tunisie</p>
            </div>
            <div class="right">
                {{-- <img src="{{ asset('images/logo.jpg') }}" alt="Logo"> --}}
                <p>M.F.: 729831 E-A-P-000</p>
            </div>
        </div>
        <div class="details">
            <p><strong>Note d'honoraires :</strong> Nº{{ $record->note }}</p>
            <p style="text-align: right;">Hammamet le : {{ $record->created_at }}</p>
        </div>
        <div class="client-info">
            <p><strong>Client :</strong> {{ $record->client->name }}</p>
            <p><strong>Adresse :</strong> {{ $record->client->address }}</p>
            <p><strong>M.F. :</strong> {{ $record->client->mf }}</p>
        </div>
        <div class="details">
            <p><strong>Objet d'honoraire :</strong> {{ $record->object }}</p>
        </div>
        <div class="amounts">
            <table>
                <tr>
                    <th>Montant H.T</th>
                    <td>{{ number_format($record->montantHT, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>T.V.A</th>
                    <td>{{ number_format($record->tva, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>Montant T.T.C</th>
                    <td>{{ number_format($record->montantTTC, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>R/S</th>
                    <td>{{ number_format($record->rs, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <th>Timbre Fiscal</th>
                    <td>{{ number_format($record->tf, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <th><strong>Total à payer :</strong></th>
                    <td><strong>{{ number_format($record->netapayer, 3, '.', ',') }}</strong></td>
                </tr>
            </table>
        </div>
        <div class="details">
            <p><strong>Arrêtée la présente note d'honoraire à la somme de :</strong> {{-- {{ $frenchWords }} dinars et {{ number_format(($record->netapayer * 1000), 0, '.', ',') | slice(-3) | replace({' ': '0'}) }} millimes.</p> --}}
            <p style="text-align: right;"><strong>Cachet et signature</strong></p>
        </div>
        <div class="footer">
            <p>Av. Mohamed Ali Hammi 8050 Hammamet</p>
            <p>Tél: 72 26 38 83 | Fax: 72 26 38 79 | GSM: 97 43 69 22 / 26 43 69 22</p>
            <p>Email: ezzeddine.haouel@yahoo.fr</p>
        </div>
    </div>
</body>

</html>
