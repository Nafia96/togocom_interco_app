@extends('template.principal_tamplate')
@section('title','Liste des comptes tontines')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>

        <li class="breadcrumb-item active" aria-current="page">Liste des operations</li>

    </ol>



</nav>
@stop

@section('content')

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste de tous les operations</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="recherche">Date d'opération</th>
                                    <th class="recherche">Compte</th>
                                    <th class="recherche">Client</th>

                                    <th class="recherche">Type d'opération</th>
                                    <th class="recherche">Entre</th>
                                    <th class="recherche">Sortie</th>
                                    <th class="recherche">Bénefice</th>
                                    <th class="recherche">Nouveau solde </th>


                                    <th >Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($operations as $operation)

                                @if($operation->compte->account_number == 1)

                                @if (   session('type_user')!=1)


                                <td>{{ $operation->created_at }}</td>
                                <td>#########</td>
                                <td>Agence principal </td>

                                <td>Versement </td>
                                <td>#########</td>
                                <td>{{ $operation->versement}}Fr cfa</td>
                                <td>#########</td>
                                <td>{{ $operation->solde_restant}}Fr cfa</td>

                                @else

                                <td>{{ $operation->created_at }}</td>
                                <td>{{ $operation->compte->account_number }}</td>
                                <td>{{ $operation->client->user->last_name.' '.$operation->client->user->first_name}} </td>

                                <td>{{ $operation->libelle_operation }}</td>
                                <td>{{ $operation->entre}}Fr CFA</td>
                                <td>{{ $operation->sortie}}Fr CFA</td>
                                <td>{{ $operation->benefice}}Fr CFA</td>

                                <td>{{ $operation->solde_restant}}Fr CFA</td>

                                @endif


                                <td>
                                    <span data-toggle="tooltip" data-placement="top" title="Génerer une facture de l'operation">
                                        <a class=" mb-2 btn btn-sm btn-info" href="{{url('facture/'.$operation->id)}}">
                                            <i class="fas far fas fa-copy text-white"> </i>
                                        </a>
                                    </span>

                                    @if (   session('type_user')==1)

                                    <span data-toggle="tooltip" data-placement="top" title="Annuler opération">
                                        <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="{{url('delete_operation/'.$operation->id)}}">
                                            <i class="fas far fa-times-circle text-white"> </i>
                                        </a>
                                    </span>


                                    @endif


                                 </td>


                                @endif

                                @if($operation->compte->account_number != 1)
                                <tr>
                                    <td>{{ $operation->created_at }}</td>
                                    <td>{{ $operation->compte->account_number }}</td>
                                    <td>{{ $operation->client->user->last_name.' '.$operation->client->user->first_name}} </td>

                                    <td>{{ $operation->libelle_operation }}</td>
                                    <td>{{ $operation->entre}}Fr CFA</td>
                                    <td>{{ $operation->sortie}}Fr CFA</td>
                                    <td>{{ $operation->benefice}}Fr CFA</td>

                                    <td>{{ $operation->solde_restant}}Fr CFA</td>



                                    <td style="width:10%">

                                        <span data-toggle="tooltip" data-placement="top" title="Génerer une facture de l'operation">
                                            <a class=" mb-2 btn btn-sm btn-info" href="{{url('facture/'.$operation->id)}}">
                                                <i class="fas far fas fa-copy text-white"> </i>
                                            </a>
                                        </span>




                                        @if (   session('type_user')==1)

                                        <span data-toggle="tooltip" data-placement="top" title="Annuler opération">
                                            <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="{{url('delete_operation/'.$operation->id)}}">
                                                <i class="fas far fa-times-circle text-white"> </i>
                                            </a>
                                        </span>


                                        @endif










                                    </td>
                                @endif





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
        title: 'Voulez-vous vraiment annuler cet operation?',
        text: 'Cet operation sera supprimer definitivement !',
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