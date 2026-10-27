<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparaison mesures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --comparison-title-row-height: 44px;
        }

        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }

        .card {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100;
            font-weight: 700;
            padding: 0.9rem 1.2rem;
            letter-spacing: 0.3px;
        }

        .kpi-bad  { background:#f8d7da; color:#842029; font-weight:bold; }
        .kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

        .table-responsive {
            max-height: 75vh;
            overflow: auto;
        }

        table.billing-comparison-table {
            width: 100%;
            min-width: 900px;
            table-layout: fixed;
            margin-bottom: 0;
        }

        table.billing-comparison-table thead th {
            position: sticky;
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100;
            z-index: 6;
            border-bottom: 2px solid #004aad;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
            vertical-align: middle;
            font-weight: 700;
        }

        table.billing-comparison-table thead tr:first-child th {
            top: 0;
            height: var(--comparison-title-row-height);
        }

        table.billing-comparison-table thead tr.filter-row th {
            top: var(--comparison-title-row-height);
            z-index: 7;
            text-transform: none;
            letter-spacing: 0;
        }

        table.billing-comparison-table th:first-child,
        table.billing-comparison-table td:first-child {
            position: sticky;
            left: 0;
            min-width: 180px;
            z-index: 4;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
        }

        table.billing-comparison-table thead tr:first-child th:first-child {
            z-index: 10;
        }

        table.billing-comparison-table thead tr.filter-row th:first-child {
            z-index: 11;
        }

        table.billing-comparison-table tbody td:first-child {
            background: #fff;
            font-weight: 600;
        }

        .comparison-filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.35rem;
        }

        .comparison-filter-field {
            flex: 1 1 190px;
            max-width: 260px;
        }

        .comparison-filter-actions {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }

        @media (max-width: 576px) {
            .comparison-filter-field {
                flex-basis: 100%;
                max-width: none;
            }

            .comparison-filter-actions {
                width: 100%;
                margin-left: 0;
            }

            .comparison-filter-actions .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('billingp') }}" class="btn btn-sm btn-light text-primary">Retour billing</a>
                    <span>Comparaison mesures provisoires / réelles</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('billing.comparison.export', request()->only(['start_month', 'end_month', 'operator'])) }}" class="btn btn-sm btn-outline-light">Exporter CSV</a>
                </div>
            </div>
            <div class="card-body">
                <form id="billing-comparison-filters" method="GET" action="{{ route('billing.comparison') }}" class="comparison-filter-bar mb-3">
                    <div class="comparison-filter-field">
                        <label for="start_month" class="form-label fw-semibold">Mois de début</label>
                        <input id="start_month" type="month" name="start_month" value="{{ $startMonth ?? now()->format('Y-m') }}" class="form-control form-control-sm">
                    </div>
                    <div class="comparison-filter-field">
                        <label for="end_month" class="form-label fw-semibold">Mois de fin</label>
                        <input id="end_month" type="month" name="end_month" value="{{ $endMonth ?? now()->format('Y-m') }}" class="form-control form-control-sm">
                    </div>
                    <div class="comparison-filter-actions">
                        <button type="submit" class="btn btn-sm btn-success">Appliquer</button>
                        <a href="{{ route('billing.comparison') }}" class="btn btn-sm btn-outline-primary">Réinitialiser</a>
                    </div>
                </form>

                @if (!empty($operatorWarnings))
                    <details class="alert alert-warning mb-3">
                        <summary class="fw-semibold">Opérateurs provisoires non associés ou ambigus ({{ count(array_unique($operatorWarnings)) }})</summary>
                        <ul class="mt-2 mb-0 ps-3">
                            @foreach (array_unique($operatorWarnings) as $warning)
                                <li>{{ $warning }}</li>
                            @endforeach
                        </ul>
                    </details>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle billing-comparison-table">
                        <colgroup>
                            <col style="width: 22%;">
                            <col style="width: 12%;">
                            <col style="width: 17%;">
                            <col style="width: 17%;">
                            <col style="width: 16%;">
                            <col style="width: 16%;">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Opérateur</th>
                                <th>Mois</th>
                                <th class="text-end">Provisoire</th>
                                <th class="text-end">Réel</th>
                                <th class="text-end">Écart</th>
                                <th class="text-end">Écart %</th>
                            </tr>
                            <tr class="filter-row">
                                <th>
                                    <select form="billing-comparison-filters" name="operator" class="form-select form-select-sm" aria-label="Filtrer par opérateur">
                                        <option value="">Tous les opérateurs</option>
                                        @foreach ($operatorsList as $operator)
                                            <option value="{{ $operator }}" {{ ($selectedOperator ?? '') === $operator ? 'selected' : '' }}>{{ $operator }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th class="text-center">-</th>
                                <th colspan="4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($comparison) === 0)
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Aucune donnée disponible pour cette période.</td>
                                </tr>
                            @else
                                @foreach ($comparison as $row)
                                    @php
                                        $deviationClass = $row['deviation_level'] === 'bad'
                                            ? 'kpi-bad'
                                            : ($row['deviation_level'] === 'warn' ? 'kpi-warn' : '');
                                    @endphp
                                    <tr>
                                        <td>{{ $row['operator'] }}</td>
                                        <td>{{ $row['month'] }}</td>
                                        <td class="text-end">{{ number_format($row['provisional'], 0, ',', ' ') }}</td>
                                        <td class="text-end">{{ number_format($row['real'], 0, ',', ' ') }}</td>
                                        <td class="text-end {{ $deviationClass }}">
                                            {{ number_format($row['ecart'], 0, ',', ' ') }}
                                        </td>
                                        <td class="text-end {{ $deviationClass }}">
                                            {{ $row['ecart_pct_label'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
