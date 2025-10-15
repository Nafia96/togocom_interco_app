<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture - {{ $invoice_number ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px; line-height:1.4 }
        .header { text-align:center; margin-bottom:8px; }
        .logo { max-height:60px; }
        h2 { margin:0; font-size:16px }
        h3 { margin:2px 0 6px 0; font-size:14px }
        p.center { text-align:center; margin:2px 0 6px 0 }
        table { width:100%; border-collapse: collapse; margin-top:12px }
        th, td { border:1px solid #333; padding:6px; text-align:center }
        th { background:#f0f0f0; font-weight:bold }
        .text-right { text-align:right }
        .totaux td { font-weight:bold }
        .footer { margin-top:20px; font-size:10px; text-align:center; line-height:1.4 }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($logo_url))
            <img src="{{ $logo_url }}" class="logo" alt="logo">
        @endif
        <h2>FACTURATION TRAFIC VALIDE D'INTERCONNEXION TOGO CELLULAIRE</h2>
        <h3>{{ $direction ?? '' }} &nbsp; - &nbsp; Facture: {{ $invoice_number ?? '' }}</h3>
        <p class="center">Facture générée le {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mois</th>
                <th>Volume valorisé (min)</th>
                <th>Prix unitaire</th>
                <th>Montant HT</th>
                <th>Montant TTC (18%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Helper to convert YYYY-MM to French month name
                $months = [
                    '01' => 'Janvier','02' => 'Février','03' => 'Mars','04' => 'Avril','05' => 'Mai','06' => 'Juin',
                    '07' => 'Juillet','08' => 'Août','09' => 'Septembre','10' => 'Octobre','11' => 'Novembre','12' => 'Décembre'
                ];
            @endphp
            @foreach($lines as $line)
                @php
                    // normalize period formats: accept YYYY-MM or Y-m
                    $periodLabel = $line['period'];
                    if (preg_match('/^(\d{4})-(\d{2})$/', $line['period'], $m)) {
                        $y = $m[1]; $mm = $m[2];
                        $periodLabel = ($months[$mm] ?? $mm) . ' ' . $y;
                    } elseif (preg_match('/^(\d{4})-(\d{1,2})$/', $line['period'], $m)) {
                        $y = $m[1]; $mm = str_pad($m[2],2,'0',STR_PAD_LEFT);
                        $periodLabel = ($months[$mm] ?? $mm) . ' ' . $y;
                    }
                @endphp
                <tr>
                    <td>{{ $periodLabel }}</td>
                    <td class="text-right">{{ number_format($line['volume'],2,',',' ') }}</td>
                    <td class="text-right">{{ number_format($line['unit_price'],4,',',' ') }}</td>
                    <td class="text-right">{{ number_format($line['valorisation'],2,',',' ') }}</td>
                    <td class="text-right">{{ number_format($line['ttc'],2,',',' ') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totaux">
                <td class="text-right" colspan="3">TOTAL</td>
                <td class="text-right">{{ number_format($totals['valorisation'],2,',',' ') }}</td>
                <td class="text-right">{{ number_format($totals['ttc'],2,',',' ') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>YAS TOGO S.A, Capital social : 17 000 000 000 F CFA ; RCCM : TG-LOM 2018 B 353</p>
        <p>TOGO TELECOM S.A, Capital social : 4 000 000 000 F CFA, RCCM : TG-LOM 2001 B 0169</p>
        <p>TOGO CELLULAIRE S.A, Capital social : 1 500 000 000 F CFA, RCCM : TG-LOM 1999 B 0279</p>
        <p>Place de la Réconciliation, Quartier Atchanté – B.P. 333 Lomé-TOGO</p>
        <p>Téléphone : +228 22 53 44 01 – www.yas.tg</p>
    </div>
</body>
</html>
