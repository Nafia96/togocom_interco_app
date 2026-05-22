@extends('template.billing_tamplate')

@section('title', 'NETWORK KPI')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: 0.25rem 0.5rem; display: flex; align-items: center;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                    <span>Launchpad</span>
                </a>

            </div>
             <h3 class="mb-0">NETWORK KPI</h3>
            <div class="d-flex align-items-center" style="gap:8px;">
                <div class="me-2 d-flex" style="gap:6px;">
                    <a href="{{ route('partnerKpi', request()->all()) }}" class="btn btn-sm btn-secondary {{ request()->routeIs('partnerKpi') ? 'active' : '' }}" title="Partner KPI">
                        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16"><path d="M2 2h12v3H2V2zm0 4h5v8H2V6zm6 0h6v8H8V6z"/></svg>
                        &nbsp;P. KPI
                    </a>

                    <a href="{{ route('networkkpi', request()->all()) }}" class="btn btn-sm btn-secondary {{ request()->routeIs('networkkpi') ? 'active' : '' }}" title="Network KPI">
                        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 4h-2A5.5 5.5 0 0 0 4 9.5v2A3.5 3.5 0 0 1 7.5 8h4A1.5 1.5 0 0 0 13.5 6V4z"/></svg>
                        &nbsp;N. KPI
                    </a>
                </div>

                <a href="{{ route('kpi.pivot', request()->all()) }}" class="btn btn-sm btn-secondary px-3" style="font-weight:700; background: linear-gradient(180deg, #0056d2 0%, #004aad 100%); border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow me-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10.293 3.293a1 1 0 0 1 1.414 0L15 6.586V4a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.586L11.707 6.707a1 1 0 0 1 0-1.414l-1.414-1.414zM5 9l2-2 3 3 4-4 1 1-5 5-3-3-2 2-1-1z"/></svg>
                    KPI
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Filtres période -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header" style="background: linear-gradient(90deg, #133272 0%, #1e4a98 100%); color: #ffd100; font-weight: 700; padding: 0.75rem 1.25rem; border-bottom: 3px solid #004aad;">
                        <h6 style="margin: 0; display: flex; align-items: center;">
                            <i class="fas fa-search card-icon p-r-30" style="margin-right: 15px; font-size: 1.2rem;"></i>
                            Filtrer les statistiques par période
                        </h6>
                    </div>
                    <div class="p-3">
                        <form method="GET" action="{{ route('networkkpi') }}">
                            <div class="form-row filtre_form align-items-end">
                                <div class="form-group col-md-3">
                                    <label>Début</label>
                                    <input type="date" class="form-control" name="start_period"
                                        value="{{ request('start_period', $filters['start']) }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Fin</label>
                                    <input type="date" class="form-control" name="end_period"
                                        value="{{ request('end_period', $filters['end']) }}">
                                </div>
                                <div class="form-group col-md-3 d-flex align-items-end" style="gap: 12px;">
                                    <button class="btn btn-success" type="submit">
                                        <i class="material-icons align-middle">sort</i> Filtrer
                                    </button>
                                    <a href="{{ route('networkkpi') }}" class="btn btn-secondary">Réinitialiser</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="card">
                    <div class="card-header" style="background-color:#F5F5DC;">
                        <h4>Résultat du filtre</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover category" id="tableExpor" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Type d’appel</th>
                                        <th>Période (range)</th>
                                        <th>Semaine</th>
                                        <th>Réseau</th>
                                        <th>Partenaire</th>
                                        <th>Tentatives</th>
                                        <th>NER</th>
                                        <th>ASR</th>
                                        <th>ACD (sec)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td>{{ $row->call_type }}</td>
                                            <td>{{ $row->dates_range }}</td>
                                            <td>{{ $row->call_week }}</td>
                                            <td>{{ $row->net_name }}</td>
                                            <td>{{ $row->partner_name }}</td>
                                            <td>{{ number_format($row->attempt, 0, ',', ' ') }}</td>
                                            <td>{{ $row->NER }}</td>
                                            <td>{{ $row->ASR }}</td>
                                            <td>{{ $row->ACD_SEC }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#tableExpor').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    footer: true,
                    filename: 'STATISTIQUES_COMPLETION',
                    title: 'STATISTIQUES DE COMPLÉTION',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'csvHtml5',
                    footer: true,
                    filename: 'STATISTIQUES_COMPLETION',
                    title: 'STATISTIQUES DE COMPLÉTION',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    filename: 'STATISTIQUES_COMPLETION',
                    title: 'STATISTIQUES DE COMPLÉTION',
                    exportOptions: { columns: ':visible' }
                }
            ],
            "language": {
                "emptyTable": "Aucune donnée disponible",
                "lengthMenu": "Afficher _MENU_ éléments",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "zeroRecords": "Aucun élément trouvé",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                },
                "search": "Rechercher:",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                "infoFiltered": "(filtré de _MAX_ éléments au total)",
                "thousands": "."
            }
        });
    });
</script>
@endsection
