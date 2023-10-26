@extends('template.principal_tamplate')
@section('title', 'Liste des créances et dettes')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>

            <li class="breadcrumb-item active" aria-current="page">Liste de toutes les créances et dettes </li>
            <div class="d-flex justify-content-end container-fluid mt-n3 ">
                <a href="{{ route('liste_operator') }}" class="btn btn-primary">Liste des opérateurs</a>
            </div>
        </ol>



    </nav>
@stop

@section('content')

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste de toutes les créances et dettes </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2 || getUserType()->type_user == 1)
                            <table class="table table-striped table-hover category" id="tableExpor" style="width:100%;">
                            @else
                                <table class="table table-striped table-hover category" id="save-stage"
                                    style="width:100%;">
                        @endif                                <thead>
                                    <tr>
                                        <th class="recherche">N°</th>
                                        <th class="recherche">OPÉRATEUR</th>
                                        <th class="recherche">PRESTATIONS</th>
                                        <th class="recherche">PERIODES</th>
                                        <th class="recherche">CREANCES</th>
                                        <th class="recherche">ENCAISSEMENT</th>
                                        <th class="recherche">DETTES</th>
                                        <th class="recherche">DECAISSEMENT</th>
                                        <th class="recherche">SOLDE</th>
                                        <th class="recherche">DATE D'AJOUT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $n = 1; ?>

                                    @foreach ($resums as $resum)
                                        <tr>
                                            <td>{{ $n }} </td>
                                            <td>{{ $resum->operator->name }} </td>
                                            <td style="width:18%">{{ $resum->service }} </td>

                                            @if ($resum->period == null)
                                                <td>---------</td>
                                            @endif

                                            @if ($resum->period != null)
                                                <td>{{ periodePrint($resum->period) }}</td>
                                            @endif

                                            @if ($resum->receivable == null)
                                                <td>0</td>
                                            @endif

                                            @if ($resum->receivable != null)
                                                <td>{{ number_format($resum->receivable) . '  ' . $resum->operator->currency }}</td>
                                            @endif


                                            @if ($resum->incoming_payement == null)
                                                <td>0</td>
                                            @endif

                                            @if ($resum->incoming_payement != null)
                                                <td>{{ number_format($resum->incoming_payement) . '  ' . $resum->operator->currency }}
                                                </td>
                                            @endif


                                            @if ($resum->operation2->invoice->invoice_type == 'estimated')
                                                @if ($resum->debt == null)
                                                    <td>0</td>
                                                @endif

                                                @if ($resum->debt != null && $resum->service != 'Facture de service voix')
                                                    <td> {{ number_format($resum->debt) . '  ' . $resum->operator->currency }} </td>
                                                @endif

                                                @if ($resum->debt != null && $resum->service == 'Facture de service voix')
                                                    <td style="background-color: #fcca29;">



                                                        <div style="display:block;" data-toggle="modal"
                                                            data-target="{{ '#update_invoiceModal' . $resum->id }}">
                                                            {{ number_format($resum->debt) . '  ' . $resum->operator->currency }}
                                                        </div>


                                                    </td>
                                                @endif
                                            @endif

                                            @if ($resum->operation2->invoice->invoice_type != 'estimated')
                                                @if ($resum->debt == null)
                                                    <td>0</td>
                                                @endif

                                                @if ($resum->debt != null)
                                                    <td>{{ number_format($resum->debt) . '  ' . $resum->operator->currency }} </td>
                                                @endif
                                            @endif


                                            @if ($resum->payout == null)
                                                <td>0</td>
                                            @endif

                                            @if ($resum->payout != null)
                                                <td>{{ number_format($resum->payout) . '  ' . $resum->operator->currency }}
                                            @endif

                                            <td>{{ number_format($resum->netting) . '  ' . $resum->operator->currency }}</td>
                                            <td>{{ $resum->created_at }}</td>



                                         
                                        </tr>
                                        <?php $n = $n + 1; ?>
                                    @endforeach



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop

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
                title: 'Voulez-vous vraiment annuler cette opération?',
                text: 'Tout ce qui concerne cette opération va être supprimé',
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
