<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Retenue à la Source</title>
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

        .retenue-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .retenue-table th,
        .retenue-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .retenue-table th {
            background-color: #f2f2f2;
        }

        .retenue-table th:nth-child(2),
        .retenue-table th:nth-child(3),
        .retenue-table td:nth-child(2),
        .retenue-table td:nth-child(3) {
            text-align: center;
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
        {{-- <div class="invoice-purpose">
            <p><strong>Période: </strong>{{ $startDate }} à {{ $endDate }}</p>
            <p><strong>Edité le: </strong>{{ $currentDate }}</p>
        </div> --}}
        <div class="logo-container">
            <div class="logo">
                <img src="{{ public_path('images/CCT.jpg') }}" alt="Logo">
            </div>
        </div>
        <br>
        <div class="mf-number">M.F. : 0729831E-A-P-000</div>
        <div class="header-line"></div>
    </div>

    <h2>Rapport de Retenue à la Source</h2>

    <div class="client-info">
        <div class="client-box">
            <p>Client: <strong>{{ $client->name }}</strong></p>
            <p>Adresse: {{ $client->address }}</p>
            <p>M.F.: {{ $client->mf }}</p>
        </div>
    </div>

    <div class="invoice-purpose">
        <p><strong>Période: </strong>{{ $startDate }} à {{ $endDate }}</p>
    </div>

    <table class="retenue-table">
        <thead>
            <tr>
                <th>Objet</th>
                <th>Montant T.T.C</th>
                <th>R.S({{ $rs }}%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($honoraires as $honoraire)
                <tr>
                    <td>{{ $honoraire->object }}</td>
                    <td>{{ number_format($honoraire->montantTTC, 3, '.', ',') }}</td>
                    <td>{{ number_format($honoraire->rs, 3, '.', ',') }}</td>
                </tr>
            @endforeach
            <br><br> <!-- Add a line break -->
            <tr>
                <td style="text-align: right;"><strong>TOTAUX:</strong></td>
                <td style="text-align: center;"><strong>{{ number_format($totalTTC, 3, '.', ',') }}</strong></td>
                <td style="text-align: center;"><strong>{{ number_format($totalRS, 3, '.', ',') }}</strong></td>
            </tr>
        </tbody>
    </table>

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
