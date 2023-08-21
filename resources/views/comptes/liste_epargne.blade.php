@extends('template.principal_tamplate')
@section('title','Liste des comptes épargnes')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>

        <li class="breadcrumb-item active" aria-current="page">Liste des comptes épargnes</li>
        <div class="d-flex justify-content-end container-fluid mt-n3">
            <a href="{{route('add_epargne')}}" class="btn btn-primary ">Ajouter un compte </a>
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
                    <h4>Liste des comptes épargnes</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                            <thead>
                                <tr>

                                    <th class="recherche">Numéros de compte</th>
                                    <th class="recherche">Client</th>
                                    <th class="recherche">Solde</th>
                                    <th class="recherche">Date de création</th>
                                    <th >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($comptes as $compte)
                                <tr>
                                    <td>{{ $compte->account_number }}</td>
                                    <td>{{ $compte->client->user->last_name.' '.$compte->client->user->first_name}} </td>

                                    <td>{{ $compte->solde_actuelle}}Fr CFA</td>

                                    <td>{{ $compte->created_at}}</td>

                                    <td style="width:28%">
                                        <span data-toggle="tooltip" data-placement="top" title="Voir les informations du compte en détail">
                                            <a class=" mb-2 btn btn-sm btn-success" data-toggle="modal" data-target="{{'#voirEpargneModal'.$compte->id}}">
                                                <i class="fas fa-eye text-white "> </i>
                                            </a>
                                        </span>

                                        @if (getUserType()->type_user == 2)


                                        <span data-toggle="tooltip" data-placement="top" title="Modifier les informations du compte ">
                                            <a class=" mb-2 btn btn-sm btn-info" href="{{url('update_epargne/'.$compte->id)}}">
                                                <i class="fas fas fa-user-cog text-white "> </i>
                                            </a>
                                        </span>

                                        @endif

                                        @if (getUserType()->type_user == 2)

                                        <span data-toggle="tooltip" data-placement="top" title="Faire un depôt  sur le  comptee">
                                            <a class=" mb-2 btn btn-sm btn-primary" data-toggle="modal" data-target="{{'#cotisationEpargneModal'.$compte->id}}">
                                                <i class="fas fa-donate text-white "> </i>
                                            </a>
                                        </span>



                                        <span data-toggle="tooltip" data-placement="top" title="Faire un retrait sur le comptee">
                                            <a class=" mb-2 btn btn-sm btn-warning" data-toggle="modal" data-target="{{'#retraitModal'.$compte->id}}">
                                                <i class="fas fa-hand-holding-usd text-white "> </i>
                                            </a>
                                        </span>

                                        <span data-toggle="tooltip" data-placement="top" title="Tous les operations du compte">
                                            <a class=" mb-2 btn btn-sm btn-info" href="{{route('liste_operations',['id_client'=>$compte->client->id,'id_compte'=>$compte->id])}}">
                                                <i class="fas fa-layer-group text-white "> </i>
                                            </a>
                                        </span>

                                        @endif


                                        <span data-toggle="tooltip" data-placement="top"
                                            title="Ajouter les frais du carnet sur ce compte">
                                            <a class=" carnet-confirm mb-2 btn btn-sm btn-warning"
                                                href="{{ url('new_carnet/' . $compte->id) }}">
                                                <i class="fas far fas fa-fill text-white"> </i>
                                            </a>
                                        </span>






                                        @if (getUserType()->type_user == 2)


                                        <span data-toggle="tooltip" data-placement="top" title="Supprimer cet compte ">
                                            <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="{{url('delete_epargne/'.$compte->id)}}">
                                                <i class="fas far fa-times-circle text-white"> </i>
                                            </a>
                                        </span>


                                        @endif


                                    </td>
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


@stop

@section('script')
<script>
    $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor thead tr .recherche').clone(true).appendTo( '#tableExpor thead' ).addClass("rech");
            $('#tableExpor thead .rech ').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="form-control" placeholder="Rechercher '+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                        .column(i)
                        .search( this.value )
                        .draw();
                    }
                } );
            } );

            var table = $('#tableExpor').DataTable( {
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language":{
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
            } );
} );

$('.delete-confirm').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Voulez-vous vraiment supprimer cet compte ?',
        text: 'Toutes les opérations reliées à ce compte seront automatiquement supprimées!',
        icon: 'warning',
        buttons: ["Annuler", "Oui!"],
    }).then(function(value) {
        if (value) {
            window.location.href = url;
        }
    });
});

$('.carnet-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Voulez-vous vraiment créer un nouveau carnet pour cet compte ?',
                text: 'Vous devriez recupérer 300fr Cfa chez le client!',
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
