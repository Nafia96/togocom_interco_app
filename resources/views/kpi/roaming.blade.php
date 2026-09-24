<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>KPI Roming</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    :root {
        --kpi-header-row-height: 48px;
    }

body {
    background:#f4f6f9;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    font-size:0.85rem;
}

th { text-align:center; white-space:nowrap; }
td { white-space:nowrap; }

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

.kpi-bad  { background:#f8d7da; color:#842029; font-weight:bold; }
.kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

.kpi-toggle { padding: .35rem .65rem; font-size: .85rem; border-radius: .35rem; transition: all 0.2s ease; }
.kpi-toggle-active { background: #133272; color: #ffd100; border-color: #133272; font-weight: 600; }
.kpi-toggle-inactive { background: #fff; color: #133272; border: 1px solid rgba(19, 50, 114, .35); }

table.table thead th {
    position: sticky;
    top: 0;
    background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
    color: #ffd100;
    z-index: 6;
    border-bottom: 2px solid #004aad;
    text-transform: uppercase;
    font-size: .85rem;
    letter-spacing: .02em;
    vertical-align: middle;
    font-weight: 700;
}

table.kpi-roaming-table {
    width: 100%;
    min-width: 1100px;
    table-layout: fixed;
}

table.kpi-roaming-table th[data-col="orig_type"],
table.kpi-roaming-table td[data-col="orig_type"],
table.kpi-roaming-table th[data-col="dest_type"],
table.kpi-roaming-table td[data-col="dest_type"] {
    width: 11%;
}

table.kpi-roaming-table th[data-col="orig_net_name"],
table.kpi-roaming-table td[data-col="orig_net_name"],
table.kpi-roaming-table th[data-col="dest_net_name"],
table.kpi-roaming-table td[data-col="dest_net_name"] {
    width: 14%;
}

table.kpi-roaming-table th[data-col="partner_name"],
table.kpi-roaming-table td[data-col="partner_name"] {
    width: 16%;
}

table.kpi-roaming-table th[data-col="attempt"],
table.kpi-roaming-table td[data-col="attempt"],
table.kpi-roaming-table th[data-col="ner"],
table.kpi-roaming-table td[data-col="ner"],
table.kpi-roaming-table th[data-col="asr"],
table.kpi-roaming-table td[data-col="asr"] {
    width: 8%;
}

table.kpi-roaming-table th[data-col="acd_sec"],
table.kpi-roaming-table td[data-col="acd_sec"] {
    width: 10%;
}

table.kpi-roaming-table td[data-col="orig_type"],
table.kpi-roaming-table td[data-col="dest_type"],
table.kpi-roaming-table td[data-col="orig_net_name"],
table.kpi-roaming-table td[data-col="dest_net_name"],
table.kpi-roaming-table td[data-col="partner_name"] {
    overflow: hidden;
    text-overflow: ellipsis;
}

table.kpi-roaming-table .filter-row .form-select {
    width: 100%;
    min-width: 0 !important;
}

.kpi-filter-bar .kpi-filter-narrow .form-select {
    min-width: 0 !important;
}

.kpi-breadcrumb {
    --bs-breadcrumb-divider: '›';
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .45rem .2rem;
    margin-bottom: 1rem;
}

.kpi-breadcrumb .breadcrumb-item {
    display: flex;
    align-items: center;
    max-width: 100%;
}

.kpi-breadcrumb .breadcrumb-item::before {
    color: #6c757d;
}

.kpi-breadcrumb .period-item,
.kpi-breadcrumb .filter-item,
.kpi-breadcrumb .empty-filter-item {
    padding: .35rem .6rem;
    border-radius: .3rem;
    line-height: 1.25;
}

.kpi-breadcrumb .period-item {
    background: #133272;
    color: #ffd100;
}

.kpi-breadcrumb .filter-item {
    background: #f8f9fa;
    border: 1px solid #133272;
    color: #133272;
}

.kpi-breadcrumb .empty-filter-item {
    color: #6c757d;
    background: #f8f9fa;
}

.kpi-breadcrumb .filter-remove {
    margin-left: .35rem;
    color: #133272;
    font-size: 1rem;
    line-height: 1;
    text-decoration: none;
}

.kpi-breadcrumb .filter-remove:hover {
    color: #842029;
}

.kpi-breadcrumb .breadcrumb-row-count {
    margin-left: auto;
}

.kpi-breadcrumb .breadcrumb-filter-muted {
    opacity: .7;
}

.kpi-breadcrumb .hidden-column-note {
    margin-left: .3rem;
    font-size: .72rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .kpi-breadcrumb .breadcrumb-row-count {
        margin-left: 0 !important;
        flex-basis: 100%;
    }
}

table.table thead tr:first-child th {
    top: 0;
    z-index: 10;
    height: var(--kpi-header-row-height);
    box-sizing: border-box;
}

table.table thead tr.filter-row th {
    top: var(--kpi-header-row-height);
    z-index: 9;
    background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%);
    box-shadow: inset 0 -1px 0 rgba(255,255,255,0.1);
    height: 52px;
    box-sizing: border-box;
}

tbody td:first-child {
    position: sticky; left: 0; background: #fff; z-index: 7; font-weight: 700; box-shadow: 2px 0 6px rgba(0,0,0,.04);
}

thead th:first-child {
    position: sticky;
    left: 0;
    background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
    z-index: 11;
    box-shadow: 2px 0 6px rgba(0,0,0,.12);
}

tr.filter-row th:first-child {
    background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%);
    z-index: 12;
}

.table-hover tbody tr:hover { background: rgba(19, 50, 114, 0.03); }
</style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: 0.25rem 0.5rem; display: flex; align-items: center;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                    <span>Launchpad</span>
                </a>
                <span>
                    KPI ROMING – {{ strtoupper($direction ?? 'ALL') }} – {{ strtoupper($view ?? 'DAY') }}
                </span>
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
                            <li class="breadcrumb-item filter-item" data-breadcrumb-col="{{ $breadcrumbItem['column'] }}">
                                <span>{{ $breadcrumbItem['label'] }} : <strong>{{ $breadcrumbItem['value'] }}</strong></span>
                                <a href="{{ $breadcrumbItem['removeUrl'] }}" class="filter-remove" title="Retirer ce filtre" aria-label="Retirer le filtre {{ $breadcrumbItem['label'] }}">&times;</a>
                            </li>
                        @else
                            <li class="breadcrumb-item empty-filter-item">{{ $breadcrumbItem['value'] }}</li>
                        @endif
                    @endforeach
                    <li class="breadcrumb-item breadcrumb-row-count">
                        <span class="badge text-bg-secondary">{{ $rowCount }} ligne(s)</span>
                    </li>
                </ol>
            </nav>

            @php
                $tableColumns = [
                    'orig_type' => 'Orig type',
                    'dest_type' => 'Dest type',
                    'orig_net_name' => 'Orig net',
                    'dest_net_name' => 'Dest net',
                    'partner_name' => 'Partner',
                    'attempt' => 'Attempt',
                    'ner' => 'NER',
                    'asr' => 'ASR',
                    'acd_sec' => 'ACD sec',
                ];

                $filterConfigs = [
                    'orig_type' => ['column' => 'orig_type', 'name' => 'filter_orig_type', 'selected' => $filterOrigType ?? ''],
                    'dest_type' => ['column' => 'dest_type', 'name' => 'filter_dest_type', 'selected' => $filterDestType ?? ''],
                    'orig_net' => ['column' => 'orig_net_name', 'name' => 'filter_orig_net', 'selected' => $filterOrigNet ?? ''],
                    'dest_net' => ['column' => 'dest_net_name', 'name' => 'filter_dest_net', 'selected' => $filterDestNet ?? ''],
                    'partner' => ['column' => 'partner_name', 'name' => 'filter_partner', 'selected' => $filterPartner ?? ''],
                ];
            @endphp

            <form method="GET" action="{{ route('kpi.roaming') }}" class="mb-3">
                <div class="row g-2 align-items-end mb-3 kpi-filter-bar">
                    <div class="col-lg-1 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="filter_direction" class="form-label">Direction</label>
                        <select id="filter_direction" name="filter_direction" class="form-select form-select-sm">
                            <option value="">Tous</option>
                            @foreach (($filterOptions['direction'] ?? []) as $value)
                                <option value="{{ $value }}" {{ ($filterDirection ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 kpi-filter-field">
                        <label for="start_date" class="form-label">Date début</label>
                        <input id="start_date" type="date" name="start_date" class="form-control form-control-sm" value="{{ $start }}">
                    </div>
                    <div class="col-lg-2 col-md-3 kpi-filter-field">
                        <label for="end_date" class="form-label">Date fin</label>
                        <input id="end_date" type="date" name="end_date" class="form-control form-control-sm" value="{{ $end }}">
                    </div>
                    <div class="col-lg-1 col-md-3 kpi-filter-field kpi-filter-narrow">
                        <label for="month" class="form-label">Mois</label>
                        <select id="month" name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">— Choisir un mois —</option>
                            @foreach ($monthOptions as $monthOption)
                                <option value="{{ $monthOption['value'] }}" {{ $selectedMonth === $monthOption['value'] ? 'selected' : '' }}>{{ $monthOption['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-lg-auto d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">Appliquer</button>
                        <a href="{{ route('kpi.roaming') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                    </div>
                    <div class="col-12 col-lg-auto ms-lg-auto d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a id="btn-export" href="{{ route('kpi.roaming.export', request()->query()) }}" class="btn btn-sm btn-success">Exporter CSV</a>
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Colonnes
                            </button>
                            <div class="dropdown-menu dropdown-menu-end p-2" id="column-menu">
                                @foreach ($tableColumns as $columnKey => $columnLabel)
                                    <label class="dropdown-item d-flex align-items-center gap-2">
                                        <input type="checkbox" class="form-check-input column-toggle" value="{{ $columnKey }}" checked>
                                        <span>{{ $columnLabel }}</span>
                                    </label>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <button type="button" class="btn btn-sm btn-link text-decoration-none w-100 text-start" id="show-all-columns">Tout afficher</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                        <table class="table kpi-roaming-table table-bordered table-hover table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th data-col="orig_type">Orig type</th>
                                    <th data-col="dest_type">Dest type</th>
                                    <th data-col="orig_net_name">Orig net</th>
                                    <th data-col="dest_net_name">Dest net</th>
                                    <th data-col="partner_name">Partner</th>
                                    <th data-col="attempt">Attempt</th>
                                    <th data-col="ner">NER</th>
                                    <th data-col="asr">ASR</th>
                                    <th data-col="acd_sec">ACD sec</th>
                                </tr>
                                <tr class="filter-row">
                                    @foreach ($filterConfigs as $key => $config)
                                        <th data-col="{{ $config['column'] }}">
                                            <select name="{{ $config['name'] }}" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="">Tous</option>
                                                @foreach (($filterOptions[$key] ?? []) as $value)
                                                    <option value="{{ $value }}" {{ ($config['selected'] ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                    @endforeach
                                    <th data-col="attempt" class="text-center text-muted">-</th>
                                    <th data-col="ner" class="text-center text-muted">-</th>
                                    <th data-col="asr" class="text-center text-muted">-</th>
                                    <th data-col="acd_sec" class="text-center text-muted">-</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($results->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Aucune donnée pour cette période / ces filtres.</td>
                                    </tr>
                                @else
                                    @foreach ($results as $row)
                                        <tr>
                                            <td data-col="orig_type">{{ $row->orig_type ?? '-' }}</td>
                                            <td data-col="dest_type">{{ $row->dest_type ?? '-' }}</td>
                                            <td data-col="orig_net_name">{{ $row->orig_net_name ?? '-' }}</td>
                                            <td data-col="dest_net_name">{{ $row->dest_net_name ?? '-' }}</td>
                                            <td data-col="partner_name">{{ $row->partner_name ?? '-' }}</td>
                                            <td data-col="attempt" class="text-end">{{ number_format((float) ($row->attempt ?? 0), 0, ',', ' ') }}</td>
                                            <td data-col="ner" class="text-end">{{ $row->ner ?? '-' }}</td>
                                            <td data-col="asr" class="text-end">{{ $row->asr ?? '-' }}</td>
                                            <td data-col="acd_sec" class="text-end">{{ $row->acd_sec ?? '-' }}</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('table.kpi-roaming-table');
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
            dateInput.addEventListener('change', function () {
                monthSelect.value = '';
            });
        }
    });

    const storageKey = 'kpi-roaming-hidden-columns';
    const exportButton = document.getElementById('btn-export');
    const toggles = Array.from(document.querySelectorAll('.column-toggle'));
    const filterColumnMap = {
        filter_orig_type: 'orig_type',
        filter_dest_type: 'dest_type',
        filter_orig_net: 'orig_net_name',
        filter_dest_net: 'dest_net_name',
        filter_partner: 'partner_name'
    };
    const labels = @json($tableColumns);

    function hiddenColumns() {
        return toggles.filter(toggle => !toggle.checked).map(toggle => toggle.value);
    }

    function updateExportLink(visibleColumns) {
        if (!exportButton) return;
        const url = new URL(exportButton.href, window.location.href);
        url.searchParams.set('cols', visibleColumns.join(','));
        exportButton.href = url.toString();
    }

    function updateHiddenFilterStatus(hidden) {
        document.querySelectorAll('.breadcrumb-filter-muted').forEach(function (item) {
            item.classList.remove('breadcrumb-filter-muted');
            const note = item.querySelector('.hidden-column-note');
            if (note) note.remove();
        });

        Object.entries(filterColumnMap).forEach(function ([inputName, column]) {
            const input = document.querySelector('[name="' + inputName + '"]');
            const breadcrumbItem = document.querySelector('[data-breadcrumb-col="' + column + '"]');
            if (hidden.includes(column) && input && input.value !== '' && breadcrumbItem) {
                breadcrumbItem.classList.add('breadcrumb-filter-muted');
                const note = document.createElement('small');
                note.className = 'hidden-column-note';
                note.textContent = '(colonne masquée)';
                breadcrumbItem.appendChild(note);
            }
        });
    }

    function applyVisibility() {
        const hidden = hiddenColumns();
        const visible = toggles.filter(toggle => toggle.checked).map(toggle => toggle.value);

        document.querySelectorAll('[data-col]').forEach(cell => {
            cell.style.display = hidden.includes(cell.dataset.col) ? 'none' : '';
        });
        localStorage.setItem(storageKey, JSON.stringify(hidden));
        updateExportLink(visible);
        updateHiddenFilterStatus(hidden);
    }

    let savedHidden = [];
    try {
        const stored = JSON.parse(localStorage.getItem(storageKey) || '[]');
        savedHidden = Array.isArray(stored) ? stored : [];
    } catch (error) {
        savedHidden = [];
    }

    toggles.forEach(toggle => {
        toggle.checked = !savedHidden.includes(toggle.value);
        toggle.addEventListener('change', function () {
            if (!this.checked && toggles.filter(item => item.checked).length === 0) {
                this.checked = true;
                return;
            }
            applyVisibility();
        });
    });

    document.getElementById('show-all-columns').addEventListener('click', function () {
        toggles.forEach(toggle => { toggle.checked = true; });
        applyVisibility();
    });

    applyVisibility();
});
</script>
@include('partials.date_sync')
</body>
</html>
