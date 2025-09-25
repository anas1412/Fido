<!DOCTYPE html>
<html>
<head>
    <title>Rapport des Notes de Débit</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .header, .footer {
            width: 100%;
            position: fixed;
            font-size: 10px;
            text-align: center;
        }
        .header {
            top: 0;
        }
        .footer {
            bottom: 0;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="company-info">
        <h3>{{ $companySetting->company_name }}</h3>
        <p>{{ $companySetting->slogan }}</p>
        <p>MF: {{ $companySetting->mf_number }}</p>
        <p>{{ $companySetting->address_line1 }}, {{ $companySetting->address_line2 }}</p>
        <p>Tel: {{ $companySetting->phone1 }} / {{ $companySetting->phone2 }} - Fax: {{ $companySetting->fax }}</p>
        <p>Email: {{ $companySetting->email }}</p>
    </div>

    <div class="report-title">
        Rapport des Notes de Débit du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>

    <table>
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
            @foreach($noteDeDebits as $noteDeDebit)
                <tr>
                    <td>{{ str_pad($noteDeDebit->note, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($noteDeDebit->date)->format('d/m/Y') }}</td>
                    <td>{{ $noteDeDebit->client->name }}</td>
                    <td>{{ $noteDeDebit->client->mf }}</td>
                    <td>{{ number_format($noteDeDebit->amount, 3, '.', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>{{ number_format($totalAmount, 3, '.', ' ') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Généré le {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>