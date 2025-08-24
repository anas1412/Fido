<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        @page { size: A4; margin: 30px 40px; }
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
            width: 100%;
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
        }
        td {
            text-align: center;
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
        /* New class to reduce vertical padding only on specific rows */
        .tight-spacing td {
            padding-top: 0; /* Set to 0 for minimum space */
            padding-bottom: 0; /* Set to 0 for minimum space */
            line-height: 1; /* This is the key change */
        }
        .normal-line-height td {
        line-height: inherit; /* Inherits the line-height from the body (1.4) */
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
            <u>HAMMAMET LE {{ $date ?? '01/06/2024' }}</u>
        </div>
        <div class="clear"></div>
    </div>

    <div class="title">
        FACTURE N°{{ $invoice_number ?? '02/2024' }}
    </div>

    <p>
        <u>Doit</u> : {{ $client_name ?? 'INES MHADBI' }}<br>
        M.F. : {{ $client_mf ?? '1692 555M' }}
    </p>

    <table>
        <tr>
            <th style="width: 10%;"><u>Qtés</u></th>
            <th style="width: 50%;"><u>D E S I G N A T I O N S</u></th>
            <th style="width: 20%;"><u>P.U. H.T.</u></th>
            <th style="width: 20%;"><u>MONTANTS</u></th>
        </tr>
        <tr>
            <td>05</td>
            <td class="left-align">BALE DE FRIPPE</td>
            <td>140.000</td>
            <td>700.000</td>
        </tr>
        <tr class="totals-row tight-spacing normal-line-height">
            <td></td>
            <td colspan="2" class="totals-label">
                - Total Hors Taxes .............................................................................................
            </td>
            <td class="right-align dash-top">700.000</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - T.V.A. : 19% ....................................................................................................
            </td>
            <td class="right-align">133.000</td>
        </tr>
        <tr class="totals-row  tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label"></td>
            <td class="right-align">-------------------------</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - Montant Toutes Taxes Comprises ..................................................................
            </td>
            <td class="right-align">833.000</td>
        </tr>
        <tr class="totals-row tight-spacing">
            <td></td>
            <td colspan="2" class="totals-label">
                - Timbre fiscal ....................................................................................................
            </td>
            <td class="right-align">1.000</td>
        </tr>
        <tr class="totals-row  tight-spacing" >
            <td></td>
            <td colspan="2" class="totals-label"></td>
            <td class="right-align">-------------------------</td>
        </tr>
        
        <tr class="totals-row tight-spacing final-row-padding">
            <td></td>
            <td colspan="2" class="totals-label">
                <u>- Net à votre aimable règlement </u>.......................................................................
            </td>
            <td class="right-align"><strong>834.000</strong></td>
        </tr>
    </table>

    <p class="bottom-text">
        Arrêtée la présente facture à la somme de : Huit Cent Trente Quatre Dinars.
    </p>

    <div class="signature">
        <u>SIGNATURE</u>
    </div>
</body>
</html>