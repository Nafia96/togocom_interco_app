@extends('template.principal_tamplate4')

@section('title', 'Roaming IOT Discount')

@section('content')

    <div class="row ">
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-green">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-award"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">Opérateur</h4>
                        <span>0</span>


                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-cyan">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-briefcase"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">Free Roaming </h4>
                        <span>0</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-purple">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-globe"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">Special IOT</h4>
                        <span>0</span>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-orange">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-money-bill-alt"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">IOT Standar</h4>
                        <span>0</span>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">


            <li class="breadcrumb-item " aria-current="page"><i class="fas fa-list"></i> Liste des tarifs End-user en FCFA TTC
            </li>


            <div class="d-flex justify-content-end container-fluid mt-n3">

                <span class="mx-1">

                    <a data-toggle="modal" data-target="{{ '#iot_dicount' }}"> <button type="button" style="color:#fbd305"
                            class=" btn btn-primary mx-1">+ AJOUTER UN TARIFS</button></a>

                </span>

            </div>

        </ol>



    </nav>



    <div class="card">
        <div class="card-header" style="background-color:#16346d">

            <h4 style="color: #fbd305"><i class="fas fa-file-invoice-dollar card-icon col-orange font-30 p-r-30"></i>  TARIFFS ROAMING OUT TOGOCOM ( VOIX ) Tarifs End-user en FCFA TTC </h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tableExpor2" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="recherche">N°</th>
                            <th class="recherche">Pays</th>
                            <th class="recherche">Opérateur</th>
                            <th class="recherche">Code opérateur</th>
                            <th class="recherche">Country Code</th>

                            <th class="recherche">MCC+MNC</th>
                            <th class="recherche">Mobile réseau</th>
                            <th class="recherche">Mobile réseau visité</th>
                            <th class="recherche">réseau filaire</th>
                            <th class="recherche">Vers CEDEAO</th>
                            <th class="recherche">Vers reste Afrique</th>
                            <th class="recherche">Vers Europ+Amérique nord</th>
                            <th class="recherche">Reste du monde</th>
                            <th class="recherche">Satelite</th>
                            <th class="recherche">Toute origine</th>
                            <th class="recherche">Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; ?>

                        @foreach ($itds as $itd)
                            <tr>
                                <td style="width:1%">{{ $n }} </td>
                                <td style="width:5%">{{ $itd->pays }} </td>
                                <td style="width:10%">{{ $itd->operateur }} </td>
                                <td style="width:10%">{{ $itd->code_operateur }} </td>
                                <td style="width:10%">{{ $itd->country_code }} </td>
                                <td style="width:10%">{{ $itd->mcc_mnc }} </td>
                                <td style="width:10%">{{ $itd->reseau_du_reseau }} </td>
                                <td style="width:10%">{{ $itd->mobile_autre_reseau }} </td>
                                <td style="width:10%">{{ $itd->mobile_filaire }} </td>
                                <td style="width:10%">{{ $itd->vers_cedeao }} </td>
                                <td style="width:10%">{{ $itd->vers_rest_europe_amerique }} </td>
                                <td style="width:10%">{{ $itd->reste_monde }} </td>
                                <td style="width:10%">{{ $itd->satellite }} </td>
                                <td style="width:10%">{{ $itd->origine }} </td>


                                @if ($itd->special_io == 1)
                                    <td style="width:10%">SPECIAL IOT</td>
                                @endif

                                @if ($itd->free_roaming == 1)
                                <td style="width:10%">FREE ROAMING</td>
                                 @endif

                                 @if ($itd->iot_standard == 1)
                                <td style="width:10%"> IOT STANDARD</td>
                                 @endif

                                <td style="width:10%">{{ $itd->updated_at }} </td>

                                <td style="width:10%">
                                    <span data-toggle="tooltip" data-placement="top" title="Supprimer" >
                                       <a class=" delete-confirm mb-2 btn btn-sm btn-danger"
                                                    href="/delete_operator/{{ $itd->id  }}">
                                                    <i class="fas far fa-times-circle text-white"> </i>
                                                </a>
                                    </span>
                                </td>
                            </tr>

                            <?php $n = $n + 1; ?>
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>


    </div>

@endsection


@section('script')
    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor2 thead tr .recherche').clone(true).appendTo('#tableExpor2 thead').addClass("rech");

            $('#tableExpor2 thead .rech').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title + '" />');

                // Apply the search on input change
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var myVariable = @json($itd->operator);

            var table = $('#tableExpor2').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copyHtml5', footer: true },
                    { extend: 'excelHtml5', footer: true },
                    { extend: 'csvHtml5', footer: true },
                    {
                        extend: 'pdfHtml5',
                        footer: true,
                        filename: 'IOT DISCOUNT DEALS BETWEEN TGOTC AND ROAMING PARTNERS',
                        title: 'IOT DISCOUNT DEALS BETWEEN TGOTC AND ROAMING PARTNERS'
                    }
                ],
                language: {
                    emptyTable: "Aucune donnée disponible dans le tableau",
                    lengthMenu: "Afficher _MENU_ éléments",
                    loadingRecords: "Chargement...",
                    processing: "Traitement...",
                    zeroRecords: "Aucun élément correspondant trouvé",
                    paginate: {
                        first: "Premier",
                        last: "Dernier",
                        next: "Suivant",
                        previous: "Précédent"
                    },
                    aria: {
                        sortAscending: ": activer pour trier la colonne par ordre croissant",
                        sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    },
                    select: {
                        rows: {
                            _: "%d lignes sélectionnées",
                            0: "Aucune ligne sélectionnée",
                            1: "1 ligne sélectionnée"
                        }
                    },
                    buttons: {
                        copy: "Copy",
                        excel: "Excel",
                        csv: "CSV",
                        pdf: "PDF",
                        pageLength: {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        }
                    },
                    info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    infoEmpty: "Affichage de 0 à 0 sur 0 éléments",
                    infoFiltered: "(filtrés de _MAX_ éléments au total)",
                    search: "Rechercher:",
                    searchPlaceholder: "Tapez pour rechercher...",
                    thousands: "."
                }
            });
        });

        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Voulez-vous vraiment supprimer cet IOT DISCOUNT ?',
                text: 'Cet IOT DISCOUNT sera supprimé définitivement de cette liste!',
                icon: 'warning',
                buttons: ["Annuler", "Oui!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection

