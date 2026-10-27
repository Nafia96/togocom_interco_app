<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ouvertures Roaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --kpi-group-row-height: 42px; --kpi-sub-row-height: 36px; --kpi-header-row-height: 52px; }
        body { background: #f4f6f9; font-family: "Segoe UI", Roboto, Arial, sans-serif; font-size: .85rem; }
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0, 0, 0, .06); }
        .card-header {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100; font-weight: 700; font-size: 1.05rem; padding: .75rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center; gap: 1rem;
            border-bottom: 3px solid #004aad; text-transform: uppercase; letter-spacing: .5px;
        }
        .row-count { margin-left: auto; }
        .filter-panel { border-bottom: 1px solid #dee2e6; padding-bottom: 1rem; margin-bottom: 1rem; }
        table.roaming-openings-table { width: 100%; min-width: 1700px; table-layout: auto; }
        table.table thead th {
            position: sticky; top: 0; background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100; z-index: 6; border-bottom: 2px solid #004aad; text-transform: uppercase;
            font-size: .78rem; vertical-align: middle; font-weight: 700; text-align: center; white-space: nowrap;
        }
        table.table thead tr:nth-child(1) th { top: 0; z-index: 12; height: var(--kpi-group-row-height); box-sizing: border-box; }
        table.table thead tr:nth-child(2) th { top: var(--kpi-group-row-height); z-index: 11; height: var(--kpi-sub-row-height); box-sizing: border-box; }
        table.table thead tr.filter-row th {
            top: calc(var(--kpi-group-row-height) + var(--kpi-sub-row-height)); z-index: 10; background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%);
            height: 52px; box-sizing: border-box; text-align: center;
        }
        table.roaming-openings-table .filter-row .form-select { width: 100%; min-width: 105px; }
        table.roaming-openings-table tbody td { text-align: center; white-space: nowrap; }
        table.roaming-openings-table th[data-col="code"],
        table.roaming-openings-table td[data-col="code"] { width: 160px; max-width: 160px; }
        .code-value { display: block; width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .action-column { min-width: 155px; width: 155px; white-space: nowrap; }
        .row-actions { display: inline-flex; align-items: center; justify-content: center; gap: .35rem; }
        table.roaming-openings-table .sticky-first-column {
            position: sticky; left: 0; min-width: 90px; z-index: 11; box-shadow: 2px 0 6px rgba(0, 0, 0, .1);
        }
        table.roaming-openings-table tbody td.sticky-first-column { background: #fff; font-weight: 700; z-index: 7; }
        table.roaming-openings-table thead .sticky-first-column { background: linear-gradient(90deg, #133272 0%, #1e4a98 100%); z-index: 14; }
        table.roaming-openings-table thead tr.filter-row .sticky-first-column { z-index: 15; background: linear-gradient(90deg, #173d82 0%, #1d4d9c 100%); }
        .status-pill { min-width: 82px; font-weight: 600; }
        .status-closed { color: #495057; background: #e9ecef; }
        .table-responsive { max-height: 68vh; }
        .column-menu { max-height: 65vh; overflow-y: auto; min-width: 260px; }
        .table-hover tbody tr:hover { background: rgba(19, 50, 114, .03); }
        @media (max-width: 768px) {
            .card-header { align-items: flex-start; flex-direction: column; }
            .row-count { margin-left: 0; flex-basis: 100%; }
            .table-responsive { max-height: 65vh; }
        }
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
                <span>OUVERTURES ROAMING</span>
            </div>
            @include('partials.cout_nav')
        </div>
        <div class="card-body">
            @php
                $columnLabels = [
                    'country' => 'Pays',
                    'code' => 'Code',
                    'operator' => 'Opérateur',
                    'postpaid_inbound' => 'Postpaid Inbound',
                    'postpaid_outbound' => 'Postpaid Outbound',
                    'camel_inbound' => 'Camel Inbound',
                    'camel_outbound' => 'Camel Outbound',
                    'data_3g_inbound' => 'Data 3G Inbound',
                    'data_3g_outbound' => 'Data 3G Outbound',
                    'data_4g_inbound' => 'Data 4G Inbound',
                    'data_4g_outbound' => 'Data 4G Outbound',
                    'data_5g_nsa_inbound' => 'Data 5G N-SA Inbound',
                    'data_5g_nsa_outbound' => 'Data 5G N-SA Outbound',
                    'k2r' => 'K2R',
                    'vrs' => 'VRS',
                ];
                $serviceColumns = array_slice(array_keys($columnLabels), 3);
                $identityColumns = array_slice($columnLabels, 0, 3, true);
                $headerGroups = [
                    'POSTPAID' => ['postpaid_inbound', 'postpaid_outbound'],
                    'CAMEL' => ['camel_inbound', 'camel_outbound'],
                    'DATA 3G' => ['data_3g_inbound', 'data_3g_outbound'],
                    'DATA 4G' => ['data_4g_inbound', 'data_4g_outbound'],
                    'DATA 5G N-SA' => ['data_5g_nsa_inbound', 'data_5g_nsa_outbound'],
                ];
                $singleServiceColumns = ['k2r', 'vrs'];
                $stickyColumn = reset($visibleCols);
                $visibleColumnCount = count($visibleCols) + 1;
            @endphp

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('kpi.roaming-openings') }}" id="roaming-openings-form">
                <div class="filter-panel">
                    <div class="row g-2 align-items-end">
                        @foreach ([
                            'country' => ['label' => 'Pays', 'placeholder' => 'Tous les pays'],
                            'code' => ['label' => 'Code', 'placeholder' => 'Tous les codes'],
                            'operator' => ['label' => 'Opérateur', 'placeholder' => 'Tous les opérateurs'],
                        ] as $column => $config)
                            @php($filterName = 'filter_' . $column)
                            <div class="col-12 col-sm-6 col-xl-2">
                                <label for="top_{{ $filterName }}" class="form-label">{{ $config['label'] }}</label>
                                <select id="top_{{ $filterName }}" name="{{ $filterName }}" class="form-select form-select-sm" onchange="this.form.querySelector('[data-filter-col={{ $column }}]').value = this.value; this.form.submit()">
                                    <option value="">Tous</option>
                                    @foreach ($filterOptions[$column] as $value)
                                        <option value="{{ $value }}" {{ ($selectedFilters[$filterName] ?? '') === $value ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                        <div class="col-12 col-xl-auto d-flex flex-wrap gap-2 align-items-end pt-2">
                            <a href="{{ route('kpi.roaming-openings') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                            <button type="button" id="open-add-roaming-opening" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#add-roaming-opening-modal">Ajouter une ouverture</button>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-end mb-3">
                            <a id="export-roaming-openings-csv" href="{{ route('kpi.roaming-openings.export', request()->query()) }}" class="btn btn-sm btn-success">Exporter CSV</a>
                            <div class="dropdown ms-sm-auto">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Colonnes</button>
                                <div class="dropdown-menu dropdown-menu-end p-2 column-menu">
                                    @foreach ($columnLabels as $column => $label)
                                        <label class="dropdown-item d-flex align-items-center gap-2">
                                            <input type="checkbox" class="form-check-input column-toggle" name="cols[]" value="{{ $column }}" {{ in_array($column, $visibleCols, true) ? 'checked' : '' }}>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none w-100 text-start" id="show-all-columns">Tout afficher</button>
                                </div>
                            </div>
                </div>

                <div class="d-flex align-items-center mb-2">
                    <span id="visible-row-count" class="badge text-bg-secondary" data-total="{{ $rowCount }}">{{ $rowCount }} ligne(s) affichée(s) sur {{ $rowCount }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table roaming-openings-table table-bordered table-hover table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                @foreach ($identityColumns as $column => $label)
                                    @if (in_array($column, $visibleCols, true))
                                        <th data-col="{{ $column }}" rowspan="2" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}">{{ $label }}</th>
                                    @endif
                                @endforeach
                                @foreach ($headerGroups as $groupLabel => $columns)
                                    @php($visibleGroupColumns = array_values(array_filter($columns, function ($column) use ($visibleCols) { return in_array($column, $visibleCols, true); })) )
                                    @if (count($visibleGroupColumns) > 0)
                                        <th colspan="{{ count($visibleGroupColumns) }}">{{ $groupLabel }}</th>
                                    @endif
                                @endforeach
                                @foreach ($singleServiceColumns as $column)
                                    @if (in_array($column, $visibleCols, true))
                                        <th data-col="{{ $column }}" rowspan="2" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}">{{ $columnLabels[$column] }}</th>
                                    @endif
                                @endforeach
                                <th rowspan="2" class="action-column">Actions</th>
                            </tr>
                            <tr>
                                @foreach ($headerGroups as $columns)
                                    @foreach ($columns as $column)
                                        @if (in_array($column, $visibleCols, true))
                                            <th data-col="{{ $column }}" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}">{{ strpos($column, '_inbound') !== false ? 'Inbound' : 'Outbound' }}</th>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tr>
                            <tr class="filter-row">
                                @foreach ($columnLabels as $column => $label)
                                    @if (in_array($column, $visibleCols, true))
                                        @if ($column === 'country' || $column === 'code' || $column === 'operator')
                                            @php($filterName = 'filter_' . $column)
                                            <th data-col="{{ $column }}" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}">
                                                <input type="text"
                                                       name="{{ $filterName }}"
                                                       class="form-control form-control-sm text-column-filter"
                                                       data-filter-col="{{ $column }}"
                                                       value="{{ $selectedFilters[$filterName] ?? '' }}"
                                                       placeholder="{{ ['country' => 'Rechercher un pays...', 'code' => 'Rechercher un code...', 'operator' => 'Rechercher un opérateur...'][$column] }}"
                                                       aria-label="Rechercher {{ $label }}">
                                            </th>
                                        @elseif (in_array($column, $serviceColumns, true))
                                            @php($filterName = 'filter_' . $column)
                                            <th data-col="{{ $column }}" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}">
                                                <select class="form-select form-select-sm header-filter" data-filter="{{ $filterName }}" aria-label="Filtrer {{ $label }}">
                                                    <option value="">Tous</option>
                                                    <option value="1" {{ ($selectedFilters[$filterName] ?? '') === '1' ? 'selected' : '' }}>Ouvert</option>
                                                    <option value="0" {{ ($selectedFilters[$filterName] ?? '') === '0' ? 'selected' : '' }}>Non ouvert</option>
                                                </select>
                                            </th>
                                        @else
                                            <th data-col="{{ $column }}" class="{{ $column === $stickyColumn ? 'sticky-first-column' : '' }}"><span class="text-muted">-</span></th>
                                        @endif
                                    @endif
                                @endforeach
                                <th class="action-column"><span class="text-muted">-</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($results->isEmpty())
                                <tr class="server-empty-row"><td colspan="{{ $visibleColumnCount }}" class="text-center text-muted">Aucune donnée pour ces filtres.</td></tr>
                            @else
                                @foreach ($results as $row)
                                    <tr data-roaming-row="true"
                                        data-opening-id="{{ $row->id }}"
                                        data-country="{{ $row->country }}"
                                        data-code="{{ $row->code }}"
                                        data-operator="{{ $row->operator }}"
                                        @foreach ($serviceColumns as $serviceColumn)
                                            data-{{ $serviceColumn }}="{{ (int) $row->{$serviceColumn} }}"
                                        @endforeach
                                    >
                                        @foreach ($columnLabels as $column => $label)
                                            @if (in_array($column, $visibleCols, true))
                                                @php($stickyClass = $column === $stickyColumn ? 'sticky-first-column' : '')
                                                @if (in_array($column, $serviceColumns, true))
                                                    @if ((int) $row->{$column} === 1)
                                                        <td data-col="{{ $column }}" class="{{ $stickyClass }}"><span class="badge rounded-pill text-bg-success status-pill">Ouvert</span></td>
                                                    @else
                                                        <td data-col="{{ $column }}" class="{{ $stickyClass }}"><span class="badge rounded-pill status-pill status-closed">Non ouvert</span></td>
                                                    @endif
                                                @else
                                                    <td data-col="{{ $column }}" class="{{ $stickyClass }}">
                                                        @if ($column === 'code')
                                                            <span class="code-value" title="{{ $row->{$column} ?? '' }}">{{ $row->{$column} ?? '' }}</span>
                                                        @else
                                                            {{ $row->{$column} ?? '' }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @endif
                                        @endforeach
                                        <td class="action-column">
                                            <div class="row-actions">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary edit-roaming-opening"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#add-roaming-opening-modal"
                                                    data-opening-id="{{ $row->id }}"
                                                    data-country="{{ $row->country }}"
                                                    data-code="{{ $row->code }}"
                                                    data-operator="{{ $row->operator }}"
                                                    @foreach ($serviceColumns as $serviceColumn)
                                                        data-{{ $serviceColumn }}="{{ (int) $row->{$serviceColumn} }}"
                                                    @endforeach
                                                    title="Modifier"
                                                    aria-label="Modifier {{ $row->operator }}">
                                                    Modifier
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-roaming-opening" data-bs-toggle="modal" data-bs-target="#delete-roaming-opening-modal" data-delete-url="{{ route('kpi.roaming-openings.destroy', $row->id) }}" data-operator="{{ $row->operator }}" data-country="{{ $row->country }}" title="Supprimer" aria-label="Supprimer {{ $row->operator }}">
                                                    Supprimer
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr id="client-filter-empty" hidden>
                                <td colspan="{{ $visibleColumnCount }}" class="text-center text-muted">Aucun résultat pour ce filtre.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="modal fade" id="add-roaming-opening-modal" tabindex="-1" aria-labelledby="roaming-opening-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST"
                              action="{{ route('kpi.roaming-openings.store') }}"
                              id="roaming-opening-form"
                              data-store-action="{{ route('kpi.roaming-openings.store') }}"
                              data-update-url-template="{{ route('kpi.roaming-openings.update', ['id' => '__OPENING_ID__']) }}">
                            @csrf
                            <input type="hidden" name="_method" id="roaming-opening-method" value="PUT" disabled>
                            <input type="hidden" name="opening_id" id="roaming-opening-id" value="{{ old('opening_id') }}">
                            <input type="hidden" name="opening_mode" id="roaming-opening-mode" value="{{ old('opening_mode', 'add') }}">
                            <input type="hidden" name="return_query" id="roaming-opening-return-query" value="{{ old('return_query', http_build_query(request()->query())) }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="roaming-opening-modal-title">Ajouter une ouverture</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                @include('kpi.partials.roaming-opening-fields', ['openingValues' => []])
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" id="roaming-opening-submit" class="btn btn-sm btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-roaming-opening-modal" tabindex="-1" aria-labelledby="delete-roaming-opening-title" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" id="delete-roaming-opening-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="return_query" id="delete-roaming-return-query" value="{{ http_build_query(request()->query()) }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="delete-roaming-opening-title">Confirmer la suppression</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-0" id="delete-roaming-opening-message"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('roaming-openings-form');
    const table = document.querySelector('.roaming-openings-table');
    const groupRow = table ? table.querySelector('thead tr:nth-child(1)') : null;
    const subRow = table ? table.querySelector('thead tr:nth-child(2)') : null;
    const textFilters = Array.from(document.querySelectorAll('.text-column-filter'));
    const dataRows = Array.from(document.querySelectorAll('tbody tr[data-roaming-row="true"]'));
    const emptyFilterRow = document.getElementById('client-filter-empty');
    const visibleRowCount = document.getElementById('visible-row-count');
    const exportLink = document.getElementById('export-roaming-openings-csv');

    function syncStickyHeaderOffset() {
        if (groupRow && subRow) {
            table.style.setProperty('--kpi-group-row-height', groupRow.getBoundingClientRect().height + 'px');
            table.style.setProperty('--kpi-sub-row-height', subRow.getBoundingClientRect().height + 'px');
        }
    }

    syncStickyHeaderOffset();
    window.addEventListener('resize', syncStickyHeaderOffset);

    function normalizeSearchText(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLocaleLowerCase();
    }

    function updateTextFilters() {
        const activeFilters = textFilters
            .map(input => ({ column: input.dataset.filterCol, value: normalizeSearchText(input.value.trim()) }))
            .filter(filter => filter.value !== '');
        let visibleCount = 0;

        dataRows.forEach(function (row) {
            const matches = activeFilters.every(function (filter) {
                const cell = row.querySelector('td[data-col="' + filter.column + '"]');
                return cell && normalizeSearchText(cell.textContent).includes(filter.value);
            });
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });

        if (emptyFilterRow) {
            emptyFilterRow.hidden = dataRows.length > 0 && visibleCount === 0;
        }
        if (visibleRowCount) {
            visibleRowCount.textContent = visibleCount + ' ligne(s) affichée(s) sur ' + dataRows.length;
        }

        if (exportLink) {
            const exportUrl = new URL(exportLink.href, window.location.origin);
            textFilters.forEach(function (input) {
                const value = input.value.trim();
                if (value) {
                    exportUrl.searchParams.set(input.name, value);
                } else {
                    exportUrl.searchParams.delete(input.name);
                }
            });
            exportLink.href = exportUrl.toString();
        }
    }

    textFilters.forEach(function (input) {
        input.addEventListener('input', updateTextFilters);
    });
    updateTextFilters();

    document.querySelectorAll('.header-filter').forEach(function (select) {
        select.addEventListener('change', function () {
            document.getElementById(this.dataset.filter).value = this.value;
            form.submit();
        });
    });

    const toggles = Array.from(document.querySelectorAll('.column-toggle'));
    toggles.forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            if (!toggles.some(item => item.checked)) {
                this.checked = true;
                return;
            }
            form.submit();
        });
    });

    document.getElementById('show-all-columns').addEventListener('click', function () {
        toggles.forEach(toggle => { toggle.checked = true; });
        form.submit();
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalForm = document.getElementById('roaming-opening-form');
    const returnQueryInput = document.getElementById('roaming-opening-return-query');
    const addOpeningModal = document.getElementById('add-roaming-opening-modal');
    const modal = bootstrap.Modal.getOrCreateInstance(addOpeningModal);
    const methodInput = document.getElementById('roaming-opening-method');
    const openingIdInput = document.getElementById('roaming-opening-id');
    const openingModeInput = document.getElementById('roaming-opening-mode');
    const modalTitle = document.getElementById('roaming-opening-modal-title');
    const submitButton = document.getElementById('roaming-opening-submit');
    const deleteModal = document.getElementById('delete-roaming-opening-modal');
    const deleteForm = document.getElementById('delete-roaming-opening-form');
    const deleteReturnQuery = document.getElementById('delete-roaming-return-query');
    const deleteMessage = document.getElementById('delete-roaming-opening-message');
    const serviceToggles = Array.from(document.querySelectorAll('.service-toggle'));

    function buildReturnQuery() {
        const params = new URLSearchParams(window.location.search);
        Array.from(params.keys()).forEach(function (key) {
            if (key.startsWith('filter_') || key === 'cols' || key.startsWith('cols[')) {
                params.delete(key);
            }
        });

        document.querySelectorAll('#roaming-openings-form [name^="filter_"]').forEach(function (field) {
            if (field.value !== '') params.set(field.name, field.value);
        });
        document.querySelectorAll('.column-toggle:checked').forEach(function (toggle) {
            params.append('cols[]', toggle.value);
        });
        return params.toString();
    }

    function updateReturnQuery() {
        returnQueryInput.value = buildReturnQuery();
    }

    function clearValidationState() {
        modalForm.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
        modalForm.querySelectorAll('.invalid-feedback').forEach(message => message.remove());
    }

    function resetServiceToggles() {
        serviceToggles.forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }

    function fillModalFields(source) {
        if (!source) {
            return;
        }

        document.getElementById('roaming-opening-country').value = source.dataset.country || '';
        document.getElementById('roaming-opening-code').value = source.dataset.code || '';
        document.getElementById('roaming-opening-operator').value = source.dataset.operator || '';

        serviceToggles.forEach(function (checkbox) {
            const value = source.getAttribute('data-' + checkbox.name);
            checkbox.checked = value === '1';
        });
    }

    function prepareAddMode(preserveValidation) {
        if (!preserveValidation) clearValidationState();
        modalForm.action = modalForm.dataset.storeAction;
        methodInput.disabled = true;
        methodInput.value = 'POST';
        openingIdInput.value = '';
        openingModeInput.value = 'add';
        modalTitle.textContent = 'Ajouter une ouverture';
        submitButton.textContent = 'Enregistrer';
        document.getElementById('roaming-opening-country').value = '';
        document.getElementById('roaming-opening-code').value = '';
        document.getElementById('roaming-opening-operator').value = '';
        resetServiceToggles();
    }

    function prepareEditMode(openingId, source, preserveValidation) {
        if (!preserveValidation) clearValidationState();
        const editSource = source || document.querySelector('button.edit-roaming-opening[data-opening-id="' + openingId + '"]');

        if (!openingId && editSource) {
            openingId = editSource.dataset.openingId || '';
        }

        modalForm.action = modalForm.dataset.updateUrlTemplate.replace('__OPENING_ID__', encodeURIComponent(openingId || ''));
        methodInput.disabled = false;
        methodInput.value = 'PUT';
        openingIdInput.value = openingId || '';
        openingModeInput.value = 'edit';
        modalTitle.textContent = 'Modifier une ouverture';
        submitButton.textContent = 'Enregistrer les modifications';

        fillModalFields(editSource);
    }

    addOpeningModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;

        if (trigger && trigger.id === 'open-add-roaming-opening') {
            prepareAddMode();
        } else if (trigger && trigger.matches('.edit-roaming-opening')) {
            const row = trigger.closest('tr[data-roaming-row="true"]');
            prepareEditMode(trigger.dataset.openingId, row || trigger);
        }

        updateReturnQuery();
    });

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        if (!trigger) return;
        deleteForm.action = trigger.dataset.deleteUrl;
        deleteMessage.textContent = 'Voulez-vous vraiment supprimer l’ouverture de ' + trigger.dataset.operator + ' (' + trigger.dataset.country + ') ? Cette action est irréversible.';
        deleteReturnQuery.value = buildReturnQuery();
    });

    @if ($errors->any())
        if (openingModeInput.value === 'edit' && openingIdInput.value) {
            prepareEditMode(openingIdInput.value, null, true);
        } else {
            prepareAddMode(true);
        }
        modal.show();
    @endif
});
</script>
</body>
</html>
