<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesures provisoires - Facturation trafic voix internationale wholesale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }

        .table-heading-country {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%) !important;
            color: #ffd100 !important;
            font-weight: bold !important;
            text-transform: uppercase;
            text-align: center;
            font-size: 1.1em !important;
            letter-spacing: 0.5px !important;
            border: 2px solid rgba(19,50,114,0.9) !important;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004aad;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -2px 6px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .btn-nav {
            background: linear-gradient(180deg, #0056d2 0%, #004aad 100%);
            color: #fff !important;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 14px;
            margin-right: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.8rem;
        }

        th,
        td {
            white-space: nowrap;
            padding: 4px 8px !important;
            vertical-align: middle !important;
        }

        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #e9ecef;
            z-index: 10;
            font-size: 0.8rem;
        }

        tbody td:first-child,
        thead th:first-child,
        tfoot td:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 11;
            font-weight: 600;
        }

        tfoot {
            position: sticky;
            bottom: 0;
            z-index: 9;
        }

        tfoot tr {
            background: #198754 !important;
            color: white !important;
            font-weight: bold;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e3fcec !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: 0.25rem 0.5rem; display: flex; align-items: center;">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                        <span>Launchpad</span>
                    </a>
                    <i class="fas fa-table me-2"></i>
                    <span class="pivot-header-title">Mesures provisoires - Facturation trafic voix internationale wholesale</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('billingp') }}" class="btn btn-sm btn-light text-primary">Pivot</a>
                </div>
            </div>

            <nav aria-label="breadcrumb" class="px-3 pt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <div class="d-flex align-items-center">
                            <strong class="me-2">Mois :</strong>
                            <span class="breadcrumb-value">{{ $monthLabel ?? '-' }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="card-body">
                <form method="GET" action="{{ route('billingProvisionalMeasures') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="month" class="form-label fw-semibold">Mois :</label>
                        <input type="month" id="month" name="month" class="form-control" value="{{ request('month', $month ?? '') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>

                <div id="tableValeurs" class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="table-heading-country">Opérateur</th>
                                <th class="text-center">Volume sortant</th>
                                <th class="text-center">Charge</th>
                                <th class="text-center">Volume entrant</th>
                                <th class="text-center">Revenu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operators as $operator => $values)
                                <tr>
                                    <td>{{ $operator }}</td>
                                    <td class="text-end">{{ $values['volume_sortant'] > 0 ? number_format($values['volume_sortant'], 0, ',', ' ') : '-' }}</td>
                                    <td class="text-end">{{ $values['charge'] > 0 ? number_format($values['charge'], 0, ',', ' ') : '-' }}</td>
                                    <td class="text-end">{{ $values['volume_entrant'] > 0 ? number_format($values['volume_entrant'], 0, ',', ' ') : '-' }}</td>
                                    <td class="text-end">{{ $values['revenu'] > 0 ? number_format($values['revenu'], 0, ',', ' ') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td class="text-end">{{ $totals['volume_sortant'] > 0 ? number_format($totals['volume_sortant'], 0, ',', ' ') : '-' }}</td>
                                <td class="text-end">{{ $totals['charge'] > 0 ? number_format($totals['charge'], 0, ',', ' ') : '-' }}</td>
                                <td class="text-end">{{ $totals['volume_entrant'] > 0 ? number_format($totals['volume_entrant'], 0, ',', ' ') : '-' }}</td>
                                <td class="text-end">{{ $totals['revenu'] > 0 ? number_format($totals['revenu'], 0, ',', ' ') : '-' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>
</body>
</html>
