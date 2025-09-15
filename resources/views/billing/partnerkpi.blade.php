@extends('template.report_template')

@section('title', 'PARTNER KPI')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Filtres période -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header" style="background-color:#F5F5DC;">
                        <h6>
                            <i class="fas fa-search card-icon col-green font-30 p-r-30"></i>
                            Filtrer par période
                        </h6>
                    </div>
                    <div class="p-3">
                        <form method="GET" action="{{ route('partnerKpi') }}">
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
                                    <a href="{{ route('partnerKpi') }}" class="btn btn-secondary">Réinitialiser</a>
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
                                        <th>Période</th>
                                        <th>Semaine</th>
                                        <th>Partenaire</th>
                                        <th>Tentatives</th>
                                        <th>NER</th>
                                        <th>ACD (%)</th>
                                        <th>ACD (sec)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td>{{ $row->call_type }}</td>
                                            <td>{{ $row->dates_range }}</td>
                                            <td>{{ $row->call_week }}</td>
                                            <td>{{ $row->partner_name }}</td>
                                            <td>{{ number_format($row->attempt, 0, ',', ' ') }}</td>
                                            <td>{{ $row->NER }}</td>
                                            <td>{{ $row->ACD }}</td>
                                            <td>{{ $row->ACD_SEC }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Laravel -->
                        <div class="mt-3">
                            {{ $data->appends(request()->query())->links() }}
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
                    filename: 'PARTNER_KPI',
                    title: 'PARTNER KPI',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'csvHtml5',
                    footer: true,
                    filename: 'PARTNER_KPI',
                    title: 'PARTNER KPI',
                    exportOptions: { columns: ':visible' }
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    filename: 'PARTNER_KPI',
                    title: 'PARTNER KPI',
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
