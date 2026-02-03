@extends('template.principal_tamplate')

@section('title', 'Liste des opérateurs')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Liste des opérateurs</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header" style="background-color:#F5F5DC">
                <h6>
                    <i class="fas fa-filter card-icon col-green font-30 p-r-30"></i>
                    Filtrer les opérateurs par période
                </h6>
            </div>
            <div class="padding-10">
                <form action="{{ route('liste_operator_reporting') }}" method="GET">
                    <div class="form-row filtre_form">
                        <div class="form-group col-md-3">
                            <label for="start_period">Période début :</label>
                            <input type="month" id="start_period" name="start_period" class="form-control"
                                   value="{{ request('start_period') ?? '' }}" min="2020-01" max="{{ now()->format('Y-m') }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="end_period">Période fin :</label>
                            <input type="month" id="end_period" name="end_period" class="form-control"
                                   value="{{ request('end_period') ?? '' }}" min="2020-01" max="{{ now()->format('Y-m') }}">
                        </div>
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau RECOUVREMENTS PAR OPÉRATEUR -->
        <div class="card mt-4">
            <div class="card-header" style="background-color:#F5F5DC ">
                <h4>RECOUVREMENTS PAR OPÉRATEUR</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom de l'opérateur</th>
                                <th>Recouvrement (FCFA)</th>
                                <th>Recouvrement (EUR)</th>
                                <th>Devise</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($operators as $index => $operator)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-success text-white" href="{{ url('ope_dashboard/'.$operator->id) }}">
                                            {{ $operator->name }}
                                        </a>
                                    </td>
                                    <td><strong class="text-info">{{ number_format($operator->recouvrement_total, 2, ',', ' ') }}</strong></td>
                                    <td><strong>{{ number_format($operator->recouvrement_total / 655.957, 2, ',', ' ') }}</strong></td>
                                    <td>{{ $operator->currency }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-dark" href="{{ url('ope_dashboard/'.$operator->id) }}" title="Voir les détails">
                                            <i class="fas fa-eye text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun opérateur trouvé pour la période sélectionnée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr style="background-color: #f9f9f9; font-weight: bold;">
                                <td colspan="2" class="text-right">TOTAL GLOBAL</td>
                                <td class="text-info">{{ number_format($total_global_recouvrement_by_operator, 2, ',', ' ') }}</td>
                                <td>{{ number_format($total_global_recouvrement_by_operator / 655.957, 2, ',', ' ') }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tableau REPORTING RECOUVREMENTS MENSUELS -->
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>REPORTING RECOUVREMENTS DES INTERCONNEXIONS INTERNATIONALES (PAR MOIS)</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse-recouvrement" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse-recouvrement">
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableRecouvrement"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue" class="recherche">MOIS</th>
                                                <th style="color: aliceblue" class="recherche">ANNÉE</th>
                                                <th style="color: aliceblue" class="recherche">RECOUVREMENT (FCFA)</th>
                                                <th style="color: aliceblue" class="recherche">RECOUVREMENT (EUR)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $groupedByMonth = [];
                                                $totalGlobal = 0;

                                                // Regrouper par mois et calculer les totaux (clé : timestamp du 1er jour du mois)
                                                foreach ($recouvrement_monthly as $recouvrement) {
                                                    $year = $recouvrement->year;
                                                    $month = str_pad($recouvrement->month, 2, '0', STR_PAD_LEFT);
                                                    $tsKey = strtotime($year . '-' . $month . '-01');
                                                    if (!isset($groupedByMonth[$tsKey])) {
                                                        $groupedByMonth[$tsKey] = [
                                                            'year' => $year,
                                                            'month' => (int)$recouvrement->month,
                                                            'total' => 0
                                                        ];
                                                    }
                                                    $groupedByMonth[$tsKey]['total'] += $recouvrement->total_incoming_payement;
                                                    $totalGlobal += $recouvrement->total_incoming_payement;
                                                }

                                                // Trier par timestamp (décroissant) : plus récent d'abord
                                                krsort($groupedByMonth);
                                            @endphp

                                            @forelse ($groupedByMonth as $monthKey => $monthData)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::createFromDate($monthData['year'], $monthData['month'], 1)->translatedFormat('F') }}</td>
                                                    <td>{{ $monthData['year'] }}</td>
                                                    <td>{{ number_format($monthData['total'], 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($monthData['total'] / 655.957, 2, ',', ' ') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Aucun recouvrement trouvé pour la période sélectionnée.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                <td colspan="2" class="text-right">TOTAL GLOBAL</td>
                                                <td>{{ number_format($totalGlobal, 2, ',', ' ') }}</td>
                                                <td>{{ number_format($totalGlobal / 655.957, 2, ',', ' ') }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
      $(document).ready(function() {
        // Ajout des champs de recherche
        $('#tableExpor thead tr .recherche').clone(true).appendTo('#tableExpor thead').addClass("rech");
        $('#tableExpor thead .rech').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                '" />');
            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        });



        var table = $('#tableExpor').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'Bfrtip',
            footer: true, // Nécessaire pour inclure le footer dans les exports
            buttons: [{
                    extend: 'excelHtml5',
                    footer: true,
                    filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS ',
                    title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS ',
                    exportOptions: {
                        columns: ':not(:last-child)', // Exclut la colonne ACTION
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text(); // Supprime HTML
                                return cleanText.replace(/\s/g, '').replace(',',
                                    '.'); // Nettoyage nombre
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        // Parcourt chaque cellule
                        $('row c[r]', sheet).each(function() {
                            var cell = $(this);
                            var value = $('v', cell).text();

                            // Si valeur numérique avec virgule ou espace
                            if (value && /^[\d\s,.]+$/.test(value)) {
                                var cleaned = value.replace(/\s/g, '').replace(',',
                                    '.');
                                $('v', cell).text(cleaned);
                                cell.attr('t', 'n'); // force le type 'number'
                            }
                        });
                    }
                },
                {
                    extend: 'csvHtml5',
                    footer: true,
                    filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                    title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                    title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS' ,
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    }
                }
            ]
        });

        // Tableau des recouvrements mensuels
        // Ajout des champs de recherche
        $('#tableRecouvrement thead tr .recherche').clone(true).appendTo('#tableRecouvrement thead').addClass("rech");
        $('#tableRecouvrement thead .rech').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                '" />');
            $('input', this).on('keyup change', function() {
                if (tableRecouvrement.column(i).search() !== this.value) {
                    tableRecouvrement.column(i).search(this.value).draw();
                }
            });
        });

        var tableRecouvrement = $('#tableRecouvrement').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'Bfrtip',
            footer: true,
            buttons: [{
                    extend: 'excelHtml5',
                    footer: true,
                    filename: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    title: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    exportOptions: {
                        columns: ':all',
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r]', sheet).each(function() {
                            var cell = $(this);
                            var value = $('v', cell).text();
                            if (value && /^[\d\s,.]+$/.test(value)) {
                                var cleaned = value.replace(/\s/g, '').replace(',', '.');
                                $('v', cell).text(cleaned);
                                cell.attr('t', 'n');
                            }
                        });
                    }
                },
                {
                    extend: 'csvHtml5',
                    footer: true,
                    filename: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    title: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    exportOptions: {
                        columns: ':all',
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    footer: true,
                    filename: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    title: 'REPORTING RECOUVREMENTS DES INTERCONNEXIONS',
                    exportOptions: {
                        columns: ':all',
                        format: {
                            body: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            },
                            footer: function(data, row, column, node) {
                                var cleanText = $('<div>').html(data).text();
                                return cleanText.replace(/\s/g, '').replace(',', '.');
                            }
                        }
                    }
                }
            ]
        });
    });

        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Voulez-vous vraiment supprimer cet opérateur?',
                text: 'Cet opérateur sera supprimé définitivement de cette liste!',
                icon: 'warning',
                buttons: ["Annuler", "Oui!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });


        new SlimSelect({
            select: '.demo'
        })
    </script>
@stop
