<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture N°{{ $invoice->invoice_number }}</title>
    <style>
        @page { size: A4; margin: 30px 80px; }
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
        }
        .header {
            width: 100%;
            margin-bottom: 10px;
        }
        .left {
            float: left;
            text-align: left;
        }
        .right {
            float: right;
            text-align: right;
        }
        .clear { clear: both; }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 25px 0 15px;
            text-decoration: underline;
        }
        table {
    width: 95%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 75px; /* Optional: Add some space above the table */
    border: 1px solid black;
    border-collapse: collapse;
    font-size: 14px;
}
        th, td {
            border: 1px solid black;
            padding: 6px;
        }
        th {
            text-align: center;
            font-weight: normal;
        }
        td {
            text-align: center;
        }
        .item-row td {
            height: 25px;
            vertical-align: top;
        }
        tr.item-row + tr.item-row td {
            border-top-style: hidden;
        }
        .totals-row td {
            border-top: none;
            border-bottom: none;
            font-size: 14px;
        }
        .totals-label {
            text-align: left;
            padding-left: 10px;
            white-space: nowrap;
        }
        .tight-spacing td {
            padding-top: 0;
            padding-bottom: 0;
            line-height: 1;
        }
        .normal-line-height td {
            line-height: inherit;
        }
        .final-row-padding td {
            padding-bottom: 6px;
        }
        .right-align {
            text-align: right !important;
            padding-right: 10px;
        }
        .left-align {
            text-align: left !important;
            padding-left: 10px;
        }
        .underline {
            text-decoration: underline;
        }
        .dash-top {
            border-top: 1px dashed black;
        }
        .bottom-text {
            margin-top: 15px;
            font-size: 14px;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="left">
            <u>MAHER MESSAI</u><br>
            <u>FRIPPERIE EN GROS</u><br>
            <u>Cité ENNAIM – BIR BOU REGBBA</u><br><br>
            <u>HAMMAMET</u><br><br>
            <u>T.V.A.</u> : 0613465PAC000
        </div>
        <div class="right">
            <u>HAMMAMET LE {{ $formattedDate }}</u>
        </div>
        <div class="clear"></div>
    </div>

    <div class="title">
        FACTURE N°{{ $invoice->invoice_number }}
    </div>

    <p>
        <u>Doit</u> : {{ $invoice->client_name }}<br>
        M.F. : {{ $invoice->client_mf }}
    </p>

    <table>
        <tr>
            <th style="width: 10%;"><u>Qtés</u></th>
            <th style="width: 50%;"><u>D E S I G N A T I O N S</u></th>
            <th style="width: 20%;"><u>P.U. H.T.</u></th>
            <th style="width: 20%;"><u>MONTANTS</u></th>
        </tr>

        @foreach ($invoiceItems as $item)
        <tr class="item-row">
            <td>{{ $item->quantity }}</td>
            <td class="left-align">{{ $item->object }}</td>
            <td>{{ number_format($item->single_price, 3, '.', '') }}</td>
            <td>{{ number_format($item->total_price, 3, '.', '') }}</td>
        </tr>
        @endforeach

         <tr class="item-row">
            <td>&nbsp;</td>
            <td class="left-align">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="item-row">
            <td>&nbsp;</td>
            <td class="left-align">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr class="totals-row tight-spacing normal-line-height">
            <td></td>
            <td colspan="2" class="totals-label">
                - Total Hors Taxes ..................................................................
            </td>
            <td class="right-align dash-top">{{ number_format($invoice->total_hors_taxe, 3, '.', '') }}</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - T.V.A. : 19% .........................................................................
            </td>
            <td class="right-align">{{ number_format($invoice->tva, 3, '.', '') }}</td>
        </tr>
        <tr class="totals-row  tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label"></td>
            <td class="right-align">-------------------------</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - Montant Toutes Taxes Comprises .......................................
            </td>
            <td class="right-align">{{ number_format($invoice->montant_ttc, 3, '.', '') }}</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - Timbre fiscal .........................................................................
            </td>
            <td class="right-align">{{ number_format($invoice->timbre_fiscal, 3, '.', '') }}</td>
        </tr>
        <tr class="totals-row  tight-spacing" >
            <td></td>
            <td colspan="2" class="totals-label"></td>
            <td class="right-align">-------------------------</td>
        </tr>
        
        <tr class="totals-row tight-spacing final-row-padding">
            <td></td>
            <td colspan="2" class="totals-label">
                <u>- Net à votre aimable règlement </u>.............................................
            </td>
            <td class="right-align"><strong>{{ number_format($invoice->net_a_payer, 3, '.', '') }}</strong></td>
        </tr>
    </table>

    <p class="bottom-text">
        Arrêtée la présente facture à la somme de : {{ $netToPayInWords ?? '' }}
    </p>

    <div class="signature">
        <u>SIGNATURE</u>
    </div>
</body>
</html>