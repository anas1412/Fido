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

        .header > div:first-child {
            margin-right: 150px; /* Adjust this value as needed to prevent overlap */
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
            white-space: nowrap;
        }

        .retenue-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        /* Set specific column widths */
        .retenue-table th:nth-child(1),
        .retenue-table td:nth-child(1) {
            width: 60%;
            /* Client - bigger column */
        }

        .retenue-table th:nth-child(2),
        .retenue-table td:nth-child(2) {
            width: 20%;
            /* M.F */
            text-align: center;
            /* Center M.F title and contents */
        }

        .retenue-table th:nth-child(3) {
            width: 10%;
            /* Montant T.T.C */
            text-align: center;
            /* Center title only */
        }

        .retenue-table th:nth-child(4) {
            width: 10%;
            /* R.S */
            text-align: right;
            /* Center title only */
        }

        .retenue-table td:nth-child(3),
        .retenue-table td:nth-child(4) {
            /* text-align: right; */
            text-align: right;
            /* Right align contents of Montant T.T.C and R.S */
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
            <p>{{ $companySetting->slogan }}</p>
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
        <div class="mf-number">M.F. : {{ $companySetting->mf_number }}</div>

        <div class="header-line"></div>
    </div>

    <h2>Etat Retenue à la Source Année {{ $fiscalYear }}</h2>
    <table class="retenue-table">
        <thead>
            <tr>
                <th>Client</th>
                <th>M.F</th>
                <th>Montant T.T.C</th>
                <th>R.S ({{ $rs }}%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->mf }}</td>
                    <td>{{ number_format($client->totalClientTTC, 3, '.', ',') }}</td>
                    <td>{{ number_format($client->totalClientRS, 3, '.', ',') }}</td>
                </tr>
            @endforeach
            <br><br>
            <tr>
                <td style="text-align: right;" colspan="2"><strong>TOTAUX:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalTTC, 3, '.', ',') }}</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalRS, 3, '.', ',') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>{{ $companySetting->address_line1 }}</td>
                <td>Tél : {{ $companySetting->phone1 }}</td>
                <td>GSM : {{ $companySetting->phone2 }} </td>
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
