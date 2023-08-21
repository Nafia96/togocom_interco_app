@extends('template.principal_tamplate')
@section('title', "Statistique sur l'agence")

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Statistique d'un agent</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Tableau de bord de l'agent {{$user->first_name.' '.$user->last_name}}</h4>
        </div>
        <div class="card-body">

            <div class="row ">

                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-cyan">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-briefcase"></i></div>
                            <div class="card-content">
                                <h4 class="card-title"> Clients enregistrés</h4>
                                <span> {{ $mesClients }} clients</span>



                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-purple">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-globe"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Comptes crées</h4>
                                <span>{{ $nbr_comptes }} comptes</span>


                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-green">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-award"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Collecte du jours</h4>
                                <span>{{ $tontine_entre_today }} Fr cfa</span </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card l-bg-orange">
                        <div class="card-statistic-3">
                            <div class="card-icon card-icon-large"><i class="fa fa-money-bill-alt"></i></div>
                            <div class="card-content">
                                <h4 class="card-title">Toutes ses collectes</h4>
                                <span>{{ $solde_entre }}Fr cfa</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Liste des clients en sa charge</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                                <thead>
                                    <tr>

                                        <th class="recherche">Nom & Prenom</th>
                                        <th class="recherche">Email</th>
                                        <th class="recherche">Tel</th>
                                        <th class="recherche">zone de collecte</th>
                                        <th class="recherche">Agence</th>
                                        <th class="recherche">Date de d'ajout</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($clients as $client)
                                    <tr>

                                        <td>{{ $client->user->last_name.' '.$client->user->first_name}} </td>
                                        <td>{{ $client->user->email }}</td>
                                        <td>{{ $client->user->tel}}</td>
                                        <td>{{ $client->adresse_collecte }}</td>
                                        <td>{{ $client->agence->nom}}</td>
                                        <td>{{ $client->user->created_at}}</td>

                                        <td style="width:16%">
                                            <span data-toggle="tooltip" data-placement="top" title="Voir les informations du client en détail">
                                                <a class=" mb-2 btn btn-sm btn-success" data-toggle="modal" data-target="{{'#voirClientModal'.$client->id}}">
                                                    <i class="fas fa-eye text-white "> </i>
                                                </a>
                                            </span>


                                            <span data-toggle="tooltip" data-placement="top" title="Modifier les informations du client">
                                                <a class=" mb-2 btn btn-sm btn-info" href="{{url('update_client/'.$client->id)}}">
                                                    <i class="fas fas fa-user-cog text-white "> </i>
                                                </a>
                                            </span>


                                            @if (getUserType()->type_user == 2)



                                            <span data-toggle="tooltip" data-placement="top" title="Supprimer cet client">
                                                <a class=" delete-confirm mb-2 btn btn-sm btn-danger" href="/delete_client/{{ $client->id }}">
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Liste des comptes tontines en sa charge</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                                    <thead>
                                        <tr>

                                            <th class="recherche">Numéros de compte</th>
                                            <th class="recherche">Client</th>
                                            <th class="recherche">Taux cotisation</th>
                                            <th class="recherche">Solde</th>
                                            <th class="recherche">Jours cotisé</th>
                                            <th class="recherche">Mois cotisé</th>
                                            <th class="recherche">Date de création</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($comptes as $compte)
                                            <tr>
                                                <td>{{ $compte->account_number }}</td>
                                                <td>{{ $compte->client->user->last_name . ' ' . $compte->client->user->first_name }}
                                                </td>

                                                <td>{{ $compte->taux_cotisation }}Fr CFA</td>
                                                <td>{{ $compte->solde_actuelle }}Fr CFA</td>

                                                @php
                                                    if ($compte->nombre_de_retrait != 0) {
                                                        # code...
                                                        $nbr_mois = (int) ($compte->nombre_de_retrait / 31);

                                                        if ($compte->nombre_de_retrait >= 31) {
                                                            # code...
                                                            $nbr_jours = $compte->nombre_de_retrait - $nbr_mois * 31;
                                                        } else {
                                                            # code...
                                                            $nbr_jours = $compte->nombre_de_retrait;
                                                        }
                                                    }

                                                @endphp
                                                @if ($compte->nombre_de_retrait == 0)

                                                    <td>0 jours</td>
                                                @else
                                                    <td>{{ $nbr_jours }} jours</td>
                                                @endif

                                                @if ($compte->nombre_de_retrait == 0)

                                                    <td>0 mois</td>

                                                @else
                                                    <td>{{ $nbr_mois }} mois</td>
                                                @endif

                                                <td>{{ $compte->created_at }}</td>

                                                <td style="width:8%">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="Voir les informations du compte en détail">
                                                        <a class=" mb-2 btn btn-sm btn-success" data-toggle="modal"
                                                            data-target="{{ '#voirTontineModal' . $compte->id }}">
                                                            <i class="fas fa-eye text-white "> </i>
                                                        </a>
                                                    </span>









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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Liste de ses opérations de la journée</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th class="recherche">Date d'opération</th>
                                            <th class="recherche">Client:</th>
                                            <th class="recherche">Tel Client:</th>

                                            <th class="recherche">Type d'opération</th>
                                            <th class="recherche">Entre</th>
                                            <th class="recherche">Sortie</th>
                                            <th class="recherche">Solde</th>
                                            <th class="recherche">Benefice</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($operations as $operation)
                                        <tr>



                                            <td>{{ $operation->created_at }}</td>
                                            <td>{{ $operation->client->user->first_name . ' ' . $operation->client->user->last_name}} </td>
                                            <td>{{ $operation->client->user->tel}} </td>

                                            <td>{{ $operation->type_operation }}</td>
                                            <td>{{ $operation->entre}}Fr CFA</td>
                                            <td>{{ $operation->sortie}}Fr CFA</td>
                                            <td>{{ $operation->solde_restant}}Fr CFA</td>
                                            <td>{{ $operation->tous_entre - $operation->entre}}Fr CFA</td>



                                            <td style="width:10%">
                                                <span data-toggle="tooltip" data-placement="top" title="Voir la facture de l'operation">
                                                    <a class=" mb-2 btn btn-sm btn-info" href="{{url('facture/'.$operation->id)}}">
                                                        <i class="fas far fas fa-copy text-white"> </i>
                                                    </a>
                                                </span>





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
    </div>


@stop
