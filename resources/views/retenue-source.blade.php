{{-- resources/views/certificates/tax-withholding.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Certificat de Retenue d'Impôt</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .header-left {
            position: absolute;
            top: 20px;
            left: 20px;
            font-weight: bold;
            line-height: 1.5;
        }

        .header-right {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
            font-weight: bold;
            line-height: 1.5;
        }

        .date-section {
            position: absolute;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-a {
            position: absolute;
            top: 130px;
            left: 20px;
            font-weight: bold;
            text-decoration: underline;
        }

        .id-grid-a {
            position: absolute;
            top: 150px;
            right: 20px;
            width: 50%;
            border-collapse: collapse;
        }

        .id-grid-a td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            height: 30px;
        }

        .info-box-a {
            position: absolute;
            top: 220px;
            left: 20px;
            width: 90%;
            line-height: 1.5;
        }

        .section-b {
            position: absolute;
            top: 260px;
            left: 20px;
            font-weight: bold;
            text-decoration: underline;
        }

        .grid-table {
            position: absolute;
            top: 280px;
            left: 20px;
            width: 60%;
            margin-left: 20%;
            border-collapse: collapse;
        }

        .grid-table td,
        .grid-table th {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            height: 30px;
        }

        .amount-cell {
            text-align: right;
            padding-right: 10px;
        }

        .section-c {
            position: absolute;
            top: 420px;
            left: 20px;
            font-weight: bold;
            text-decoration: underline;
        }

        .id-grid-c {
            position: absolute;
            top: 440px;
            right: 20px;
            width: 50%;
            border-collapse: collapse;
        }

        .id-grid-c td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            height: 30px;
        }

        .info-box-c {
            position: absolute;
            top: 500px;
            left: 20px;
            width: 90%;
            line-height: 1.5;
        }

        .signature-box {
            position: absolute;
            top: 580px;
            left: 20px;
            width: 90%;
            text-align: center;
            line-height: 1.5;
        }

        .signature-box p {
            margin: 8px 0;
        }

        .line-break {
            border-top: 1px solid black;
            margin: 8px 0;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            width: calc(100% - 40px);
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header-left">
        REPUBLIQUE TUNISIENNE<br>
        MINISTERE DES FINANCES<br>
        DIRECTION REGIONALE<br>
        DU CONTROLE FISCAL
    </div>

    <div class="header-right">
        CERTIFICAT DE RETENUE<br>
        D'IMPOT SUR LE REVENU<br>
        OU D'IMPOT SUR LES SOCIETES
    </div>

    <div class="date-section">
        Retenu effectuée le : {{ $date_retenue ?? '01/07/2024' }}
    </div>

    <div class="section-a">
        A-PERSONNE OU ORGANISME PAYEUR:
    </div>

    <table class="id-grid-a">
        <tr>
            <td width="25%"><strong>Matricule<br>Fiscal</strong></td>
            <td width="25%"><strong>Code<br>T.V.A</strong></td>
            <td width="25%"><strong>Code<br>catégorie</strong></td>
            <td width="25%"><strong>N° Et.<br>Secondaire</strong></td>
        </tr>
        <tr>
            <td>{{ $matricule_fiscal_payeur ?? '1199245Y' }}</td>
            <td>{{ $code_tva_payeur ?? 'A' }}</td>
            <td>{{ $code_categorie_payeur ?? 'M' }}</td>
            <td>{{ $no_et_secondaire_payeur ?? '000' }}</td>
        </tr>
    </table>

    <div class="info-box-a">
        <strong>Dénomination de la personne ou de l'organisme payeur:</strong>
        {{ $denomination_payeur ?? 'STE DISTRIPETS SARL' }}<br>
        <strong>Adresse:</strong> {{ $adresse_payeur ?? 'RUE SAAD IBN ABI WAKKAS MORNAG' }}
    </div>

    <div class="section-b">
        B-RETENUES EFFECTUEES SUR:
    </div>

    <table class="grid-table">
        <tr>
            <th width="60%">Description</th>
            <th width="15%">MONTANT BRUT</th>
            <th width="10%">RETENUE</th>
            <th width="15%">MONTANT NET</th>
        </tr>
        <tr>
            <td>{{ $description ?? '- Assistance Comptable de l\'année 2023.' }}</td>
            <td class="amount-cell">{{ number_format($montant_brut ?? 3571.0, 3) }}</td>
            <td class="amount-cell">{{ number_format($retenue ?? 107.1, 3) }}</td>
            <td class="amount-cell">{{ number_format($montant_net ?? 3463.9, 3) }}</td>
        </tr>
        <tr>
            <td><strong>Total Général</strong></td>
            <td class="amount-cell"><strong>{{ number_format($montant_brut ?? 3571.0, 3) }}</strong></td>
            <td class="amount-cell"><strong>{{ number_format($retenue ?? 107.1, 3) }}</strong></td>
            <td class="amount-cell"><strong>{{ number_format($montant_net ?? 3463.9, 3) }}</strong></td>
        </tr>
    </table>

    <div class="section-c">
        C-BENEFICIAIRE:
    </div>

    <table class="id-grid-c">
        <tr>
            <td width="25%"><strong>Matricule<br>Fiscal</strong></td>
            <td width="25%"><strong>Code<br>T.V.A</strong></td>
            <td width="25%"><strong>Code<br>catégorie</strong></td>
            <td width="25%"><strong>N° Et.<br>Secondaire</strong></td>
        </tr>
        <tr>
            <td>{{ $matricule_fiscal_beneficiaire ?? '729831E' }}</td>
            <td>{{ $code_tva_beneficiaire ?? 'A' }}</td>
            <td>{{ $code_categorie_beneficiaire ?? 'P' }}</td>
            <td>{{ $no_et_secondaire_beneficiaire ?? '000' }}</td>
        </tr>
    </table>

    <div class="info-box-c">
        <strong>Nom, Prénom, ou raison social:</strong> {{ $nom_beneficiaire ?? 'Cabinet Ezzeddine Haouel' }}<br>
        <strong>Adresse Professionnelle:</strong> {{ $adresse_beneficiaire ?? 'Av. Mohamed Ali Hammi 8050 Hammamet' }}
    </div>

    <div class="signature-box">
        <p>Je soussigné, certifie exacts les renseignements figurant sur le présent certificat</p>
        <p>et m'expose aux sanctions prévenues par la loi pour toute inexactitude</p>

        <div class="line-break"></div>

        <p>HAMMAMET, le {{ $date_signature ?? '31/08/2024' }}</p>

        <div class="line-break"></div>

        <p>Cachet et signature du payeur</p>
    </div>

    <div class="footer">
        Certificat de retenue d'impôt sur le revenu ou d'impôt sur les sociétés - Document à conserver pour la
        déclaration fiscale.
    </div>
</body>

</html>
