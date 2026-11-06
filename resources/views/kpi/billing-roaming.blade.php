<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Billing Splited</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/multi-filter.css') }}?v={{ filemtime(public_path('css/multi-filter.css')) }}" rel="stylesheet">
<style>
    :root { --kpi-header-row-height: 48px; }
    body { background:#f4f6f9; font-family:"Segoe UI", Roboto, Arial, sans-serif; font-size:.85rem; }
    th { text-align:center; }
    .card { border-radius:15px; overflow:hidden; box-shadow:0 3px 10px rgba(0,0,0,.06); }
    .card-header {
        background:linear-gradient(90deg, #133272 0%, #1e4a98 100%); color:#ffd100; font-weight:700;
        font-size:1.05rem; padding:.75rem 1.25rem; display:flex; justify-content:space-between;
        align-items:center; border-bottom:3px solid #004aad; text-transform:uppercase; letter-spacing:.5px;
        box-shadow:inset 0 -2px 6px rgba(0,0,0,.15); border-radius:.5rem .5rem 0 0;
    }
    .kpi-bad { background:#f8d7da; color:#842029; font-weight:bold; }
    .kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }
    table.table thead th {
        position:sticky; top:0; background:linear-gradient(90deg, #133272 0%, #1e4a98 100%);
        color:#ffd100; z-index:6; border-bottom:2px solid #004aad; text-transform:uppercase;
        font-size:.85rem; letter-spacing:.02em; vertical-align:middle; font-weight:700;
    }
    table.kpi-billing-roaming-table { width:100%; min-width:1250px; table-layout:auto; }
    table.kpi-billing-roaming-table th[data-col="period"], table.kpi-billing-roaming-table td[data-col="period"] {
        width:160px; max-width:200px; white-space:normal; word-break:normal;
    }
    table.kpi-billing-roaming-table th[data-col="orig_type"], table.kpi-billing-roaming-table td[data-col="orig_type"],
    table.kpi-billing-roaming-table th[data-col="dest_type"], table.kpi-billing-roaming-table td[data-col="dest_type"],
    table.kpi-billing-roaming-table th[data-col="financial_type"], table.kpi-billing-roaming-table td[data-col="financial_type"],
    table.kpi-billing-roaming-table th[data-col="nb_calls"], table.kpi-billing-roaming-table td[data-col="nb_calls"],
    table.kpi-billing-roaming-table th[data-col="minutes"], table.kpi-billing-roaming-table td[data-col="minutes"],
    table.kpi-billing-roaming-table th[data-col="amount_XOF"], table.kpi-billing-roaming-table td[data-col="amount_XOF"] { white-space:nowrap; }
    table.kpi-billing-roaming-table td[data-col="orig_net_name"],
    table.kpi-billing-roaming-table td[data-col="dest_net_name"],
    table.kpi-billing-roaming-table td[data-col="orig_country_name"],
    table.kpi-billing-roaming-table td[data-col="dest_country_name"],
    table.kpi-billing-roaming-table td[data-col="carrier_name"] { overflow:hidden; text-overflow:ellipsis; }
    table.kpi-billing-roaming-table .filter-row .form-select { width:100%; min-width:0!important; }
    .kpi-filter-bar .kpi-filter-narrow .form-select { min-width:0!important; }
    .kpi-breadcrumb {
        --bs-breadcrumb-divider:'›'; display:flex; flex-wrap:wrap; align-items:center;
        gap:.45rem .2rem; margin-bottom:1rem;
    }
    .kpi-breadcrumb .breadcrumb-item { display:flex; align-items:center; max-width:100%; }
    .kpi-breadcrumb .breadcrumb-item::before { color:#6c757d; }
    .kpi-breadcrumb .period-item, .kpi-breadcrumb .filter-item, .kpi-breadcrumb .empty-filter-item {
        padding:.35rem .6rem; border-radius:.3rem; line-height:1.25;
    }
    .kpi-breadcrumb .period-item { background:#133272; color:#ffd100; }
    .kpi-breadcrumb .filter-item { background:#f8f9fa; border:1px solid #133272; color:#133272; }
    .kpi-breadcrumb .empty-filter-item { color:#6c757d; background:#f8f9fa; }
    .kpi-breadcrumb .filter-remove { margin-left:.35rem; color:#133272; font-size:1rem; line-height:1; text-decoration:none; }
    .kpi-breadcrumb .filter-remove:hover { color:#842029; }
    .kpi-breadcrumb .breadcrumb-row-count { margin-left:auto; }
    .kpi-breadcrumb .breadcrumb-filter-muted { opacity:.7; }
    .kpi-breadcrumb .hidden-column-note { margin-left:.3rem; font-size:.72rem; color:#6c757d; }
    @media (max-width:768px) { .kpi-breadcrumb .breadcrumb-row-count { margin-left:0!important; flex-basis:100%; } }
    table.table thead tr:first-child th {
        top:0; z-index:10; height:var(--kpi-header-row-height); box-sizing:border-box;
    }
    table.table thead tr.filter-row th {
        top:var(--kpi-header-row-height); z-index:9; background:linear-gradient(90deg, #173d82 0%, #1d4d9c 100%);
        box-shadow:inset 0 -1px 0 rgba(255,255,255,.1); height:52px; box-sizing:border-box; text-align:center;
    }
    tbody td:first-child {
        position:sticky; left:0; min-width:120px; background:#fff; z-index:7; font-weight:700;
        box-shadow:2px 0 6px rgba(0,0,0,.04);
    }
    table.kpi-billing-roaming-table tbody td { text-align:center; }
    thead th:first-child {
        position:sticky; left:0; min-width:120px; background:linear-gradient(90deg, #133272 0%, #1e4a98 100%);
        z-index:11; box-shadow:2px 0 6px rgba(0,0,0,.12);
    }
    tr.filter-row th:first-child { min-width:120px; background:linear-gradient(90deg, #173d82 0%, #1d4d9c 100%); z-index:12; }
    table.kpi-billing-roaming-table .filter-description {
        display:block; color:rgba(255,255,255,.9); font-size:.7rem; font-weight:400;
        line-height:1.15; text-align:center; white-space:normal;
    }
    .table-hover tbody tr:hover { background:rgba(19,50,114,.03); }
        .kpi-sort-link { display:inline-flex; align-items:center; gap:.3rem; color:inherit; text-decoration:none; white-space:nowrap; cursor:pointer; }
        .kpi-sort-link:hover { color:#ffd100; }
        .kpi-sort-indicator { opacity:.42; font-size:.75em; }
        .kpi-sort-indicator.active { opacity:1; }
    .kpi-pagination { display:flex; justify-content:center; width:100%; overflow-x:auto; }
    .kpi-pagination nav { max-width:100%; }
    .kpi-pagination .pagination { flex-wrap:wrap; justify-content:center; gap:.2rem; margin-bottom:0; }
    .kpi-pagination .page-item { flex:0 0 auto; }
    .kpi-pagination .page-link { min-width:2.1rem; padding:.3rem .55rem; text-align:center; font-size:.82rem; line-height:1.25; }
</style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding:.25rem .5rem; display:flex; align-items:center;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height:24px; width:auto; object-fit:contain; margin-right:6px;">
                    <span>Launchpad</span>
                </a>
                <span>BILLING SPLITED</span>
            </div>
            @include('partials.kpi_nav')
        </div>

        <div class="card-body">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb kpi-breadcrumb">
                    @foreach ($breadcrumb as $breadcrumbItem)
                        @if ($breadcrumbItem['type'] === 'period')
                            <li class="breadcrumb-item period-item">
                                <span>{{ $breadcrumbItem['label'] }} : <strong>{{ $breadcrumbItem['value'] }}</strong></span>
                            </li>
                        @elseif ($breadcrumbItem['type'] === 'filter')
                            <li class="breadcrumb-item filter-item {{ !empty($breadcrumbItem['hidden']) ? 'breadcrumb-filter-muted' : '' }}" data-breadcrumb-col="{{ $breadcrumbItem['column'] }}">
                                <span>{{ $breadcrumbItem['label'] }} : <strong title="{{ $breadcrumbItem['title'] ?? $breadcrumbItem['value'] }}">{{ $breadcrumbItem['value'] }}</strong></span>
                                @if (!empty($breadcrumbItem['hidden']))
                                    <small class="hidden-column-note">(colonne masquée)</small>
                                @endif
                                <a href="{{ $breadcrumbItem['removeUrl'] }}" class="filter-remove" title="Retirer ce filtre" aria-label="Retirer le filtre {{ $breadcrumbItem['label'] }}">&times;</a>
                            </li>
                        @else
                            <li class="breadcrumb-item empty-filter-item">{{ $breadcrumbItem['value'] }}</li>
                        @endif
                    @endforeach
                    <li class="breadcrumb-item breadcrumb-row-count">
                        <span class="badge text-bg-secondary">{{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }} sur {{ $rowCount }} ligne(s)</span>
                    </li>
                </ol>
            </nav>

            @php
                $visibleColumns = array_merge($visibleDims, $visibleMetrics);
                $visibleColumnCount = count($visibleColumns);
                $filterConfigs = [];
                foreach ($filters as $key => $config) {
                    $filterConfigs[$key] = [
                        'column' => $config['column'],
                        'name' => 'filter_' . $key,
                        'label' => $allCols[$key],
                        'selected' => $config['value'],
                    ];
                }
                $measureDescriptions = [
                    'nb_calls' => 'Nombre d\'appels',
                    'minutes' => 'Volume en minutes',
                    'amount_XOF' => 'Montant (XOF)',
                ];
            @endphp

            <form id="kpi-filter-form" method="GET" action="{{ route('kpi.billing-roaming') }}" class="mb-3">
                <div class="row g-2 align-items-end mb-3 kpi-filter-bar">
                    <div class="col-lg-2 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="start_date" class="form-label">Date début</label>
                        <input id="start_date" type="date" name="start_date" class="form-control form-control-sm" value="{{ $start }}">
                    </div>
                    <div class="col-lg-2 col-md-3 kpi-filter-field">
                        <label for="end_date" class="form-label">Date fin</label>
                        <input id="end_date" type="date" name="end_date" class="form-control form-control-sm" value="{{ $end }}">
                    </div>
                    <div class="col-lg-2 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="month" class="form-label">Mois</label>
                        <select id="month" name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">— Choisir un mois —</option>
                            @foreach ($monthOptions as $monthOption)
                                <option value="{{ $monthOption['value'] }}" {{ $selectedMonth === $monthOption['value'] ? 'selected' : '' }}>{{ $monthOption['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="granularity" class="form-label">Périodicité</label>
                        <select id="granularity" name="granularity" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="none" {{ $granularity === 'none' ? 'selected' : '' }}>Aucune</option>
                            <option value="day" {{ $granularity === 'day' ? 'selected' : '' }}>Jour</option>
                            <option value="week" {{ $granularity === 'week' ? 'selected' : '' }}>Semaine</option>
                            <option value="month" {{ $granularity === 'month' ? 'selected' : '' }}>Mois</option>
                            <option value="year" {{ $granularity === 'year' ? 'selected' : '' }}>Année</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="per_page" class="form-label">Lignes par page</label>
                        <select id="per_page" name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach ([50, 100, 200, 500] as $pageSize)
                                <option value="{{ $pageSize }}" {{ $results->perPage() === $pageSize ? 'selected' : '' }}>{{ $pageSize }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-auto d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                        <a href="{{ route('kpi.billing-roaming') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                    </div>
                    <div class="col-12 col-lg-auto ms-lg-auto d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a id="btn-export" href="{{ route('kpi.billing-roaming.export', request()->query()) }}" class="btn btn-sm btn-success">Exporter CSV</a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Colonnes</button>
                            <div class="dropdown-menu dropdown-menu-end p-2" id="column-menu">
                                @foreach ($allCols as $columnKey => $columnLabel)
                                    @php
                                        $isLockedPeriod = $columnKey === 'period' && $granularity !== 'none';
                                    @endphp
                                    <label class="dropdown-item d-flex align-items-center gap-2 {{ $isLockedPeriod ? 'text-muted' : '' }}">
                                        <input type="checkbox" class="form-check-input column-toggle" name="cols[]" value="{{ $columnKey }}"
                                               {{ in_array($columnKey, $visibleColumns, true) ? 'checked' : '' }}
                                               {{ $isLockedPeriod ? 'checked disabled title="Désactivez la granularité pour retirer cette colonne"' : '' }}>
                                        <span>{{ $columnLabel }}</span>
                                    </label>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none w-100 text-start" id="show-all-columns">Tout afficher</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($rowCount > 5000)
                    <div class="alert alert-warning py-2" role="alert">
                        Résultat volumineux ({{ number_format($rowCount, 0, ',', ' ') }} lignes) : affinez avec les filtres, la période ou les colonnes, ou utilisez l’export CSV.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table kpi-billing-roaming-table table-bordered table-hover table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                @foreach ($allCols as $columnKey => $columnLabel)
                                    @if (in_array($columnKey, $visibleColumns, true))
                                        @php
                                            $numericSort = in_array($columnKey, ['rate_XOF', 'nb_calls', 'minutes', 'amount_XOF'], true);
                                            $activeSort = $sort === $columnKey;
                                            $nextDir = $activeSort
                                                ? ($numericSort ? ($dir === 'desc' ? 'asc' : null) : ($dir === 'asc' ? 'desc' : null))
                                                : ($numericSort ? 'desc' : 'asc');
                                            if ($nextDir === null) {
                                                $sortUrl = request()->fullUrlWithQuery(['sort' => null, 'dir' => null, 'page' => null]);
                                                $sortTitle = 'Retirer le tri';
                                            } else {
                                                $sortUrl = request()->fullUrlWithQuery(['sort' => $columnKey, 'dir' => $nextDir, 'page' => null]);
                                                $sortTitle = $columnKey === 'period'
                                                    ? ($nextDir === 'asc' ? 'Du plus ancien au plus récent' : 'Du plus récent au plus ancien')
                                                    : ($numericSort
                                                        ? ($nextDir === 'desc' ? 'Trier du plus grand au plus petit' : 'Trier du plus petit au plus grand')
                                                        : ($nextDir === 'asc' ? 'Trier de A à Z' : 'Trier de Z à A'));
                                            }
                                        @endphp
                                        <th data-col="{{ $columnKey }}" aria-sort="{{ $activeSort ? ($dir === 'asc' ? 'ascending' : 'descending') : 'none' }}" class="{{ $columnKey === 'period' ? 'period-column period-column-' . $granularity : '' }}">
                                            <a class="kpi-sort-link" href="{{ $sortUrl }}" title="{{ $sortTitle }}">
                                                <span>{{ $columnLabel }}</span>
                                                <span class="kpi-sort-indicator {{ $activeSort ? 'active' : '' }}" aria-hidden="true">{{ $activeSort ? ($dir === 'asc' ? '▲' : '▼') : '↕' }}</span>
                                            </a>
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                            <tr class="filter-row">
                                @foreach ($allCols as $columnKey => $columnLabel)
                                    @if (in_array($columnKey, $visibleColumns, true))
                                        @if (isset($filterConfigs[$columnKey]))
                                            @php
                                                $config = $filterConfigs[$columnKey];
                                            @endphp
                                            <th data-col="{{ $columnKey }}">
                                                @include('partials.multi_filter', [
                                                    'name' => $config['name'],
                                                    'label' => $config['label'],
                                                    'options' => $filterOptions[$columnKey] ?? [],
                                                    'selected' => $config['selected'],
                                                    'formId' => 'kpi-filter-form',
                                                    'compact' => true,
                                                ])
                                            </th>
                                        @else
                                            <th data-col="{{ $columnKey }}">
                                                @if (isset($measureDescriptions[$columnKey]))
                                                    <small class="filter-description">{{ $measureDescriptions[$columnKey] }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </th>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if ($results->count() === 0)
                                <tr><td colspan="{{ $visibleColumnCount }}" class="text-center text-muted">Aucune donnée pour cette période / ces filtres.</td></tr>
                            @else
                                @foreach ($results as $row)
                                    <tr>
                                        @foreach ($allCols as $columnKey => $columnLabel)
                                            @if (in_array($columnKey, $visibleColumns, true))
                                                @php
                                                    $cellValue = $row->{$columnKey} ?? null;
                                                    $cellClass = in_array($columnKey, $visibleDims, true) ? '' : 'text-end';
                                                    if ($columnKey === 'period' && !empty($cellValue)) {
                                                        switch ($granularity) {
                                                            case 'day':
                                                                $cellText = \Carbon\Carbon::parse($cellValue)->locale('fr')->translatedFormat('d/m/Y');
                                                                break;
                                                            case 'week':
                                                                $weekStart = \Carbon\Carbon::parse($cellValue)->locale('fr');
                                                                $cellText = 'Semaine du ' . $weekStart->translatedFormat('d/m/Y') . ' (' . $weekStart->copy()->addDays(6)->translatedFormat('d/m/Y') . ')';
                                                                break;
                                                            case 'month':
                                                                $cellText = mb_convert_case(\Carbon\Carbon::parse($cellValue)->locale('fr')->translatedFormat('F Y'), MB_CASE_TITLE, 'UTF-8');
                                                                break;
                                                            case 'year':
                                                                $cellText = \Carbon\Carbon::parse($cellValue)->locale('fr')->translatedFormat('Y');
                                                                break;
                                                            default:
                                                                $cellText = $cellValue;
                                                        }
                                                    } elseif (in_array($columnKey, $visibleMetrics, true)) {
                                                        $cellText = number_format((float) ($cellValue ?? 0), 2, ',', ' ');
                                                    } else {
                                                        $cellText = $cellValue ?? '-';
                                                    }
                                                @endphp
                                                <td data-col="{{ $columnKey }}" class="{{ $cellClass }} {{ $columnKey === 'period' ? 'period-column period-column-' . $granularity : '' }}">{{ $cellText }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </form>
            <div class="kpi-pagination mt-3">
                {{ $results->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/multi-filter.js') }}?v={{ filemtime(public_path('js/multi-filter.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('table.kpi-billing-roaming-table');
    const titleRow = table ? table.querySelector('thead tr:first-child') : null;

    function syncStickyHeaderOffset() {
        if (titleRow) {
            table.style.setProperty('--kpi-header-row-height', titleRow.getBoundingClientRect().height + 'px');
        }
    }

    syncStickyHeaderOffset();
    window.addEventListener('resize', syncStickyHeaderOffset);

    const monthSelect = document.getElementById('month');
    ['start_date', 'end_date'].forEach(function (fieldName) {
        const dateInput = document.querySelector('input[name="' + fieldName + '"]');
        if (dateInput && monthSelect) {
            dateInput.addEventListener('change', function () { monthSelect.value = ''; });
        }
    });

    const toggles = Array.from(document.querySelectorAll('.column-toggle'));
    const storageKey = 'kpi-billing-roaming-visible-columns';
    const allowedColumns = new Set(toggles.map(toggle => toggle.value));
    const currentUrl = new URL(window.location.href);
    const hasColsParameter = Array.from(currentUrl.searchParams.keys()).some(key => key === 'cols' || /^cols\[/.test(key));
    let savedColumns = [];
    try {
        const stored = JSON.parse(localStorage.getItem(storageKey) || '[]');
        savedColumns = Array.isArray(stored) ? stored.filter(column => allowedColumns.has(column)) : [];
    } catch (error) {
        savedColumns = [];
    }

    if (!hasColsParameter && savedColumns.length > 0) {
        currentUrl.searchParams.forEach(function (value, key) {
            if (key === 'cols' || /^cols\[/.test(key)) currentUrl.searchParams.delete(key);
        });
        savedColumns.forEach(column => currentUrl.searchParams.append('cols[]', column));
        window.location.replace(currentUrl.toString());
        return;
    }

    function saveVisibleColumns() {
        const visible = toggles.filter(toggle => toggle.checked).map(toggle => toggle.value);
        localStorage.setItem(storageKey, JSON.stringify(visible));
    }

    toggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const enabledCheckedCount = toggles.filter(item => !item.disabled && item.checked).length;
            if (!this.checked && enabledCheckedCount === 0) {
                this.checked = true;
                return;
            }
            saveVisibleColumns();
            this.form.submit();
        });
    });

    document.getElementById('show-all-columns').addEventListener('click', function () {
        toggles.forEach(toggle => { toggle.checked = true; });
        saveVisibleColumns();
        this.closest('form').submit();
    });

    saveVisibleColumns();
});
</script>
@include('partials.date_sync')
</body>
</html>
