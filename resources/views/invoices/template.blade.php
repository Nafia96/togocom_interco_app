<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #eee; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>FACTURATION TRAFIC VALIDE D'INTERCONNEXION TOGO CELLULAIRE</h2>
    <h3>{{ $invoice->direction ?? '' }}</h3>
    <p>Période : {{ $invoice->period ?? $invoice->periodDate ?? '' }}</p>

    <table>
        <thead>
            <tr>
                <th>Période</th>
                <th>Volume (min)</th>
                <th>PU</th>
                <th>Montant HT</th>
                <th>Montant TTC</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details as $d)
            <tr>
                <td>{{ $d['period'] ?? $d['periode'] ?? '' }}</td>
                <td>{{ number_format($d['traffic_validated'] ?? $d['volume'] ?? 0, 2, ',', ' ') }}</td>
                <td>{{ $d['unit_price'] ?? '' }}</td>
                <td>{{ number_format($d['valorisation'] ?? 0, 2, ',', ' ') }}</td>
                <td>{{ number_format(($d['valorisation'] ?? 0) * 1.18, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="3">TOTAL</th>
                <th>{{ number_format($invoice->total_valorisation ?? 0, 2, ',', ' ') }}</th>
                <th>{{ number_format($invoice->total_ttc ?? ($invoice->total_valorisation * 1.18 ?? 0), 2, ',', ' ') }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>
