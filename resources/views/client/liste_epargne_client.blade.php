@extends('template.principal_tamplate')
@section('title','Liste des comptes épargnes')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>

        <li class="breadcrumb-item active" aria-current="page">Liste des comptes épargnes</li>

    </ol>



</nav>
@stop

@section('content')

<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">

                @if ($gestionaire_compte != null)
                <div class="mt-3  col-12 text-center  m-auto">
                    <img style="height: 200px; width: 200px; margin-bottom: 10px" alt="image"
                        src="{{ $gestionaire_compte->avatar }}""
                     class="  rounded-circle author-box-picture">
                    <div class="clearfix"></div>
                    <div class="author-box-name mb-3">
                        <h5>{{ $gestionaire_compte->last_name }} {{ $gestionaire_compte->first_name }}
                        </h5>
                    </div>
                </div>

                <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2"
                        style="background-color:#E5E8E8;  border-radius: 18px;">
                        <div class="row">
                            <div class="col-md-6 col-6 b-r">
                                <strong>Nom de votre agence</strong>
                                <br>
                                <p  class="text-muted">
                                    {{ $agence->nom }}</p>
                            </div>
                            <div class="col-md-6 col-6 b-r">
                                <strong>Nom du gestionaire de compte</strong>
                                <br>
                                <p class="text-muted">
                                    {{ $gestionaire_compte->last_name }} {{ $gestionaire_compte->first_name }}                                        </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-6 b-r">
                                <strong>Telephone de votre agence</strong>
                                <br>
                                <p class="text-muted">{{ $agence->tel1 }} </p>
                            </div>
                            <div class="col-md-6 col-6 b-r">
                                <strong>Telephone de gestionnaire de compte</strong>
                                <br>
                                <p class="text-muted">{{ $gestionaire_compte->tel }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-6 b-r">
                                <strong>Adresse de votre agence</strong>
                                <br>
                                <p class="text-muted">{{ $agence->adresse }} </p>
                            </div>
                            <div class="col-md-6 col-6 b-r">
                                <strong>Poste de votre gestionnaire de compte</strong>
                                <br>
                                @if($gestionaire_compte->type_user = 2)
                                <p class="text-muted"> Chef de l'agence</p>
                                @else
                                <p class="text-muted"> Agent </p>
                                @endif
                            </div>
                        </div>





                    </div>






                </div>


            @endif


                <div class="card-header">
                    <h4>Liste des mes comptes épargnes</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                            <thead>
                                <tr>

                                    <th class="recherche">Numéros de compte</th>
                                    <th class="recherche">Client</th>
                                    <th class="recherche">Agence</th>
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
                                    <td>{{ $compte->agence->nom }}</td>

                                    <td>{{ $compte->solde_actuelle}}Fr CFA</td>

                                    <td>{{ $compte->created_at}}</td>

                                    <td style="width:10%">
                                        <span data-toggle="tooltip" data-placement="top" title="Voir les informations du compte en détail">
                                            <a class=" mb-2 btn btn-sm btn-success" data-toggle="modal" data-target="{{'#voirEpargneModal'.$compte->id}}">
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
</div>


@stop

