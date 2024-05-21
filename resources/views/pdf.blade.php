<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Note d'Honoraires</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice {
            margin: 20px;
        }

        .invoice-container {
            max-width: 21cm;
            /* A4 page width */
            margin: 0 auto;
            /* Center the container horizontally on the page */
            padding: 1cm;
            /* Add padding for spacing within the A4 page */
            position: relative;
            /* Ensure the container is positioned relatively */
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
        }

        .invoice-details {
            font-size: 12px;
            margin-top: 10px;
        }

        .invoice-logo {
            max-width: 100px;
            float: right;
            /* Make the logo float to the right */
            margin-left: 200px;
            /* Add some margin to separate it from text */
        }

        .separator {
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }

        .client-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
        }

        .invoice-table {
            width: 50%;
            /* Set the width to 50% of the container */
            margin: 20px auto 0;
            /* Center the table horizontally */
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-total {
            margin-top: 20px;
            font-size: 18px;
        }

        .footer-table {
            display: none;
            width: 100%;
            table-layout: fixed;
            position: fixed;
            bottom: 0;
        }

        .footer-table td {
            padding: 2px;
        }

        /* Define a container to hold the table and limit its width */
        .invoice-container {
            max-width: 21cm;
            /* A4 page width */
            margin: 0 auto;
            /* Center the table horizontally */
            padding: 1cm;
            /* Add padding for spacing within the A4 page */
            position: relative;
            /* Ensure the container is positioned relatively */

        }

        .fixed-table {
            position: fixed;
            /* Set the table to a fixed position */
            top: 0;
            /* Adjust the top position as needed */
            right: 0;
            /* Place the table on the right side of the page */
            /* You can also specify a width, max-width, and other styles as needed */
            /* Example: width: 300px; */
        }

        /* Define a table-like structure using CSS flexbox */
        .table {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Style for the text container */
        .table-cell.text {
            /* Allow the text container to expand and fill available space */
            width: 60cm;
            white-space: nowrap;
            /* Prevent text from wrapping to the next line */
        }

        /* Style for the logo container */
        .table-cell.logo {
            width: 4cm;
            /* Set a fixed width for the logo container */
            text-align: right;
            /* Align the logo to the right */
            margin-left: 1cm;
            /* Add margin to create space between text and logo */
        }

        client-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
        }

        .client-info-box {
            display: inline-block;
            /* Use inline-block to allow the box to take the width of its content */
            border: 1px solid #ddd;
            /* Add a border for styling */
            padding: 10px;
            /* Add padding for space inside the box */
            width: 50%;
            /* Set the width to 50% of its container */
            float: right;
            /* Float the box to the right */
        }
    </style>
</head>

<body>
    <div class="invoice">

        <div class="invoice-container ">
            <div class="invoice-header">
                <div>
                    <table class="fixed-table ">
                        <tr>
                            <td>
                                <div class="table-cell text">
                                    <div class="invoice-details">
                                        <p class="invoice-title">Cabinet Ezzeddine Haouel</p>
                                        <p class="centered-text" style="font-size: 15px;">Comptable Commissaire aux
                                            comptes</p>
                                        <p class="centered-text" style="font-size: 15px;">Member de la compagnie des
                                            comptables de Tunisie</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="table-cell logo">
                                    {{-- <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="invoice-logo" width="4cm" height="4cm"> --}}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: right;">
                    <p style="font-size: 12px; text-decoration: underline; margin-top: 0;">
                        <!-- Adjust margin-top -->
                        M.F.: 729831 E-A-P-000
                    </p>
                </div>

            </div>
        </div>


        <div class="separator"></div>

        <div class="invoice-details">
            <div>
                <p style="font-size: 17px;">
                    <strong style="text-decoration: underline; font-size: 15px;">Note d'honoraire:</strong>
                    {{-- Nº{{ honoraire . note }} --}}
                </p>
            </div>
            <div style="text-align: right;">
                <p>
                    <strong>Hammamet le:</strong>
                    {{-- {{ honoraire.createdAt ? honoraire.createdAt|date('d/m/Y') : '' }} --}}
                </p>
            </div>
        </div>

        <div class="client-info">
            <div class="client-info-box">
                <p>
                    {{-- <strong>Client:</strong>
						{{ honoraire.client.name }}
					</p>
					<p>
						<strong>Adresse:</strong>
						{{ honoraire.client.adresse }}
					</p>
					<p>
						<strong>M.F:</strong>
						{{ honoraire.client.mf }} --}}
                </p>
            </div>
        </div>

        <div class="separator"></div>

        <p>
            <strong style="text-decoration: underline;">Objet d'Honoraire:</strong>
            {{-- {{ honoraire.objet }} --}}
        </p>

        <table class="invoice-table">
            <tr>
                <th>Montant H.T</th>
                {{-- <td style="text-align: right;">{{ honoraire.montantht|number_format(3, '.', ',') }}</td> --}}
            </tr>
            <tr>
                <th>T.V.A
                    {{-- {{ tva }}%</th>
					<td style="text-align: right;">{{ honoraire.tva|number_format(3, '.', ',') }}</td> --}}
            </tr>
            <tr>
                <th>Montant T.T.C</th>
                {{-- <td style="text-align: right;">{{ honoraire.montantttc|number_format(3, '.', ',') }}</td> --}}
            </tr>
            <tr>
                <th>R/S
                    {{-- {{ rs }}%</th>
					<td style="text-align: right;">{{ honoraire.rs|number_format(3, '.', ',') }}</td> --}}
            </tr>
            <tr>
                <th>Timbre Fiscal</th>
                {{-- <td style="text-align: right;">{{ honoraire.timbrefiscal|number_format(3, '.', ',') }}</td> --}}
            </tr>
            <tr>
                <th>
                    <strong>Total à payer:</strong>
                </th>
                {{-- <td style="text-align: right;">{{ honoraire.netapayer|number_format(3, '.', ',') }}</td> --}}
            </tr>
        </table>


        <div class="invoice-details">
            <div>
                <p>
                    <strong>Arrêtée la présente note d'honoraire à la somme de:</strong>
                    {{-- {{ frenchWords }}
						dinars et
						{{ (honoraire.netapayer * 1000)|number_format(0, '.', ',')|slice(-3)|replace({' ': '0'}) }}
						millimes. --}}
                </p>
            </div>
            <div style="text-align: right;">
                <p>
                    <strong>Cachet et signature</strong>
                </p>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <div class="separator"></div>

            <div class="invoice-details"><!-- Your existing content goes here -->
            </div>
            <!-- Invisible table placed at the bottom -->
            <table class="footer-table">
                <!-- First line -->
                <tr>
                    <td>Av. Mohamed Ali Hammi</td>
                    <td>Tél: 72 26 38 83</td>
                    <td>GSM: 97 43 69 22 / 26 43 69 22</td>
                </tr>
                <!-- Second line -->
                <tr>
                    <td>8050 Hammamet</td>
                    <td>Fax: 72 26 38 79</td>
                    <td>Email: ezzeddine.haouel@yahoo.fr</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
