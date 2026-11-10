@extends('template.principal_tamplate3')

@section('title')
    Déclarations internationales
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tgc-tgt') }}">National</a></li>
            <li class="breadcrumb-item active" aria-current="page">International · Déclarations</li>
        </ol>
    </nav>
@endsection

@section('content')
    <style>
        .declarations-page {
            padding-top: 1.25rem;
            padding-bottom: 1.5rem;
        }

        .declarations-header {
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .declaration-summary-card {
            height: 100%;
            min-height: 132px;
            padding: 1rem 1.1rem;
            background: #fff;
            border: 1px solid #e6e9ef;
            border-top: 3px solid #3157a4;
            border-radius: 4px;
        }

        .declaration-summary-card--wholesale {
            border-top-color: #168a83;
        }

        .declaration-summary-card--roaming {
            border-top-color: #d28a24;
        }

        .declaration-summary-card--charge {
            border-top-color: #ca5263;
        }

        .declaration-summary-label {
            margin-bottom: .65rem;
        }

        .declaration-summary-heading {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: .25rem .55rem;
            margin-bottom: .2rem;
        }

        .declaration-summary-title {
            white-space: nowrap;
            color: #29364b;
            font-size: .95rem;
            font-weight: 700;
        }

        .declaration-summary-period {
            display: block;
            color: #6c757d;
            font-size: .8rem;
        }

        .declaration-summary-context {
            white-space: nowrap;
            color: #3157a4;
            font-size: .78rem;
            font-weight: 600;
        }

        .declaration-summary-value {
            display: block;
            color: #29364b;
            font-size: 1.3rem;
            font-weight: 600;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .declaration-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .declarations-data-panel {
            padding: 1rem;
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 4px;
        }

        .declarations-direction-guide {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 1rem;
            background: #f6f8fb;
            border: 1px solid #e6e9ef;
            border-radius: 3px;
        }

        .declarations-direction-group {
            min-width: 0;
            padding: .7rem .85rem;
        }

        .declarations-direction-group + .declarations-direction-group {
            border-left: 1px solid #dfe4ec;
        }

        .declarations-direction-category,
        .declarations-direction-label {
            display: block;
        }

        .declarations-direction-category {
            margin-bottom: .15rem;
            color: #6c757d;
            font-size: .78rem;
        }

        .declarations-direction-label {
            color: #29364b;
            font-size: .9rem;
            font-weight: 700;
        }

        .declarations-table-toolbar,
        .declarations-table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .declarations-table-toolbar {
            margin-bottom: 1rem;
        }

        .declarations-table-footer {
            margin-top: 1rem;
        }

        .declarations-table-exports .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .declarations-table-exports .dt-button {
            margin: 0;
        }

        .declarations-table-search,
        .declarations-table-info,
        .declarations-table-pagination {
            min-width: 0;
        }

        .declarations-table-search .dataTables_filter,
        .declarations-table-info .dataTables_info,
        .declarations-table-pagination .dataTables_paginate {
            margin: 0;
            padding: 0;
        }

        .declarations-table-search .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
        }

        .declarations-table-search .dataTables_filter input {
            width: min(16rem, 55vw);
            margin-left: 0;
        }

        .declarations-table-scroll {
            width: 100%;
            overflow-x: auto;
        }

        #internationalDeclarationsTable {
            width: 100% !important;
            min-width: 680px;
            margin-bottom: 0;
        }

        #internationalDeclarationsTable thead th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #internationalDeclarationsTable tbody th,
        #internationalDeclarationsTable tbody td {
            vertical-align: middle;
        }

        #internationalDeclarationsTable .no-export {
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .declarations-data-panel {
                padding: .75rem;
            }

            .declarations-direction-guide {
                grid-template-columns: 1fr;
            }

            .declarations-direction-group + .declarations-direction-group {
                border-top: 1px solid #dfe4ec;
                border-left: 0;
            }

            .declarations-table-toolbar,
            .declarations-table-footer {
                align-items: stretch;
                flex-direction: column;
                gap: .75rem;
            }

            .declarations-table-search .dataTables_filter label {
                align-items: flex-start;
                flex-direction: column;
            }

            .declarations-table-search .dataTables_filter input {
                width: 100%;
            }

            .declarations-table-pagination .dataTables_paginate {
                float: none;
                text-align: left;
            }
        }
    </style>

    <div class="container-fluid declarations-page">
        <div class="d-flex flex-wrap justify-content-between align-items-center declarations-header">
            <div>
                <h4 class="mb-1">Déclarations internationales</h4>
                <p class="text-muted mb-0">Saisie mensuelle des valeurs déclarées.</p>
            </div>
            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                <div class="declaration-actions mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary declaration-add-button" data-toggle="modal" data-target="#declarationModal" data-field="retail_tgc" data-label="Retail TGC">+ Retail TGC</button>
                    <button type="button" class="btn btn-outline-primary declaration-add-button" data-toggle="modal" data-target="#declarationModal" data-field="wholesale" data-label="Wholesale">+ Wholesale</button>
                    <button type="button" class="btn btn-outline-primary declaration-add-button" data-toggle="modal" data-target="#declarationModal" data-field="visitor_roaming" data-label="Roaming visiteur">+ Roaming visiteur</button>
                    <button type="button" class="btn btn-outline-primary declaration-add-button" data-toggle="modal" data-target="#declarationModal" data-field="charge" data-label="Charge">+ Charge</button>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @php
            $declarationSummary = [
                ['field' => 'retail_tgc', 'label' => 'Retail TGC', 'context' => 'TGC → TGT', 'class' => ''],
                ['field' => 'wholesale', 'label' => 'Wholesale', 'context' => 'TGT → TGC', 'class' => 'declaration-summary-card--wholesale'],
                ['field' => 'visitor_roaming', 'label' => 'Roaming visiteur', 'context' => 'TGT → TGC', 'class' => 'declaration-summary-card--roaming'],
                ['field' => 'charge', 'label' => 'Charge', 'context' => 'TGC', 'class' => 'declaration-summary-card--charge'],
            ];
        @endphp

        @php
            $periodStart = $declarations->last();
            $periodEnd = $declarations->first();
        @endphp

        <div class="row declaration-summary" aria-label="Synthèse des montants cumulés par catégorie">
            @foreach ($declarationSummary as $summary)
                <div class="col-12 col-sm-6 col-xl-3 mb-3">
                    <article class="declaration-summary-card {{ $summary['class'] }}">
                        <div class="declaration-summary-label">
                            <div class="declaration-summary-heading">
                            <span class="declaration-summary-title">{{ $summary['label'] }}</span>
                            <span class="declaration-summary-context">{{ $summary['context'] }}</span>
                            </div>
                            <span class="declaration-summary-period">Début : {{ $periodStart ? $periodStart->period : '—' }} · Fin : {{ $periodEnd ? $periodEnd->period : '—' }}</span>
                        </div>
                        <span class="declaration-summary-value">
                            @php
                                $summaryTotal = (string) ($declarationTotals->{$summary['field']} ?? '0.00');
                                $summaryParts = explode('.', $summaryTotal, 2);
                                $summaryInteger = preg_replace('/\B(?=(\d{3})+(?!\d))/', ' ', $summaryParts[0]);
                                $summaryDecimals = substr(str_pad($summaryParts[1] ?? '', 2, '0'), 0, 2);
                            @endphp
                            {{ $summaryInteger }},{{ $summaryDecimals }}
                        </span>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="declarations-data-panel">
            <div class="declarations-direction-guide" aria-label="Correspondance des colonnes et des sens">
                <div class="declarations-direction-group">
                    <span class="declarations-direction-category">Retail TGC</span>
                    <span class="declarations-direction-label">Sens TGC → TGT</span>
                </div>
                <div class="declarations-direction-group">
                    <span class="declarations-direction-category">Wholesale + Roaming visiteur</span>
                    <span class="declarations-direction-label">Sens TGT → TGC</span>
                </div>
                <div class="declarations-direction-group">
                    <span class="declarations-direction-category">Charge</span>
                    <span class="declarations-direction-label">TGC</span>
                </div>
            </div>
            <table id="internationalDeclarationsTable" class="table table-striped table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Période</th>
                        <th scope="col">Retail TGC</th>
                        <th scope="col">Wholesale</th>
                        <th scope="col">Roaming visiteur</th>
                        <th scope="col">Charge</th>
                        <th scope="col" class="no-export">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($declarations as $declaration)
                        <tr>
                            <th scope="row">{{ $declaration->period }}</th>
                            <td>{{ $declaration->retail_tgc ?? '—' }}</td>
                            <td>{{ $declaration->wholesale ?? '—' }}</td>
                            <td>{{ $declaration->visitor_roaming ?? '—' }}</td>
                            <td>{{ $declaration->charge ?? '—' }}</td>
                            <td class="no-export">
                                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary declaration-edit-button"
                                            data-toggle="modal"
                                            data-target="#editDeclarationModal"
                                            data-update-url="{{ route('international.declarations.update', $declaration->id) }}"
                                            data-period="{{ $declaration->period }}"
                                            data-retail-tgc="{{ $declaration->retail_tgc }}"
                                            data-wholesale="{{ $declaration->wholesale }}"
                                            data-visitor-roaming="{{ $declaration->visitor_roaming }}"
                                            data-charge="{{ $declaration->charge }}">
                                        Modifier
                                    </button>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucune déclaration enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="editDeclarationModal" tabindex="-1" role="dialog" aria-labelledby="editDeclarationModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="editDeclarationForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDeclarationModalTitle">Modifier une déclaration</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="editDeclarationPeriod">Période (mois)</label>
                                    <input id="editDeclarationPeriod" name="period" type="month" class="form-control" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="editRetailTgc">Retail TGC</label>
                                    <input id="editRetailTgc" name="retail_tgc" type="number" class="form-control" min="0" max="999999999999999999.99" step="0.01">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="editWholesale">Wholesale</label>
                                    <input id="editWholesale" name="wholesale" type="number" class="form-control" min="0" max="999999999999999999.99" step="0.01">
                                </div>
                            </div>
                            <div class="form-row mb-0">
                                <div class="form-group col-md-6">
                                    <label for="editVisitorRoaming">Roaming visiteur</label>
                                    <input id="editVisitorRoaming" name="visitor_roaming" type="number" class="form-control" min="0" max="999999999999999999.99" step="0.01">
                                </div>
                                <div class="form-group col-md-6 mb-0">
                                    <label for="editCharge">Charge</label>
                                    <input id="editCharge" name="charge" type="number" class="form-control" min="0" max="999999999999999999.99" step="0.01">
                                </div>
                            </div>
                            <small class="form-text text-muted">Les valeurs sont enregistrées telles que saisies, sans calcul.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
        <div class="modal fade" id="declarationModal" tabindex="-1" role="dialog" aria-labelledby="declarationModalTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('international.declarations.store') }}">
                        @csrf
                        <input type="hidden" name="field" id="declarationField" value="{{ old('field', 'retail_tgc') }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="declarationModalTitle">Ajouter une déclaration</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="declarationPeriod">Période (mois)</label>
                                <input id="declarationPeriod" name="period" type="month" class="form-control @error('period') is-invalid @enderror" value="{{ old('period', now()->format('Y-m')) }}" required>
                                @error('period')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="declarationValue" id="declarationValueLabel">Valeur déclarée</label>
                                <input id="declarationValue" name="value" type="number" class="form-control" min="0" max="999999999999999999.99" step="0.01" value="{{ old('value') }}" required>
                            </div>
                            <small class="form-text text-muted">Une seule déclaration est autorisée par période. Pour modifier une période existante, utilisez le bouton Modifier du tableau.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#declarationModal, #editDeclarationModal').forEach(function (modal) {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });

            const fieldInput = document.getElementById('declarationField');
            const title = document.getElementById('declarationModalTitle');
            const valueLabel = document.getElementById('declarationValueLabel');
            const valueInput = document.getElementById('declarationValue');

            document.querySelectorAll('.declaration-add-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    const label = this.dataset.label;
                    fieldInput.value = this.dataset.field;
                    title.textContent = 'Ajouter ' + label;
                    valueLabel.textContent = 'Valeur déclarée · ' + label;
                    valueInput.value = '';
                });
            });

            const editForm = document.getElementById('editDeclarationForm');
            document.querySelectorAll('.declaration-edit-button').forEach(function (button) {
                button.addEventListener('click', function () {
                    editForm.action = this.dataset.updateUrl;
                    document.getElementById('editDeclarationPeriod').value = this.dataset.period;
                    document.getElementById('editRetailTgc').value = this.dataset.retailTgc || '';
                    document.getElementById('editWholesale').value = this.dataset.wholesale || '';
                    document.getElementById('editVisitorRoaming').value = this.dataset.visitorRoaming || '';
                    document.getElementById('editCharge').value = this.dataset.charge || '';
                });
            });

            const previousButton = Array.from(document.querySelectorAll('.declaration-add-button'))
                .find(button => button.dataset.field === fieldInput.value);
            if (previousButton) {
                title.textContent = 'Ajouter ' + previousButton.dataset.label;
                valueLabel.textContent = 'Valeur déclarée · ' + previousButton.dataset.label;
            }

            @if ($errors->any())
                if (window.jQuery) {
                    window.jQuery('#declarationModal').modal('show');
                }
            @endif
        });
        </script>
    @endif
