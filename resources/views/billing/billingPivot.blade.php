@extends('template.billing_tamplate')

@section('title', 'Tableau Pivot - Facturation')

@section('content')
<div class="container">
    <h4 class="text-center mb-4">TABLEAU PIVOT DE FACTURATION</h4>

    <div class="row">

        <!-- Filtres -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header" style="background-color:#F5F5DC;">
                    <h6>
                        <i class="fas fa-search card-icon col-green font-30 p-r-30"></i>
                        Filtrer le tableau pivot
                    </h6>
                </div>
                <div class="p-3">
                    <form method="GET" action="{{ route('billingp') }}">
                        <div class="form-row filtre_form align-items-end">
                            <div class="form-group col-md-2">
                                <label>Direction</label>
                                <select class="form-control" name="direction">
                                    <option value="">Tous</option>
                                    <option value="Entrant" {{ request('direction') == 'Entrant' ? 'selected' : '' }}>Entrant</option>
                                    <option value="Sortant" {{ request('direction') == 'Sortant' ? 'selected' : '' }}>Sortant</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Opérateurs</label>
                                <select class="form-control demo1" name="carrier_name[]" multiple>
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator }}"
                                            {{ is_array(request('carrier_name')) && in_array($operator, request('carrier_name')) ? 'selected' : '' }}>
                                            {{ $operator }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Début</label>
                                <input type="date" class="form-control" name="start_period" value="{{ request('start_period') }}">
                            </div>

                            <div class="form-group col-md-2">
                                <label>Fin</label>
                                <input type="date" class="form-control" name="end_period" value="{{ request('end_period') }}">
                            </div>

                            <div class="form-group col-md-3 d-flex align-items-end" style="gap: 12px;">
                                <button class="btn btn-success" type="submit">
                                    <i class="material-icons align-middle">sort</i> Filtrer
                                </button>
                                <a href="{{ route('billingp') }}" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau pivot -->
            <div class="card">
                <div class="card-header" style="background-color:#F5F5DC;">
                    <h4>Résultats du Tableau Pivot</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (getUserType()->type_user == 3 || getUserType()->type_user == 2 || getUserType()->type_user == 1)
                            @if(count($pivotData) > 0)
                                <table class="table table-striped table-hover category" id="tableExpor" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Opérateur</th>
                                            @foreach($dates as $date)
                                                <th>{{ \Carbon\Carbon::parse($date)->format('d/m') }}</th>
                                            @endforeach
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pivotData as $carrier => $values)
                                            <tr>
                                                <td><b>{{ $carrier }}</b></td>
                                                @php $rowTotal = 0; @endphp
                                                @foreach($dates as $date)
                                                    @php
                                                        $val = $values[$date] ?? 0;
                                                        $rowTotal += $val;
                                                    @endphp
                                                    <td class="text-end">{{ number_format($val, 0, ',', ' ') }}</td>
                                                @endforeach
                                                <td class="fw-bold text-end">{{ number_format($rowTotal, 0, ',', ' ') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-secondary">
                                        <tr>
                                            <th>Total général</th>
                                            @php $grandTotal = 0; @endphp
                                            @foreach($dates as $date)
                                                @php
                                                    $colTotal = collect($pivotData)->sum(fn($row) => $row[$date] ?? 0);
                                                    $grandTotal += $colTotal;
                                                @endphp
                                                <th class="text-end">{{ number_format($colTotal, 0, ',', ' ') }}</th>
                                            @endforeach
                                            <th class="text-end">{{ number_format($grandTotal, 0, ',', ' ') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            @else
                                <div class="alert alert-warning">Aucune donnée trouvée pour la période sélectionnée.</div>
                            @endif
                        @else
                            <div class="alert alert-danger">Vous n’avez pas les droits pour accéder à ce tableau.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new SlimSelect({ select: '.demo1' });
});

$(document).ready(function() {
    var table = $('#tableExpor').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', filename: 'TABLEAU_PIVOT', title: 'Tableau Pivot de Facturation' },
            { extend: 'csvHtml5', filename: 'TABLEAU_PIVOT' },
            { extend: 'pdfHtml5', filename: 'TABLEAU_PIVOT', title: 'Tableau Pivot de Facturation' }
        ],
        "language": {
            "emptyTable": "Aucune donnée disponible",
            "lengthMenu": "Afficher _MENU_ éléments",
            "loadingRecords": "Chargement...",
            "processing": "Traitement...",
            "zeroRecords": "Aucun élément trouvé",
            "paginate": {
                "first": "Premier", "last": "Dernier", "next": "Suivant", "previous": "Précédent"
            },
            "search": "Rechercher:",
            "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
            "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
            "infoFiltered": "(filtrés de _MAX_ éléments au total)"
        }
    });
});
</script>
@endsection
