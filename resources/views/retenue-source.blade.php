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
            line-height: 1.2; /* Adjusted line-height to reduce vertical space */
            font-size: 14px;
        }

        .header-right {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
            font-weight: bold;
            line-height: 1.2; /* Adjusted line-height to reduce vertical space */
            font-size: 14px;
        }

        .date-section {
            position: absolute;
            top: 110px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
        }

        .section-a {
            position: absolute;
            top: 150px;
            left: 20px;
            font-weight: bold;
            text-decoration: underline;
            font-size: 15px;
        }

        .id-grid-a {
            position: absolute;
            top: 160px;
            right: 20px;
            width: 50%;
            border-collapse: collapse;
            border: solid black;
            font-size: 15px;
        }

        .id-grid-a td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            height: 30px;
        }

        .info-box-a {
            position: absolute;
            top: 260px;
            left: 20px;
            width: 90%;
            line-height: 1.5;
            font-size: 14px;
        }

        .grid-table {
            position: absolute;
            top: 325px;
            left: 8px;
            right: 8px;
            width: 95%;
            border-collapse: collapse;
            border: solid black;
            margin-left: 10px;
            margin-right: 10px;
            font-size: 15px;
        }

        .grid-table th:first-child,
        .grid-table td:first-child {
            text-align: left;
             /* Aligns only the first table header to the left */
        }


        .grid-table tr:nth-child(2) td:nth-child(2) {
            height: 120px;
        }


        .grid-table td,
        .grid-table th {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }



        .amount-cell {
            text-align: right;
            padding-right: 10px;
        }

        .section-c {
            position: absolute;
            top: 550px;
            left: 20px;
            font-weight: bold;
            text-decoration: underline;
            font-size: 15px;
            
        }

        .id-c {
            position: absolute;
            top: 560px;
            right: 20px;
            font-size: 13px;
            
        }

        .id-grid-c {
            position: absolute;
            top: 580px;
            right: 20px;
            width: 50%;
            border-collapse: collapse;
            font-size: 15px;
            font-weight: bold;
            border: solid black;
        }

        .id-grid-c td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            height: 30px;
        }

        .info-box-c {
            position: absolute;
            top: 690px;
            left: 20px;
            width: 90%;
            line-height: 1.5;
            font-size: 14px;
        }

        .signature-box {
            position: absolute;
            top: 750px;
            left: 0px;
            right: 1px;
            width: 100%;
            text-align: center;
            line-height: 0.8;
            border: 1px solid black;
            height: 220px;
            font-size: 14px;
            padding-top: 10px;
        }

        .signature-box p {
            margin: 12px 0;
        }


        .footer {
            position: absolute;
            bottom: 80px;
            left: 20px;
            width: calc(100% - 40px);
            font-size: 10px;
            text-align: left;
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
        Retenu effectuée le : {{ $currentDate ?? 'NaN' }}
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
            <td><strong>{{ substr($client->mf ?? '1199245Y', 0, 8) }}</strong></td>
            <td><strong>{{ substr($client->mf ?? 'A', 8, 1) }}</strong></td>
            <td><strong>{{ substr($client->mf ?? 'A', 9, 1) }}</strong></td>
            <td><strong>{{ substr($client->mf ?? 'A', 10, 3) }}</strong></td>
        </tr>
    </table>

    <br><br>

    <div class="info-box-a">
        <strong>Dénomination de la personne ou de l'organisme payeur:
        {{ $client->name ?? 'STE DISTRIPETS SARL' }}</strong><br>
        <strong>Adresse :</strong> {{ $client->address ?? 'RUE SAAD IBN ABI WAKKAS MORNAG' }}
    </div>

    <table class="grid-table">
        <tr>
            <th width="50%">B-RETENUES EFFECTUEES SUR: </th>
            <th width="21%">MONTANT BRUT</th>
            <th width="14%">RETENUE</th>
            <th width="21%">MONTANT NET</th>
        </tr>
        <tr>
    <td>
        @foreach($honoraires as $honoraire)
            {{ $honoraire->object ?? '- Assistance Comptable de l\'année NaN.' }}<br>
        @endforeach
    </td>
    <td class="amount-cell">
        @foreach($honoraires as $honoraire)
            {{ number_format($honoraire->montantHT ?? 00) }}<br>
        @endforeach
    </td>
    <td class="amount-cell">
        @foreach($honoraires as $honoraire)
            {{ number_format($honoraire->rs ?? 00) }}<br>
        @endforeach
    </td>
    <td class="amount-cell">
        @foreach($honoraires as $honoraire)
            {{ number_format($honoraire->netapayer ?? 00) }}<br>
        @endforeach
    </td>
</tr>
        <tr>
            <td><strong>Total Général</strong></td>
            <td class="amount-cell"><strong>{{ number_format($totalTTC ?? 00) }}</strong></td>
            <td class="amount-cell"><strong>{{ number_format($TotalRS ?? 00) }}</strong></td>
            <td class="amount-cell"><strong>{{ number_format($totalNET ?? 00) }}</strong></td>
        </tr>
    </table>

    <div class="section-c">
        C-BENEFICIAIRE:
    </div>

    <div class="id-c">
        IDENTIFIANT
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
        <strong>Nom, Prénom, ou raison social: {{ $nom_beneficiaire ?? 'Cabinet Ezzeddine Haouel' }}<br></strong>
        <strong>Adresse Professionnelle : {{ $adresse_beneficiaire ?? 'Av. Mohamed Ali Hammi 8050 Hammamet' }}</strong> 
    </div>


    <div class="signature-box">
        <p>Je soussigné, certifie exacts les renseignements figurant sur le présent certificat</p>
        <p>et m'expose aux sanctions prévenues par la loi pour toute inexactitude</p>

        <br>

        <p>HAMMAMET, le {{ $currentDate ?? '31/08/2024' }}</p>


        <p><strong>Cachet et signature du payeur</strong></p>
    </div>


    <div class="footer">
        1/ le certificat, est délivé à l'occasion de chaque paiement toutesfois pour les opération répétitives le certificat peut être délivré trimestriellement.
        <br>
        2/ code catégorie: M.personnes physique- industrie et commerce- P. Professions librérales - N. employeurs non soumis à l'impôt ou sur les sociétés (administrations et
        établissement publies). -E. établissement secondaires.
    </div>
</body>

</html>
