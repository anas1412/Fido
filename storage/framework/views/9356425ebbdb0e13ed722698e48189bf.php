<?php
    use App\Helpers\NumberToWords;

    $netapayer = $record->amount;
    $dinars = floor($netapayer);
    $millimes = round(($netapayer - $dinars) * 1000);
    $dinarsInWords = NumberToWords::convertToWords($dinars);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note de débit</title>
    <style>
        @page {
            size: A4;
            /* Set page size */
            margin-top: 10px;
            /* Set margins to zero */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            /* Increased from 12px to 14px */
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
            margin-right: 150px; /* Adjust this value as needed to prevent overlap */
        }

        .logo-container {
            position: absolute;
            top: 0;
            right: 0px;
            /* Moved 20px to the right */
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
            /* Adjust the width as needed */
            height: auto;
            /* Adjust the height automatically to maintain aspect ratio */
            border: 1px;
            border-radius: 50%;
            object-fit: cover;
            /* Ensures the image fits well within the container */
        }

        .mf-number {
            font-size: 12px;
            text-decoration: underline;
            /* Underline the text */
            text-align: right;
            /* Align the text to the right */
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

        .invoice-purpose {
            margin-bottom: 20px;
        }

        .invoice-purpose p {
            font-size: 1.2em;
            /* Increases the size of the text */
        }

        .invoice-table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Full viewport height */
            width: 100%;
            /* Full viewport width */
        }

        .invoice-table {
            width: 50%;
            border-collapse: collapse;
            margin: auto;
            margin-bottom: 10px;
            font-size: 18px;
            /* Center the table */
        }

        .invoice-table th,
        .invoice-table td {
            border: none;
            padding: 8px;
        }

        .invoice-table th:first-child,
        .invoice-table td:first-child {
            text-align: left;
            width: 40%;
        }

        .invoice-table th:nth-child(2),
        .invoice-table td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
            width: 40%;
        }

        .total-in-words {
            margin-bottom: 80px;
            position: relative;
            font-size: 15px;
            /* This allows positioning of the signature text */
        }

        .signature-text {
            text-align: right;
            /* Aligns the text to the right */
            margin-top: 20px;
            /* Adds space between the text above */
            font-weight: bold;
            /* Makes the text bold */
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
            <h1><?php echo e($companySetting->company_name); ?></h1>
            <p><?php echo e($companySetting->slogan); ?></p>
        </div>
        <div class="logo-container">
            <div class="logo">
                <img src="<?php echo e(public_path('images/CCT.jpg')); ?>" alt="Logo">
            </div>

        </div>
        <br>
        <div class="mf-number">M.F. : <?php echo e($companySetting->mf_number); ?></div>
        <div class="header-line"></div>
    </div>

    <div class="invoice-details">
        <p><strong><u>Note de débit</u> :</strong>
            N°<?php echo e(substr_replace(str_pad($record->note, 8, '0', STR_PAD_LEFT), '/', -4, 0)); ?>

        </p>
    </div>

    <div class="client-info">
        <p><?php echo e($companySetting->location); ?> le : <?php echo e($formattedDate); ?></p>
        <div class="client-box">
            <p>Client: <strong><?php echo e($record->client->name); ?></strong></p>
            <p>Adresse: <?php echo e($record->client->address); ?></p>
            <p>M.F.: <?php echo e($record->client->mf); ?></p>
        </div>
    </div>

    <div class="invoice-purpose">
        <p><strong><u>Objet de débit</u> :</strong> <?php echo e($record->description); ?>.</p>
    </div>

    <div class="invoice-table-container">
        <table class="invoice-table">
            <tr>
                <td>Montant</td>
                <td>:</td>
                <td><strong><?php echo e(number_format($record->amount, 3, '.', ',')); ?></strong></td>
            </tr>
        </table>
    </div>
    <br>
    <div class="total-in-words">
        <p>Arrêtée la présente note de débit à la somme de :
            <?php echo e($dinarsInWords); ?> dinars et <?php echo e($millimes); ?> millimes.
        </p>
        <p class="signature-text"><strong>Cachet et signature</strong></p>
    </div>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td><?php echo e($companySetting->address_line1); ?></td>
                <td>Tél : <?php echo e($companySetting->phone1); ?></td>
                <td>GSM : <?php echo e($companySetting->phone2); ?></td>
            </tr>
            <tr>
                <td><?php echo e($companySetting->address_line2); ?></td>
                <td>Fax : <?php echo e($companySetting->fax); ?></td>
                <td>Email : <?php echo e($companySetting->email); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>
<?php /**PATH D:\Devs\Fido\resources\views\note-de-debit.blade.php ENDPATH**/ ?>