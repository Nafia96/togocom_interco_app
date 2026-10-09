<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coût Carrier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --kpi-header-row-height: 48px; }
        body { background: #f4f6f9; font-family: "Segoe UI", Roboto, Arial, sans-serif; font-size: .85rem; }
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0, 0, 0, .06); }
        .card-header {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100; font-weight: 700; font-size: 1.05rem; padding: .75rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 3px solid #004aad; text-transform: uppercase; letter-spacing: .5px;
        }
        .kpi-breadcrumb { --bs-breadcrumb-divider: '›'; display: flex; flex-wrap: wrap; align-items: center; gap: .45rem .2rem; margin-bottom: 1rem; }
        .kpi-breadcrumb .breadcrumb-item { display: flex; align-items: center; max-width: 100%; }
        .kpi-breadcrumb .period-item, .kpi-breadcrumb .filter-item, .kpi-breadcrumb .empty-filter-item { padding: .35rem .6rem; border-radius: .3rem; line-height: 1.25; }
        .kpi-breadcrumb .period-item { background: #133272; color: #ffd100; }
        .kpi-breadcrumb .filter-item { background: #f8f9fa; border: 1px solid #133272; color: #133272; }
        .kpi-breadcrumb .empty-filter-item { color: #6c757d; background: #f8f9fa; }
        .kpi-breadcrumb .filter-remove { margin-left: .35rem; color: #133272; font-size: 1rem; line-height: 1; text-decoration: none; }
        .kpi-breadcrumb .breadcrumb-row-count { margin-left: auto; }
        table.cost-carrier-table { width: 100%; min-width: 850px; }
        table.table thead th {
            position: sticky; top: 0; background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100; z-index: 6; border-bottom: 2px solid #004aad; text-transform: uppercase;
            font-size: .85rem; vertical-align: middle; font-weight: 700; text-align: center;
        }
        table.table thead tr:first-child th { top: 0; z-index: 10; height: var(--kpi-header-row-height); box-sizing: border-box; }
        table.table thead tr.filter-row th {
            top: var(--kpi-header-row-height); z-index: 9; background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%);
            height: 52px; box-sizing: border-box; text-align: center;
        }
        table.cost-carrier-table .filter-row .form-select { width: 100%; min-width: 0; }
        table.cost-carrier-table tbody td { text-align: center; }
        table.cost-carrier-table tbody td:first-child, table.cost-carrier-table thead th:first-child {
            position: sticky; left: 0; min-width: 130px; z-index: 11; box-shadow: 2px 0 6px rgba(0, 0, 0, .1);
        }
        table.cost-carrier-table tbody td:first-child { background: #fff; font-weight: 700; z-index: 7; }
        table.cost-carrier-table thead tr.filter-row th:first-child { z-index: 12; background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%); }
        .table-hover tbody tr:hover { background: rgba(19, 50, 114, .03); }
        @media (max-width: 768px) { .kpi-breadcrumb .breadcrumb-row-count { margin-left: 0; flex-basis: 100%; } }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: .25rem .5rem; display: flex; align-items: center;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                    <span>Launchpad</span>
                </a>
                <span>COÛT CARRIER</span>
            </div>
            @include('partials.cout_nav')
        </div>
        <div class="card-body">
            @php
                $periodLabel = \Carbon\Carbon::parse($start)->format('d/m/Y') . ' → ' . \Carbon\Carbon::parse($end)->format('d/m/Y');
                $activeFilters = [
                    ['param' => 'filter_dest_net', 'label' => 'Dest net', 'value' => $filterDestNet],
                    ['param' => 'filter_carrier', 'label' => 'Carrier', 'value' => $filterCarrier],
                    ['param' => 'filter_rate', 'label' => 'Rate', 'value' => $filterRate],
                ];
            @endphp
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb kpi-breadcrumb">
                    <li class="breadcrumb-item period-item"><span>Période : <strong>{{ $periodLabel }}</strong></span></li>
                    @php($hasActiveFilter = false)
                    @foreach ($activeFilters as $activeFilter)
                        @if ($activeFilter['value'] !== '')
                            @php($hasActiveFilter = true)
                            <li class="breadcrumb-item filter-item">
                                <span>{{ $activeFilter['label'] }} : <strong>{{ $activeFilter['value'] }}</strong></span>
                                <a href="{{ route('kpi.cout-carrier', \Illuminate\Support\Arr::except(request()->query(), [$activeFilter['param']])) }}" class="filter-remove" title="Retirer ce filtre" aria-label="Retirer le filtre {{ $activeFilter['label'] }}">&times;</a>
                            </li>
                        @endif
                    @endforeach
                    @unless ($hasActiveFilter)
                        <li class="breadcrumb-item empty-filter-item">Aucun filtre</li>
                    @endunless
                    <li class="breadcrumb-item breadcrumb-row-count"><span class="badge text-bg-secondary">{{ $rowCount }} ligne(s)</span></li>
                </ol>
            </nav>

            <form method="GET" action="{{ route('kpi.cout-carrier') }}" id="cost-carrier-form">
                <div class="row g-2 align-items-end mb-3">
                    <div class="col-lg-2 col-md-4">
                        <label for="start_date" class="form-label">Date début</label>
                        <input id="start_date" type="date" name="start_date" class="form-control form-control-sm" value="{{ $start }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="end_date" class="form-label">Date fin</label>
                        <input id="end_date" type="date" name="end_date" class="form-control form-control-sm" value="{{ $end }}">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="filter_dest_net" class="form-label">Dest net</label>
                        <select id="filter_dest_net" name="filter_dest_net" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($filterOptions['dest_net'] as $value)
                                <option value="{{ $value }}" {{ $filterDestNet == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label for="filter_carrier" class="form-label">Carrier</label>
                        <select id="filter_carrier" name="filter_carrier" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($filterOptions['carrier'] as $value)
                                <option value="{{ $value }}" {{ $filterCarrier == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-4">
                        <label for="filter_rate" class="form-label">Rate</label>
                        <select id="filter_rate" name="filter_rate" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach ($filterOptions['rate'] as $value)
                                <option value="{{ $value }}" {{ $filterRate == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-8 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                        <a href="{{ route('kpi.cout-carrier') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                        <button type="button" id="export-cost-carrier" class="btn btn-sm btn-success">Exporter CSV</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle mb-0 cost-carrier-table">
                        <thead>
                            <tr>
                                <th>Type de coût</th>
                                <th>Période</th>
                                <th>Dest net</th>
                                <th>Carrier</th>
                                <th>Rate</th>
                                <th>Minutes</th>
                            </tr>
                            <tr class="filter-row">
                                <th><span class="text-muted">-</span></th>
                                <th><span class="text-muted">-</span></th>
                                <th>
                                    <select class="form-select form-select-sm" aria-label="Filtrer par dest net" onchange="document.getElementById('filter_dest_net').value = this.value; this.form.submit()">
                                        <option value="">Tous</option>
                                        @foreach ($filterOptions['dest_net'] as $value)
                                            <option value="{{ $value }}" {{ $filterDestNet == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-select form-select-sm" aria-label="Filtrer par carrier" onchange="document.getElementById('filter_carrier').value = this.value; this.form.submit()">
                                        <option value="">Tous</option>
                                        @foreach ($filterOptions['carrier'] as $value)
                                            <option value="{{ $value }}" {{ $filterCarrier == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select class="form-select form-select-sm" aria-label="Filtrer par rate" onchange="document.getElementById('filter_rate').value = this.value; this.form.submit()">
                                        <option value="">Tous</option>
                                        @foreach ($filterOptions['rate'] as $value)
                                            <option value="{{ $value }}" {{ $filterRate == $value ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th><span class="text-muted">-</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($results->isEmpty())
                                <tr><td colspan="6" class="text-center text-muted">Aucune donnée pour cette période / ces filtres.</td></tr>
                            @else
                                @foreach ($results as $row)
                                    <tr>
                                        <td>{{ $row->direction }}</td>
                                        <td>{{ $row->period }}</td>
                                        <td>{{ $row->dest_net_name }}</td>
                                        <td>{{ $row->carrier_name }}</td>
                                        <td>{{ $row->rate }}</td>
                                        <td class="text-end">{{ number_format((float) $row->minutes, 2, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('export-cost-carrier').addEventListener('click', function () {
        const table = document.querySelector('.cost-carrier-table');
        const rows = [table.tHead.rows[0], ...table.tBodies[0].rows];
        const csv = rows.map(row => Array.from(row.cells)
            .map(cell => '"' + cell.textContent.trim().replace(/"/g, '""') + '"')
            .join(';'))
            .join('\r\n');
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'cout-carrier.csv';
        link.click();
        URL.revokeObjectURL(link.href);
    });
</script>
</body>
</html>