@endsection

@section('script')
    <script>
        $(function () {
            $('#internationalDeclarationsTable').DataTable({
                dom: '<"declarations-table-toolbar"<"declarations-table-exports"B><"declarations-table-search"f>><"declarations-table-scroll"t><"declarations-table-footer"<"declarations-table-info"i><"declarations-table-pagination"p>>',
                order: [[0, 'desc']],
                buttons: [
                    { extend: 'copy', text: 'Copier', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':not(.no-export)' } },
                    { extend: 'csv', text: 'CSV', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':not(.no-export)' } },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':not(.no-export)' } },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-sm btn-outline-secondary', orientation: 'landscape', exportOptions: { columns: ':not(.no-export)' } },
                    { extend: 'print', text: 'Imprimer', className: 'btn btn-sm btn-outline-secondary', exportOptions: { columns: ':not(.no-export)' } },
                ],
                language: {
                    emptyTable: 'Aucune déclaration enregistrée.',
                    search: 'Rechercher :',
                    lengthMenu: 'Afficher _MENU_ lignes',
                    info: '_START_ à _END_ sur _TOTAL_ lignes',
                    infoEmpty: 'Aucune déclaration',
                    zeroRecords: 'Aucune déclaration correspondante',
                    paginate: { first: 'Première', last: 'Dernière', next: 'Suivante', previous: 'Précédente' }
                }
            });
        });
    </script>
@endsection
