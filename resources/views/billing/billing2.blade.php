@extends('template.billing_tamplate')

@section('title', 'Statistiques de facturation')

@section('content')
    <div class="container">
        <h4 class="text-center mb-4">STATISTIQUES DE FACTURATION</h4>

        <div class="row">

            <!-- Filtres améliorés -->
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header" style="background-color:#F5F5DC;">
                        <h6>
                            <i class="fas fa-search card-icon col-green font-30 p-r-30"></i>
                            Filtrer les statistiques de facturation
                        </h6>
                    </div>
                    <div class="p-3">
                        <form method="GET" action="{{ route('billing2') }}">
                            <div class="form-row filtre_form align-items-end">
                                <div class="form-group col-md-2">
                                    <label>Direction</label>
                                    <select class="form-control" name="direction">
                                        <option value="">Tous</option>
                                        <option value="Revenue" {{ request('direction') == 'Revenue' ? 'selected' : '' }}>
                                            Revenue</option>
                                        <option value="Charge" {{ request('direction') == 'Charge' ? 'selected' : '' }}>
                                            Charge</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
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
                                    <label>Réseau origine</label>
                                    <select class="form-control demo2" name="orig_net_name[]" multiple>
                                        @foreach ($origNets as $net)
                                            <option value="{{ $net }}"
                                                {{ is_array(request('orig_net_name')) && in_array($net, request('orig_net_name')) ? 'selected' : '' }}>
                                                {{ $net }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Réseau destination</label>
                                    <select class="form-control demo3" name="dest_net_name[]" multiple>
                                        @foreach ($destNets as $net)
                                            <option value="{{ $net }}"
                                                {{ is_array(request('dest_net_name')) && in_array($net, request('dest_net_name')) ? 'selected' : '' }}>
                                                {{ $net }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Pays origine</label>
                                    <select class="form-control demo4" name="orig_country_name[]" multiple>
                                        @foreach ($origCountries as $country)
                                            <option value="{{ $country }}"
                                                {{ is_array(request('orig_country_name')) && in_array($country, request('orig_country_name')) ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Pays destination</label>
                                    <select class="form-control demo5" name="dest_country_name[]" multiple>
                                        @foreach ($destCountries as $country)
                                            <option value="{{ $country }}"
                                                {{ is_array(request('dest_country_name')) && in_array($country, request('dest_country_name')) ? 'selected' : '' }}>
                                                {{ $country }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row mt-2">
                                <div class="form-group col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_net_name"
                                            id="show_net_name" value="1"
                                            {{ request('show_net_name') == '1' || request('orig_net_name') || request('dest_net_name') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_net_name">
                                            Afficher Réseaux (origine/destination)
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="show_country_name"
                                            id="show_country_name" value="1"
                                            {{ request('show_country_name') == '1' || request('orig_country_name') || request('dest_country_name') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_country_name">
                                            Afficher Pays (origine/destination)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row filtre_form align-items-end mt-2">
                                <div class="form-group col-md-2">
                                    <label>Type de vue</label>
                                    <select class="form-control" name="view_type">
                                         <option value="daily_carrier"
                                            {{ request('view_type') == 'daily_carrier' ? 'selected' : '' }}>Daily Carrier
                                        </option>
                                        <option value="daily_summary"
                                            {{ request('view_type') == 'daily_summary' ? 'selected' : '' }}>Daily Summary
                                        </option>
                                       
                                        <option value="monthly_summary"
                                            {{ request('view_type') == 'monthly_summary' ? 'selected' : '' }}>Monthly
                                            Summary</option>
                                        <option value="monthly_carrier"
                                            {{ request('view_type') == 'monthly_carrier' ? 'selected' : '' }}>Monthly
                                            Carrier</option>
                                        <option value="monthly_details"
                                            {{ request('view_type') == 'monthly_details' ? 'selected' : '' }}>Monthly
                                            Details</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Début</label>
                                    <input type="month" class="form-control" name="start_period"
                                        value="{{ request('start_period') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Fin</label>
                                    <input type="month" class="form-control" name="end_period"
                                        value="{{ request('end_period') }}">
                                </div>
                                <div class="form-group col-md-3 d-flex align-items-end" style="gap: 12px;">
                                    <button class="btn btn-success" type="submit">
                                        <i class="material-icons align-middle">sort</i> Filtrer
                                    </button>
                                    <a href="{{ route('billing2') }}" class="btn btn-secondary">Réinitialiser</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



                <!-- Tableau -->

                <div class="card">
                    <div class="card-header" style="background-color:#F5F5DC;">
                        <h4>Le résultat du filtre</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2 || getUserType()->type_user == 1)
                                <table class="table table-striped table-hover category" id="tableExpor" style="width:100%;">
                                @else
                                    <table class="table table-striped table-hover category" id="save-stage"
                                        style="width:100%;">
                            @endif
@php
    // Forcer la même valeur par défaut que dans le contrôleur
    $viewType = request('view_type') ?: 'daily_carrier';
@endphp

<thead>
    <tr>
        <th class="recherche">Direction</th>

        @if (in_array($viewType, ['daily_carrier', 'monthly_carrier', 'monthly_details']))
            <th class="recherche">Opérateur</th>
        @endif

        @if (request('show_net_name') == '1' || request('orig_net_name') || request('dest_net_name'))
            <th class="recherche">Réseau origine</th>
            <th class="recherche">Réseau destination</th>
        @endif

        @if (request('show_country_name') == '1' || request('orig_country_name') || request('dest_country_name'))
            <th class="recherche">Pays origine</th>
            <th class="recherche">Pays destination</th>
        @endif

        <th class="recherche">Période</th>
        <th class="recherche">Minutes</th>
        <th class="recherche">Montant CFA</th>
    </tr>
</thead>

<tbody>
    @foreach ($data as $row)
        <tr>
            <td>{{ $row->direction }}</td>

            @if (in_array($viewType, ['daily_carrier', 'monthly_carrier', 'monthly_details']))
                <td>{{ $row->carrier_name }}</td>
            @endif

            @if (request('show_net_name') == '1' || request('orig_net_name') || request('dest_net_name'))
                <td>{{ $row->orig_net_name }}</td>
                <td>{{ $row->dest_net_name }}</td>
            @endif

            @if (request('show_country_name') == '1' || request('orig_country_name') || request('dest_country_name'))
                <td>{{ $row->orig_country_name }}</td>
                <td>{{ $row->dest_country_name }}</td>
            @endif

            <td>{{ $row->period }}</td>
            <td>{{ number_format($row->total_minutes, 0, ',', ' ') }}</td>
            <td>{{ number_format($row->total_amount, 0, ',', ' ') }}</td>
        </tr>
    @endforeach
</tbody>



                            </table>
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

                new SlimSelect({
                    select: '.demo1'
                });

                new SlimSelect({
                    select: '.demo2'
                });
                new SlimSelect({
                    select: '.demo3'
                });
                new SlimSelect({
                    select: '.demo4'
                });
                new SlimSelect({
                    select: '.demo5'
                });
            });

            $(document).ready(function() {
                // Ajoute un champ de recherche à chaque colonne du header
                $('#tableExpor thead tr').clone(true).appendTo('#tableExpor thead').addClass("rech");
                $('#tableExpor thead .rech th').each(function(i) {
                    var title = $(this).text();
                    $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                        '" />');

                    $('input', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table
                                .column(i)
                                .search(this.value)
                                .draw();
                        }
                    });
                });

                var table = $('#tableExpor').DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,
                    dom: 'Bfrtip',
                    footer: true,
                    buttons: [{
                            extend: 'excelHtml5',
                            footer: true,
                            filename: 'STATISTIQUES_OPERATEURS',
                            title: 'STATISTIQUES DE FACTURATION',
                            exportOptions: {
                                columns: ':visible',
                                format: {
                                    body: function(data) {
                                        return typeof data === 'string' ?
                                            data.replace(/\s/g, '').replace(',', '.') :
                                            data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            footer: true,
                            exportOptions: {
                                columns: ':visible',
                                format: {
                                    body: function(data) {
                                        return typeof data === 'string' ?
                                            data.replace(/\s/g, '').replace(',', '.') :
                                            data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            footer: true,
                            filename: 'STATISTIQUES_OPERATEURS',
                            title: 'STATISTIQUES DE FACTURATION',
                            exportOptions: {
                                columns: ':visible',
                                format: {
                                    body: function(data) {
                                        return typeof data === 'string' ?
                                            data.replace(/\s/g, '').replace(',', '.') :
                                            data;
                                    }
                                }
                            }
                        }
                    ],
                    "language": {
                        "emptyTable": "Aucune donnée disponible dans le tableau",
                        "lengthMenu": "Afficher _MENU_ éléments",
                        "loadingRecords": "Chargement...",
                        "processing": "Traitement...",
                        "zeroRecords": "Aucun élément correspondant trouvé",
                        "paginate": {
                            "first": "Premier",
                            "last": "Dernier",
                            "next": "Suivant",
                            "previous": "Précédent"
                        },
                        "search": "Rechercher:",
                        "searchPlaceholder": "...",
                        "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                        "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                        "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                        "thousands": "."
                    }
                });
            });
        </script>
    @endsection
