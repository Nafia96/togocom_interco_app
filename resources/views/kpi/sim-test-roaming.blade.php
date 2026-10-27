<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des SIM de test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: "Segoe UI", Roboto, Arial, sans-serif; font-size: .85rem; }
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0, 0, 0, .06); }
        .card-header { background: linear-gradient(90deg, #133272 0%, #1e4a98 100%); color: #ffd100; font-weight: 700; font-size: 1.05rem; padding: .75rem 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; border-bottom: 3px solid #004aad; text-transform: uppercase; }
        .filter-panel { border-bottom: 1px solid #dee2e6; padding-bottom: 1rem; margin-bottom: 1rem; }
        .sim-test-table { width: 100%; min-width: 1250px; table-layout: auto; }
        .sim-test-table thead th { position: sticky; top: 0; z-index: 6; background: linear-gradient(90deg, #133272 0%, #1e4a98 100%); color: #ffd100; border-bottom: 2px solid #004aad; text-transform: uppercase; font-size: .78rem; vertical-align: middle; font-weight: 700; text-align: center; white-space: nowrap; }
        .sim-test-table thead tr:first-child th { height: 42px; box-sizing: border-box; }
        .sim-test-table thead tr.filter-row th { top: 42px; z-index: 5; background: #173d82; }
        .sim-test-table tbody td { text-align: center; white-space: nowrap; }
        .sim-test-table td[data-col="rp_name"], .sim-test-table td[data-col="comments"] { text-align: left; }
        .sim-test-table th[data-col="group_number"], .sim-test-table td[data-col="group_number"] { width: 70px; }
        .sim-test-table th[data-col="comments"], .sim-test-table td[data-col="comments"] { min-width: 220px; }
        .action-column { min-width: 155px; white-space: nowrap; }
        .row-actions { display: inline-flex; align-items: center; justify-content: center; gap: .35rem; }
        .table-responsive { max-height: 68vh; }
        .table-hover tbody tr:hover { background: rgba(19, 50, 114, .03); }
        @media (max-width: 768px) { .card-header { align-items: flex-start; flex-direction: column; } .table-responsive { max-height: 65vh; } }
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
                <span>LISTE DES SIM DE TEST</span>
            </div>
            @include('partials.cout_nav')
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('kpi.sim-test-roaming') }}" id="sim-test-filter-form">
                <div class="filter-panel">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <label for="filter-country" class="form-label">Pays</label>
                            <select id="filter-country" name="filter_country" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les pays</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country }}" {{ request('filter_country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <label for="filter-rp-name" class="form-label">RP_name</label>
                            <select id="filter-rp-name" name="filter_rp_name" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Tous les RP_name</option>
                                @foreach ($rpNames as $rpName)
                                    <option value="{{ $rpName }}" {{ request('filter_rp_name') === $rpName ? 'selected' : '' }}>{{ $rpName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-xl-auto d-flex flex-wrap gap-2 align-items-end">
                            <a href="{{ route('kpi.sim-test-roaming') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                            <button type="button" id="open-add-sim-test" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#sim-test-modal">Ajouter</button>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-end mb-3">
                    <a id="export-sim-test-csv" href="{{ route('kpi.sim-test-roaming.export', request()->query()) }}" class="btn btn-sm btn-success">Exporter CSV</a>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span id="visible-row-count" class="badge text-bg-secondary">{{ $rowCount }} ligne(s) affichée(s) sur {{ $rowCount }}</span>
                </div>

                <div class="table-responsive">
                    <table class="table sim-test-table table-bordered table-hover table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th data-col="group_number">N°</th>
                                <th data-col="msisdn">Msisdn</th>
                                <th data-col="imsi">Imsi</th>
                                <th data-col="rp_name">RP_name</th>
                                <th data-col="country">Country</th>
                                <th data-col="comments">COMMENTAIRES</th>
                                <th class="action-column">Actions</th>
                            </tr>
                            <tr class="filter-row">
                                @foreach (['group_number' => 'N°', 'msisdn' => 'Msisdn', 'imsi' => 'Imsi', 'rp_name' => 'RP_name', 'country' => 'Country', 'comments' => 'COMMENTAIRES'] as $column => $label)
                                    <th data-col="{{ $column }}">
                                        @if ($column === 'group_number')
                                            <span class="text-muted">-</span>
                                        @else
                                            <input type="text" class="form-control form-control-sm sim-test-search" data-search-col="{{ $column }}" name="search_{{ $column }}" value="{{ request('search_' . $column) }}" placeholder="Rechercher..." aria-label="Rechercher {{ $label }}">
                                        @endif
                                    </th>
                                @endforeach
                                <th class="action-column"><span class="text-muted">-</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr data-sim-test-row="true">
                                    <td data-col="group_number">{{ $row->group_number }}</td>
                                    <td data-col="msisdn">{{ $row->msisdn }}</td>
                                    <td data-col="imsi">{{ $row->imsi }}</td>
                                    <td data-col="rp_name">{{ $row->rp_name }}</td>
                                    <td data-col="country">{{ $row->country }}</td>
                                    <td data-col="comments">{{ $row->comments }}</td>
                                    <td class="action-column">
                                        <div class="row-actions">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-sim-test" data-bs-toggle="modal" data-bs-target="#sim-test-modal" data-id="{{ $row->id }}" data-group-number="{{ $row->group_number }}" data-msisdn="{{ $row->msisdn }}" data-imsi="{{ $row->imsi }}" data-rp-name="{{ $row->rp_name }}" data-country="{{ $row->country }}" data-comments="{{ $row->comments }}" title="Modifier">Modifier</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-sim-test" data-bs-toggle="modal" data-bs-target="#delete-sim-test-modal" data-delete-url="{{ route('kpi.sim-test-roaming.destroy', $row->id) }}" data-msisdn="{{ $row->msisdn }}" data-rp-name="{{ $row->rp_name }}" title="Supprimer">Supprimer</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Aucune SIM trouvée.</td></tr>
                            @endforelse
                            <tr id="sim-test-filter-empty" hidden><td colspan="7" class="text-center text-muted">Aucun résultat pour cette recherche.</td></tr>
                        </tbody>
                    </table>
                </div>
            </form>

            <div class="modal fade" id="sim-test-modal" tabindex="-1" aria-labelledby="sim-test-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('kpi.sim-test-roaming.store') }}" id="sim-test-form" data-store-action="{{ route('kpi.sim-test-roaming.store') }}" data-update-url-template="{{ route('kpi.sim-test-roaming.update', ['id' => '__SIM_ID__']) }}">
                            @csrf
                            <input type="hidden" name="_method" id="sim-test-method" value="PUT" disabled>
                            <input type="hidden" name="opening_mode" id="sim-test-mode" value="{{ old('opening_mode', 'add') }}">
                            <input type="hidden" name="opening_id" id="sim-test-id" value="{{ old('opening_id') }}">
                            <input type="hidden" name="return_query" value="{{ http_build_query(request()->query()) }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="sim-test-modal-title">Ajouter une SIM</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-4"><label for="sim-test-group" class="form-label">N° groupe</label><input id="sim-test-group" type="number" min="1" name="group_number" value="{{ old('group_number') }}" class="form-control @error('group_number') is-invalid @enderror"><div class="invalid-feedback">@error('group_number'){{ $message }}@enderror</div></div>
                                    <div class="col-md-4"><label for="sim-test-msisdn" class="form-label">Msisdn</label><input id="sim-test-msisdn" type="text" name="msisdn" value="{{ old('msisdn') }}" class="form-control @error('msisdn') is-invalid @enderror" required maxlength="20"><div class="invalid-feedback">@error('msisdn'){{ $message }}@enderror</div></div>
                                    <div class="col-md-4"><label for="sim-test-imsi" class="form-label">Imsi</label><input id="sim-test-imsi" type="text" name="imsi" value="{{ old('imsi') }}" class="form-control @error('imsi') is-invalid @enderror" required maxlength="20"><div class="invalid-feedback">@error('imsi'){{ $message }}@enderror</div></div>
                                    <div class="col-md-6"><label for="sim-test-rp-name" class="form-label">RP_name</label><input id="sim-test-rp-name" type="text" name="rp_name" value="{{ old('rp_name') }}" class="form-control @error('rp_name') is-invalid @enderror" required maxlength="150"><div class="invalid-feedback">@error('rp_name'){{ $message }}@enderror</div></div>
                                    <div class="col-md-6"><label for="sim-test-country" class="form-label">Country</label><input id="sim-test-country" type="text" name="country" value="{{ old('country') }}" class="form-control @error('country') is-invalid @enderror" required maxlength="100"><div class="invalid-feedback">@error('country'){{ $message }}@enderror</div></div>
                                    <div class="col-12"><label for="sim-test-comments" class="form-label">COMMENTAIRES</label><textarea id="sim-test-comments" name="comments" class="form-control @error('comments') is-invalid @enderror" maxlength="255" rows="2">{{ old('comments') }}</textarea><div class="invalid-feedback">@error('comments'){{ $message }}@enderror</div></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" id="sim-test-submit" class="btn btn-sm btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="delete-sim-test-modal" tabindex="-1" aria-labelledby="delete-sim-test-title" aria-hidden="true">
                <div class="modal-dialog"><div class="modal-content">
                    <form method="POST" id="delete-sim-test-form">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="return_query" value="{{ http_build_query(request()->query()) }}">
                        <div class="modal-header"><h5 class="modal-title" id="delete-sim-test-title">Confirmer la suppression</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button></div>
                        <div class="modal-body"><p class="mb-0" id="delete-sim-test-message"></p></div>
                        <div class="modal-footer"><button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-sm btn-danger">Supprimer</button></div>
                    </form>
                </div></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('sim-test-form');
    const modalElement = document.getElementById('sim-test-modal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
    const methodInput = document.getElementById('sim-test-method');
    const modeInput = document.getElementById('sim-test-mode');
    const idInput = document.getElementById('sim-test-id');
    const searchInputs = Array.from(document.querySelectorAll('.sim-test-search'));
    const dataRows = Array.from(document.querySelectorAll('tbody tr[data-sim-test-row="true"]'));
    const countLabel = document.getElementById('visible-row-count');
    const emptyRow = document.getElementById('sim-test-filter-empty');
    const exportLink = document.getElementById('export-sim-test-csv');
    const fieldIds = { group_number: 'sim-test-group', msisdn: 'sim-test-msisdn', imsi: 'sim-test-imsi', rp_name: 'sim-test-rp-name', country: 'sim-test-country', comments: 'sim-test-comments' };

    function clearValidation() {
        form.querySelectorAll('.is-invalid').forEach(field => field.classList.remove('is-invalid'));
    }

    function prepareAdd() {
        clearValidation();
        form.action = form.dataset.storeAction;
        methodInput.disabled = true;
        modeInput.value = 'add';
        idInput.value = '';
        document.getElementById('sim-test-modal-title').textContent = 'Ajouter une SIM';
        document.getElementById('sim-test-submit').textContent = 'Enregistrer';
        Object.values(fieldIds).forEach(id => { document.getElementById(id).value = ''; });
    }

    modalElement.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        if (trigger && trigger.id === 'open-add-sim-test') {
            prepareAdd();
            return;
        }
        if (!trigger || !trigger.matches('.edit-sim-test')) return;

        clearValidation();
        const id = trigger.dataset.id;
        form.action = form.dataset.updateUrlTemplate.replace('__SIM_ID__', encodeURIComponent(id));
        methodInput.disabled = false;
        modeInput.value = 'edit';
        idInput.value = id;
        document.getElementById('sim-test-modal-title').textContent = 'Modifier une SIM';
        document.getElementById('sim-test-submit').textContent = 'Enregistrer les modifications';
        Object.keys(fieldIds).forEach(function (key) {
            document.getElementById(fieldIds[key]).value = trigger.getAttribute('data-' + key.replace('_', '-')) || '';
        });
    });

    searchInputs.forEach(input => input.addEventListener('input', updateSearch));

    function updateSearch() {
        const active = searchInputs.map(input => ({ column: input.dataset.searchCol, value: input.value.trim().toLocaleLowerCase() })).filter(filter => filter.value !== '');
        let visible = 0;
        dataRows.forEach(function (row) {
            const matches = active.every(function (filter) {
                const cell = row.querySelector('td[data-col="' + filter.column + '"]');
                return cell && cell.textContent.toLocaleLowerCase().includes(filter.value);
            });
            row.hidden = !matches;
            if (matches) visible++;
        });
        countLabel.textContent = visible + ' ligne(s) affichée(s) sur ' + dataRows.length;
        emptyRow.hidden = dataRows.length === 0 || visible !== 0;
        const exportUrl = new URL(exportLink.href, window.location.origin);
        searchInputs.forEach(function (input) {
            if (input.value.trim()) exportUrl.searchParams.set(input.name, input.value.trim());
            else exportUrl.searchParams.delete(input.name);
        });
        exportLink.href = exportUrl.toString();
    }

    const deleteModal = document.getElementById('delete-sim-test-modal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const trigger = event.relatedTarget;
        if (!trigger) return;
        document.getElementById('delete-sim-test-form').action = trigger.dataset.deleteUrl;
        document.getElementById('delete-sim-test-message').textContent = 'Supprimer la SIM ' + trigger.dataset.msisdn + ' (' + trigger.dataset.rpName + ') ?';
    });

    @if ($errors->any())
        if (modeInput.value === 'edit' && idInput.value) {
            form.action = form.dataset.updateUrlTemplate.replace('__SIM_ID__', encodeURIComponent(idInput.value));
            methodInput.disabled = false;
            methodInput.value = 'PUT';
            document.getElementById('sim-test-modal-title').textContent = 'Modifier une SIM';
            document.getElementById('sim-test-submit').textContent = 'Enregistrer les modifications';
        }
        modal.show();
    @endif

    updateSearch();
});
</script>
</body>
</html>
