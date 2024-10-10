<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Retenue à la Source</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Rapport de Retenue à la Source</h1>

    <h2>Informations du Client</h2>
    <p><strong>Nom:</strong> {{ $client->name }}</p>
    <p><strong>Adresse:</strong> {{ $client->address }}</p>
    <p><strong>Matricule Fiscale:</strong> {{ $client->mf }}</p>

    <h2>Période du Rapport</h2>
    <p>Du {{ $startDate }} au {{ $endDate }}</p>

    <h2>Détails des Retenues</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant TTC</th>
                <th>Retenue à la Source</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($honoraires as $honoraire)
                <tr>
                    <td>{{ $honoraire->date }}</td>
                    <td>{{ number_format($honoraire->montantTTC, 3, '.', ',') }} TND</td>
                    <td>{{ number_format($honoraire->rs, 3, '.', ',') }} TND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Total de la Retenue à la Source</h2>
    <p><strong>{{ number_format($totalRS, 3, '.', ',') }} TND</strong></p>
</body>

</html>
