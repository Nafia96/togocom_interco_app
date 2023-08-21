@extends('template.principal_tamplate')

@section('title', 'Accueil')

@section('content')

            <div class="row ">
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Clients</h5>
                                            <h2 class="mb-3 font-18"> {{ $mesClients }} </h2>

                                            <p class="mb-0"><span class="col-green">{{ $mesAgents }}</span>
                                                Agents</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/banner/1.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Entrées</h5>
                                            <h2 class="mb-3 font-18">{{ $entre }} fr Cfa</h2>
                                            <p class="mb-0"><span class="col-green">{{ $entre_today }} fr Cfa</span>
                                                Aujourd'hui</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/banner/3.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Retraits</h5>
                                            <h2 class="mb-3 font-18">{{ $sortie }} fr cfa</h2>
                                            <p class="mb-0"><span class="col-green">{{ $sortie_today }} fr Cfa
                                                </span>Aujourd'hui</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/banner/4.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-statistic-4">
                            <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-15">Comptes</h5>
                                            <h2 class="mb-3 font-18"> {{ $nbr_comptes }}</h2>


                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                        <div class="banner-img">
                                            <img src="assets/img/banner/2.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row ">
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-green">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-award"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Solde Tontine</h4>
                                <span>{{ $tontine_entre }} Fr cfa</span>



                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-purple" role="progressbar" data-width="0%" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <p class="mb-0 text-sm">
                                    <span class="mr-2"><i class="fa fa-arrow-up"></i>
                                    <span class="text-nowrap">{{$tontine_entre_today - $tontine_sortie_today}}Fr cfa Aujourd'hui</span>
                                </p>




                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-cyan">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-briefcase"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Solde Epargne</h4>
                                <span> {{$epargne_entre}}Fr cfa </span>



                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-orange" role="progressbar" data-width="0%" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="mr-2"><i class="fa fa-arrow-up"></i> </span>
                                    <span class="text-nowrap">{{$epargne_entre_today- $epargne_sortie_today - $epargne_benefice_today}}Fr cfa Aujourd'hui</span>
                                </p>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-purple">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-globe"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Solde carnet</h4>
                                <span>{{$solde_carnet}}Fr cfa</span>


                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-cyan" role="progressbar" data-width="0%" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="text-nowrap">{{$nbre_carnet}} carnet vendues </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-orange">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-money-bill-alt"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Solde </h4>
                                <span>{{$solde_agence}}Fr cfa</span>



                                <div class="progress mt-1 mb-1" data-height="8">
                                    <div class="progress-bar l-bg-green" role="progressbar" data-width="0%" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0 text-sm">
                                    <span class="text-nowrap">{{$versement}}Fr cfa déjà versé</span>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>







    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des opération de la journée</h4>
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
                                @if ($operation->type_operation == 'benefice') @continue @endif

                                @if($operation->compte->account_number == 1)
                                <td>{{ $operation->created_at }}</td>
                                <td>#########</td>
                                <td>Agence principal </td>

                                <td>Versement </td>
                                <td>#########</td>
                                <td>{{ $operation->versement}}Fr cfa</td>
                                <td>#########</td>
                                <td>{{ $operation->solde_restant}}Fr cfa</td>



                                <td>
                                    <span data-toggle="tooltip" data-placement="top" title="Génerer une facture de l'operation">
                                        <a class=" mb-2 btn btn-sm btn-info" href="{{url('facture/'.$operation->id)}}">
                                            <i class="fas far fas fa-copy text-white"> </i>
                                        </a>
                                    </span>


                                    <span data-toggle="tooltip" data-placement="top" title="Annuler opération">
                                        <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="{{url('delete_operation/'.$operation->id)}}">
                                            <i class="fas far fa-times-circle text-white"> </i>
                                        </a>
                                    </span>

                                 </td>
                                @endif

                                @if($operation->compte->account_number != 1)
                                <tr>
                                    @if ($operation->type_operation == 'benefice') @continue @endif

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






                                        <span data-toggle="tooltip" data-placement="top" title="Annuler opération">
                                            <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="{{url('delete_operation/'.$operation->id)}}">
                                                <i class="fas far fa-times-circle text-white"> </i>
                                            </a>
                                        </span>












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







@endsection

@section('script')
<script>

$('.delete-confirm').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Voulez-vous vraiment annuler cet operation?',
        text: 'Toutes les transactions liées à cette operation sera supprimer definitivement !',
        icon: 'warning',
        buttons: ["Annuler", "Oui!"],
    }).then(function(value) {
        if (value) {
            window.location.href = url;
        }
    });
});
</script>

@stop
