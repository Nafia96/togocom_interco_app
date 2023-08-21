@extends('template.principal_tamplate')
@section('title', 'Création d\'un compte dépôt')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Facture</li>
        </ol>
    </nav>
@stop

@section('content')

    <section class="section">
        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Facture </h2>
                                <div class="invoice-number">Opération # {{ $operation->id }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    @if ($operation->compte->account_number != 1)
                                        <address>

                                            <strong>Agence:</strong><br>
                                            {{ $operation->agence->nom }} <br>
                                            {{ $operation->agence->adresse }},{{ $operation->agence->quartier }}<br>
                                            {{ $operation->agence->tel1 }},<br>
                                            {{ $operation->agence->email }},<br>
                                            {{ $operation->agence->ville }}, Togo
                                        </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Client:</strong><br>
                                        {{ $operation->client->user->first_name . ' ' . $operation->client->user->last_name }}
                                        <br>
                                        {{ $operation->client->user->tel, $operation->client->user->tel2 }} <br>
                                        {{ $operation->client->user->email }} <br>
                                        {{ $operation->client->user->ville }}, Togo
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Servi par:</strong><br>
                                        {{ $operation->user_add_by->first_name . ' ' . $operation->user_add_by->last_name }}<br>
                                        {{ $operation->user_add_by->tel }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Date d'opération:</strong><br>
                                        {{ $operation->created_at }} <br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Type d'opération</div>
                            <p class="section-lead">{{ $operation->type_operation }}</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th data-width="40">#</th>
                                        <th>Numéros de compte</th>
                                        <th class="text-center">Entré</th>
                                        <th class="text-center">Sortie</th>
                                        <th class="text-right">Solde restant </th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>{{ $operation->compte->account_number }}</td>
                                        <td class="text-center"> {{ $operation->entre }}Fr cfa </td>
                                        <td class="text-center">{{ $operation->sortie }}Fr cfa </td>
                                        <td class="text-right">{{ $operation->solde_restant }}Fr cfa </td>
                                    </tr>

                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="section-title">Signature de l'agent:</div>
                                    <p class="section-lead"> {{ $operation->user_add_by->first_name . ' ' . $operation->user_add_by->last_name }}<br>
                                    </p>

                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Nouveau solde sur le compte</div>
                                        <div class="invoice-detail-value">{{ $operation->solde_restant }}Fr Cfa</div>
                                    </div>

                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Signature du client</div>
                                    </div>

                                    @endif


                                    @if ($operation->compte->account_number == 1)
                                    <address>

                                        <strong>Agence:</strong><br>
                                        {{ $operation->agence->nom }} <br>
                                        {{ $operation->agence->adresse }},{{ $operation->agence->quartier }}<br>
                                        {{ $operation->agence->tel1 }},<br>
                                        {{ $operation->agence->email }},<br>
                                        {{ $operation->agence->ville }}, Togo
                                    </address>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <address>
                                    <strong>Agence Principal:</strong><br>
                                    {{ $operation->client->user->first_name . ' ' . $operation->client->user->last_name }}
                                    <br>
                                    {{ $operation->client->user->tel, $operation->client->user->tel2 }} <br>
                                    {{ $operation->client->user->email }} <br>
                                    {{ $operation->client->user->ville }}, Togo
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <address>
                                    <strong>Versement éffectué par:</strong><br>
                                    {{ $operation->user_add_by->first_name . ' ' . $operation->user_add_by->last_name }}<br>
                                    {{ $operation->user_add_by->first_name }}
                                </address>
                            </div>
                            <div class="col-md-6 text-md-right">
                                <address>
                                    <strong>Date d'opération:</strong><br>
                                    {{ $operation->created_at }} <br><br>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="section-title">Type d'opération</div>
                        <p class="section-lead">Versement</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tr>
                                    <th data-width="40">#</th>
                                    <th>Numéros de compte</th>
                                    <th class="text-center">Entré</th>
                                    <th class="text-center">Sortie</th>
                                    <th class="text-right">Nouveau solde Agence </th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>##########</td>
                                    <td class="text-center"> ######## </td>
                                    <td class="text-center">{{ $operation->sortie }}Fr cfa </td>
                                    <td class="text-right">{{ $operation->agence->solde_total }}Fr cfa </td>
                                </tr>

                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-8">
                                <div class="section-title">Signature de l'argence principal:</div>
                                <p class="section-lead">{{ $operation->client->user->first_name . ' ' . $operation->client->user->last_name }}
                                </p>

                            </div>
                            <div class="col-lg-4 text-right">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Nouveau solde sur le compte de l'agence</div>
                                    <div class="invoice-detail-value">{{ $operation->agence->solde_total }}Fr Cfa</div>
                                </div>

                                <hr class="mt-2 mb-2">
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Signature du chef d'agence</div>
                                </div>

                                @endif


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-md-right">
                    <div class="float-lg-left mb-lg-0 mb-3">
                        <a href="{{ route('agence_operations') }}">
                            <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i>
                                Annuler</button>

                        </a>
                    </div>
                    <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Imprimer</button>
                </div>
            </div>
        </div>
    </section>




@stop

@section('script')




    <script>
        < script >
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
    </script>

@stop
