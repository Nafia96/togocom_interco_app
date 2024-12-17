@extends('template.principal_tamplate')

@section('title','Accueil')


@section('content')




    <div class="row ">
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-green">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-award"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">Solde Tontine</h4>
                        <span>{{ $tontine_acutelle }} Fr cfa</span>

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
                        <span> {{$epargne_acutelle}}Fr cfa </span>



                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card l-bg-purple">
                <div class="card-statistic-3">
                    <div class="card-icon card-icon-large"><i class="fa fa-globe"></i></div>
                    <div class="card-content">
                        <h4 class="card-title">Solde retiré</h4>
                        <span>{{$solde_sortie}}Fr cfa</span>



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
                        <span>{{$solde_total_acutelle}}Fr cfa</span>



                    </div>
                </div>
            </div>
        </div>
    </div>







    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste de mes opérations de la journée</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableExpor" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="recherche">Date d'opération</th>
                                    <th class="recherche">Servi par:</th>
                                    <th class="recherche">Tel servant(e):</th>

                                    <th class="recherche">Type d'opération</th>
                                    <th class="recherche">Solde restant</th>
                                    <th class="recherche">Entre</th>
                                    <th class="recherche">Sortie</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($mes_operations as $operation)

                                @if ($operation->type_operation == 'benefice') @continue @endif

                                <tr>



                                    <td>{{ $operation->created_at }}</td>
                                    <td>{{ $operation->user_add_by->first_name . ' ' . $operation->user_add_by->last_name}} </td>
                                    <td>{{ $operation->user_add_by->tel}} </td>

                                    <td>{{ $operation->libelle_operation }}</td>
                                    <td>{{ $operation->solde_restant}}Fr CFA</td>
                                    <td>{{ $operation->entre}}Fr CFA</td>
                                    <td>{{ $operation->sortie}}Fr CFA</td>


                                    <td style="width:15%">
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







@endsection
