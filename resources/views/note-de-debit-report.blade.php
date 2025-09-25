<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Notes de Débit</title>
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


        .retenue-table { /* Renamed from retenue-table to report-table for generality */
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

        /* Adjust column widths for debit notes */
        .retenue-table th:nth-child(1),
        .retenue-table td:nth-child(1) {
            width: 20%; /* Réf */
        }

        .retenue-table th:nth-child(2),
        .retenue-table td:nth-child(2) {
            width: 20%; /* Date */
            text-align: center;
        }

        .retenue-table th:nth-child(3),
        .retenue-table td:nth-child(3) {
            width: 40%; /* Client */
            white-space: normal;
            word-wrap: break-word;
        }

        .retenue-table th:nth-child(4),
        .retenue-table td:nth-child(4) {
            width: 20%; /* M.F */
            text-align: center;
        }

        .retenue-table th:nth-child(5),
        .retenue-table td:nth-child(5) {
            width: 20%; /* Montant */
            text-align: right;
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
        <div class="logo-container">
            <div class="logo">
                <img src="{{ public_path('images/CCT.jpg') }}" alt="Logo">
            </div>
        </div>
        <br>
        <div class="mf-number">M.F. : {{ $companySetting->mf_number }}</div>

        <div class="header-line"></div>
    </div>

    <h2>Rapport des Notes de Débit du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h2>
    <table class="retenue-table">
        <thead>
            <tr>
                <th>Réf</th>
                <th>Date</th>
                <th>Client</th>
                <th>M.F</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($noteDeDebits as $noteDeDebit)
                <tr>
                    <td>{{ str_pad($noteDeDebit->note, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($noteDeDebit->date)->format('d/m/Y') }}</td>
                    <td>{{ $noteDeDebit->client->name }}</td>
                    <td>{{ $noteDeDebit->client->mf }}</td>
                    <td>{{ number_format($noteDeDebit->amount, 3, '.', ' ') }}</td>
                </tr>
            @endforeach
            <br><br>
            <tr>
                <td style="text-align: right;" colspan="4"><strong>TOTAL:</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($totalAmount, 3, '.', ' ') }}</strong></td>
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