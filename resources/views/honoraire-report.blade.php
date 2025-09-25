<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Honoraires</title>
    <style>
        @page {
            size: A4;
            margin-top: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            margin-top: 0;
            margin-bottom: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            min-height: 100%;
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
            height: auto;
            border: 1px;
            border-radius: 50%;
            object-fit: cover;
        }

        .mf-number {
            font-size: 12px;
            text-decoration: underline;
            text-align: right;
        }

        .header-line {
            border-top: 1px solid #000;
            margin-top: 8px;
        }

        /* Updated table styles only */
        .retenue-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;

        }

        .retenue-table th,
        .retenue-table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            /* Reduced padding */
            text-align: left;
            white-space: nowrap;
        }

        .retenue-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .retenue-table th:nth-child(1),
        .retenue-table td:nth-child(1) {
            width: 10%;
            text-align: right;
            /* Réf.Honoraire */
        }


        .retenue-table th:nth-child(2),
        .retenue-table td:nth-child(2) {
            width: 10%;
            text-align: center;
            /* Date */
        }


        .retenue-table th:nth-child(3),
        .retenue-table td:nth-child(3) {
            width: 28%;
            white-space: normal;
            /* Override the nowrap property */
            word-wrap: break-word;
            text-align: left;
            /* Client */
        }

        .retenue-table th:nth-child(4),
        .retenue-table td:nth-child(4) {
            width: 10%;
            text-align: right;
            /* Total H.T */
        }

        .retenue-table th:nth-child(5),
        .retenue-table td:nth-child(5) {
            width: 7%;
            text-align: right;
            /* T.V.A */
        }

        .retenue-table th:nth-child(6),
        .retenue-table td:nth-child(6) {
            width: 10%;
            text-align: right;
            /* R.S */
        }

        .retenue-table th:nth-child(7),
        .retenue-table td:nth-child(7) {
            width: 10%;
            text-align: right;
            /* Montant T.T.C */
        }

        .retenue-table th:nth-child(8),
        .retenue-table td:nth-child(8) {
            width: 5%;
            text-align: right;
            /* Timbre */
        }

        .retenue-table th:nth-child(9),
        .retenue-table td:nth-child(9) {
            width: 10%;
            text-align: right;
            /* Net à payer */
        }

        .footer {
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #000;
            padding-top: 3px;
            position: absolute;
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
            <h1>{{ $companySetting->company_name }}</h1>
            {{-- <p>Comptable Commissaire aux comptes Membre de la</p>
            <p>compagnie des comptables de Tunisie</p> --}}
        </div>
        <div class="invoice-purpose">
            <p><strong>Période: </strong>{{ $startDate }} à {{ $endDate }}</p>
            <p><strong>Edité le: </strong>{{ $currentDate }}</p>
        </div>
        <div class="logo-container">
            <div class="logo">
                <img src="{{ public_path('images/CCT.jpg') }}" alt="Logo">
            </div>
        </div>
        <br>
        <div class="mf-number">M.F. : {{ $companySetting->mf_number }}</div>

        <div class="header-line"></div>
    </div>

    <h2>RAPPORT DES HONORAIRES</h2>
    <table class="retenue-table">
        <thead>
            <tr>
                <th>Réf.</th>
                <th>Date</th>
                <th>Client</th>
                <th>H.T</th>
                <th>T.V.A</th>
                <th>R.S</th>
                <th>T.T.C</th>
                <th>Timbre</th>
                <th>Net à payer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hs as $honoraire)
                <tr>
                    <td>{{ substr(str_pad($honoraire->note, 8, '0', STR_PAD_LEFT), 0, 4) . '/' . substr(str_pad($honoraire->note, 8, '0', STR_PAD_LEFT), 4, 4) }}</td>
                    <td>{{ date('d/m/Y', strtotime($honoraire->date)) }}</td>
                    <td>{{ $honoraire->client->name }}</td>
                    <td>{{ number_format($honoraire->montantHT, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->tva, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->rs, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->montantTTC, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->tf, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->netapayer, 3, '.', ',') }}</td>

                </tr>
            @endforeach
            <br><br>
            <tr>
                <td style="text-align: right;" colspan="3"><strong>TOTAUX:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalHT, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalTVA, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalRS, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalTTC, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalTF, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalNetapayer, 3, '.', ',') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>{{ $companySetting->address_line1 }}</td>
                <td>Tél : {{ $companySetting->phone1 }}</td>
                <td>GSM : {{ $companySetting->phone2 }}</td>
            </tr>
            <tr>
                <td>{{ $companySetting->address_line2 }}</td>
                <td>Fax : {{ $companySetting->fax }}</td>
                <td>Email : {{ $companySetting->email }}</td>
            </tr>
        </table>
    </div>
</body>

</html>