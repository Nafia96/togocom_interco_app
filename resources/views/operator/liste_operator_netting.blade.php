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
                <form action="{{ route('liste_operator_netting') }}" method="GET">
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
                        <div class="form-group col-md-3 d-flex align-items-end justify-content-end">
                            <a href="{{ route('add_operator') }}" class="btn btn-info">Ajouter un opérateur</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header" style="background-color:#F5F5DC ">
                <h4>Liste des opérateurs (Créances, Dettes, Netting)</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Nom de l'opérateur</th>
                                <th>Créance</th>
                                <th>Encaissement</th>
                                <th>Décaissement</th>
                                <th>Dette</th>
                                <th>Netting</th>
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
                                    <td>{{ number_format($operator->total_receivable, 2, ',', ' ') }}</td>
                                    <td>{{ number_format($operator->encaissement, 2, ',', ' ') }}</td>
                                    <td>{{ number_format($operator->decaissement, 2, ',', ' ') }}</td>
                                    <td>{{ number_format($operator->total_debt, 2, ',', ' ') }}</td>
                                    <td>{{ number_format($operator->netting, 2, ',', ' ') }}</td>
                                    <td>{{ $operator->currency }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-dark" href="{{ url('ope_dashboard/'.$operator->id) }}" title="Voir les détails">
                                            <i class="fas fa-eye text-white"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Aucun opérateur trouvé pour la période sélectionnée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor thead tr .recherche').clone(true).appendTo('#tableExpor thead').addClass("rech");
            $('#tableExpor thead .rech ').each(function(i) {
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
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
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
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
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
