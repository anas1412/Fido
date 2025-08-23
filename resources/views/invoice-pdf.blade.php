<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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

        .header > div:first-child {
            margin-right: 150px;
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
        }

        .client-box p {
            margin: 0 0 5px 0;
        }

        .client-box p:last-child {
            margin-bottom: 0;
        }

        .invoice-purpose {
            margin-bottom: 20px;
        }

        .invoice-purpose p {
            font-size: 1.2em;
        }

        .invoice-table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .total-in-words {
            margin-bottom: 80px;
            position: relative;
            font-size: 15px;
        }

        .signature-text {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
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

    <div class="invoice-details">
        <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
        <p><strong>Invoice Date:</strong> {{ $formattedDate }}</p>
    </div>

    <div class="client-info">
        <p>{{ $companySetting->location }} le : {{ $formattedDate }}</p>
        <div class="client-box">
            <p>Client: <strong>{{ $invoice->client->name }}</strong></p>
            <p>Address: {{ $invoice->client->address }}</p>
            <p>M.F.: {{ $invoice->client->mf }}</p>
        </div>
    </div>

    <div class="invoice-table-container">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceItems as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->single_price, 3, '.', ',') }}</td>
                        <td>{{ number_format($item->total_price, 3, '.', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total HT:</strong></td>
                    <td>{{ number_format($invoice->total_ht, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total TVA:</strong></td>
                    <td>{{ number_format($invoice->total_tva, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total TTC:</strong></td>
                    <td>{{ number_format($invoice->total_ttc, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total RS:</strong></td>
                    <td>{{ number_format($invoice->total_rs, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Timbre Fiscal:</strong></td>
                    <td>{{ number_format($invoice->total_tf, 3, '.', ',') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Net à Payer:</strong></td>
                    <td>{{ number_format($invoice->net_to_pay, 3, '.', ',') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

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
