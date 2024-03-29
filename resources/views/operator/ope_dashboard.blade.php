@extends('template.principal_tamplate')

@section('title')

Dashboard opérateur

@endsection

@section('content')


@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-list"></i> Situation actuelle de
                {{ $operator->name }}</li>


            <div class="d-flex justify-content-end container-fluid mt-n3">
                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                    <a data-toggle="modal" data-target="{{ '#invoiceModal' . $operator->id }}"> <button type="button"
                            class=" btn btn-dark mx-1">+CREANCE</button></a>
                    <a data-toggle="modal" data-target="{{ '#addInvoiceModal' . $operator->id }}"><button type="button"
                            class="btn btn-warning mx-1">+DETTE</button></a>
                    <a data-toggle="modal" data-target="{{ '#settlementModal' . $operator->id }}"><button type="button"
                            class="btn btn-info mx-1">+REGLEMENT</button></a>
                @endif
                <span class="mx-1">
                    <a href="{{ route('liste_operator') }}" class="btn btn-primary ">TOUS LES OPÉRATEURS</a>

                </span>

            </div>

        </ol>



    </nav>
@stop
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-cyan">
                <i class="fas fa-donate"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">CREANCE AVEC {{ $operator->name }}
                    </h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    {{ number_format($receiv_invoices->sum('amount'), 2, ',', ' ') }} {{ $operator->currency }} <br>

                </div>

                <div style="font-size: 100%" class=" mb-4 card-body pull-center">
                    Total : {{ number_format($op_account->receivable, 2, ',', ' ') }} {{ $operator->currency }}
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-purple">
                <i class="fas fa-drafting-compass"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">NETTING AVEC {{ $operator->name }}
                    </h4>
                </div>
                <br>

                <div class="card-body pull-center">

                    <p class="mb-0 mt-0 font-15">Année en cours :<span class="col-green"> en faveur de
                            @if ($receiv_invoices->sum('amount') - $debt_invoices->sum('amount') > 0)
                                TOGOCOM
                            @else
                                {{ $operator->name }}
                            @endif
                        </span>
                    </p>



                    <h2 class="mb-0 font-20">
                        {{ number_format($receiv_invoices->sum('amount') - $debt_invoices->sum('amount'), 2, ',', ' ') }}
                        {{ $operator->currency }}</h2>


                </div>

                <div style="font-size: 100%" class="mb-4 card-body pull-center">

                    Total :
                    {{ number_format($op_account->receivable - $op_account->debt, 2, ',', ' ') }}
                    {{ $operator->currency }}
                    en faveur de
                    @if ($op_account->receivable - $op_account->debt > 0)
                        TOGOCOM
                    @else
                        {{ $operator->name }}
                    @endif

                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-icon shadow-primary bg-green">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">DETTE AVEC {{ $operator->name }}
                    </h4>
                </div>
                <div style="font-size: 140%" class="  card-body pull-center">
                    <br>

                    Année en cours : <br>
                    {{ number_format($debt_invoices->sum('amount'), 2, ',', ' ') }} {{ $operator->currency }} <br>

                </div>

                <div style="font-size: 100%" class=" mb-4 card-body pull-center">
                    Total : {{ number_format($op_account->debt, 2, ',', ' ') }} {{ $operator->currency }}

                </div>
            </div>

        </div>
    </div>
</div>
<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4>Informations en détail de {{ $operator->name }}</h4>
            <div class="card-header-action">
                <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                        class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="collapse hide" id="mycard-collapse">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-2 col-3 b-r" style="color:#03a04f; font-weight: bold; ">
                        <strong>Nom de l'opérateur</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->name }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Adresse</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">
                            {{ $operator->adresse . ' / ' . $operator->country }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Telephone</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->tel }}</p>
                    </div>
                    <div class="col-md-2 col-3 b-r" style="color:#03a04f; font-weight: bold; ">
                        <strong>Telephone 2 </strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->tel2 }}</p>
                    </div>
                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Email </strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->email }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Email 2</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->email2 }}</p>
                    </div>
                </div>



                <div class="row">

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Email 3</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->email3 }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>La devise</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->currency }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Opérateur de CEDEAO</strong>
                        <br>
                        @if ($operator->cedeao == 0)
                            <p style="color:#ec1f28; font-weight: bold; ">Non</p>
                        @endif

                        @if ($operator->cedeao == 1)
                            <p style="color:#ec1f28; font-weight: bold; ">Oui</p>
                        @endif

                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Créer le :</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->created_at }}</p>
                    </div>
                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Dernière modification</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->updated_at }}</p>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>RIB</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->rib }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>N°Compte</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->ope_account_number }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Adresse banque</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->banque_adresse }}</p>
                    </div>

                    <div class="col-md-2 col-3 b-r " style="color:#03a04f; font-weight: bold; ">
                        <strong>Code swift</strong>
                        <br>
                        <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->swift_code }}</p>
                    </div>



                </div>
                <a href="#" class="btn btn-primary">Télécharger </a>





            </div>
            <div class="card-footer">
                <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->description }}</p>

            </div>
        </div>
    </div>
</div>

<div class="col-12 col-sm-12 col-lg-12">

    <div class="col-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="padding-20">
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                            aria-selected="true">TOTAL DES CREANCES ET DETTES </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#settings" role="tab"
                            aria-selected="false">DETAILS DES CREANCES ET DETTES </a>
                    </li>
                </ul>
                <div class="tab-content tab-bordered" id="myTab3Content">
                    <div class="tab-pane fade show active" id="about" role="tabpanel"
                        aria-labelledby="home-tab2">

                        <div class="card-header">

                            <h4>LA SOMME PAR ANNEES DES CREANCES ET DETTES DE : {{ $operator->name }}</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse222" class="btn btn-icon btn-danger"
                                    href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse222">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tableExpor235"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="width:1px">N°</th>
                                                <th>ANNEE</th>
                                                <th>CREANCES ({{ $operator->currency }})</th>
                                                <th>ENCAISSEMENT ({{ $operator->currency }})</th>
                                                <th>DETTES ({{ $operator->currency }})</th>
                                                <th>DECAISSEMENT ({{ $operator->currency }})</th>
                                                <th> NETTING ({{ $operator->currency }})</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 1; ?>

                                            @foreach ($sum_resum as $resum)
                                                <tr>
                                                    <td style="width:1%">{{ $n }} </td>
                                                    <td style="width:10%">{{ $resum->year }} </td>


                                                    @if ($resum->total_receivable == null)
                                                        <td style="width:15%">0</td>
                                                    @endif

                                                    @if ($resum->total_receivable != null)
                                                        <td style="width:15%">
                                                            {{ number_format($resum->total_receivable, 2, ',', ' ') }}
                                                        </td>
                                                    @endif


                                                    @if ($resum->encaissement == null)
                                                        <td style="width:15%">0</td>
                                                    @endif

                                                    @if ($resum->encaissement != null)
                                                        <td style="width:15%">
                                                            {{ number_format($resum->encaissement, 2, ',', ' ') }}
                                                        </td>
                                                    @endif


                                                    @if ($resum->total_debt == null)
                                                        <td style="width:15%">0</td>
                                                    @endif

                                                    @if ($resum->total_debt != null)
                                                        <td style="width:15%">
                                                            {{ number_format($resum->total_debt, 2, ',', ' ') }}
                                                        </td>
                                                    @endif




                                                    @if ($resum->decaissement == null)
                                                        <td style="width:15%">0</td>
                                                    @endif

                                                    @if ($resum->decaissement != null)
                                                        <td style="width:15%">
                                                            {{ number_format($resum->decaissement, 2, ',', ' ') }}
                                                    @endif

                                                    <td style="width:15%">
                                                        {{ number_format($resum->total_receivable - $resum->total_debt, 2, ',', ' ') }}

                                                    </td>



                                                </tr>
                                                <?php $n = $n + 1; ?>
                                            @endforeach

                                           


                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: bold;">
                                                <td>#</td>
                                                <td >TOTAL </td>
                                                <td>{{$sum_resum_total->total_receivable}}</td>
                                                <td>{{$sum_resum_total->encaissement}}</td>
                                                <td>{{$sum_resum_total->total_debt}}</td>
                                                <td>{{$sum_resum_total->decaissement}}</td>
                                                <td>{{$sum_resum_total->total_receivable - $sum_resum_total->total_debt}}</td>
                                            </tr>
                                        
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="profile-tab2">

                        <div class="card-header">

                            <h4>LISTE DES CREANCES ET DETTES DE : {{ $operator->name }}</h4>
                            <div class="card-header-action">
                                <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-danger"
                                    href="#"><i class="fas fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="collapse show" id="mycard-collapse2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tableExpor"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th class="recherche">N°</th>
                                                <th class="recherche">PRESTATIONS </th>
                                                <th class="recherche">PERIODES</th>
                                                <th class="recherche">CREANCES ({{ $operator->currency }})</th>
                                                <th style="width:8%" class="recherche">ENCAISSEMENT
                                                    ({{ $operator->currency }})</th>
                                                <th class="recherche">DETTES ({{ $operator->currency }})</th>
                                                <th class="recherche">DECAISSEMENT ({{ $operator->currency }})</th>
                                                <th class="recherche"> NETTING ({{ $operator->currency }})</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 1; ?>

                                            @foreach ($resums as $resum)
                                                <tr>
                                                    <td>{{ $n }} </td>
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
                                                        <td>{{ number_format($resum->receivable, 2, ',', ' ') }}
                                                        </td>
                                                    @endif


                                                    @if ($resum->incoming_payement == null)
                                                        <td style="width:8%">0</td>
                                                    @endif

                                                    @if ($resum->incoming_payement != null)
                                                        <td style="width:8%">
                                                            {{ number_format($resum->incoming_payement, 2, ',', ' ') }}
                                                        </td>
                                                    @endif


                                                    @if ($resum->operation2->invoice->invoice_type == 'estimated')
                                                        @if ($resum->debt == null)
                                                            <td>0</td>
                                                        @endif

                                                        @if ($resum->debt != null && $resum->service != 'Facture de service voix')
                                                            <td> {{ number_format($resum->debt, 2, ',', ' ') }}
                                                            </td>
                                                        @endif

                                                        @if ($resum->debt != null && $resum->service == 'Facture de service voix')
                                                            <td style="background-color: #fcca29;">



                                                                <div style="display:block;" data-toggle="modal"
                                                                    data-target="{{ '#update_invoiceModal' . $resum->id }}">
                                                                    {{ number_format($resum->debt, 2, ',', ' ') }}
                                                                </div>


                                                            </td>
                                                        @endif
                                                    @endif

                                                    @if ($resum->operation2->invoice->invoice_type != 'estimated')
                                                        @if ($resum->debt == null)
                                                            <td>0</td>
                                                        @endif

                                                        @if ($resum->debt != null)
                                                            <td>{{ number_format($resum->debt, 2, ',', ' ') }}
                                                            </td>
                                                        @endif
                                                    @endif


                                                    @if ($resum->payout == null)
                                                        <td>0</td>
                                                    @endif

                                                    @if ($resum->payout != null)
                                                        <td>{{ number_format($resum->payout, 2, ',', ' ') }}
                                                    @endif

                                                    <td>{{ number_format($resum->netting, 2, ',', ' ') }}
                                                    </td>



                                                    <td style="width:10%">

                                                        @if ($resum->operation2->operation_type != '3')
                                                            <span data-toggle="tooltip" data-placement="top"
                                                                title="Voir la facture de créance">
                                                                <a class=" mb-2 btn btn-sm btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="{{ '#invoice' . $resum->operation1->id }}">
                                                                    <i class="fas fa-receipt text-white "> </i>
                                                                </a>
                                                            </span>
                                                        @endif

                                                        @if ($resum->operation2->operation_type == '3')
                                                            <span data-toggle="tooltip" data-placement="top"
                                                                title="Voir la facture du règlement">
                                                                <a class=" mb-2 btn btn-sm btn-primary"
                                                                    data-toggle="modal"
                                                                    data-target="{{ '#invoice' . $resum->operation2->id }}">
                                                                    <i class="fas fa-receipt text-white "> </i>
                                                                </a>
                                                            </span>
                                                        @endif

                                                        @if ($resum->operation2->id != $resum->operation1->id)
                                                            <span data-toggle="tooltip" data-placement="top"
                                                                title="Voir la facture de la dette">
                                                                <a class=" mb-2 btn btn-sm btn-dark"
                                                                    data-toggle="modal"
                                                                    data-target="{{ '#invoice' . $resum->operation2->id }}">
                                                                    <i class="fas fa-receipt text-white "> </i>
                                                                </a>
                                                            </span>
                                                        @endif



                                                    </td>
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
        </div>
    </div>


</div>


<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4>Toutes les factures de {{ $operator->name }}</h4>
            <div class="card-header-action">
                <a data-collapse="#mycard-collapse1" class="btn btn-icon btn-info" href="#"><i
                        class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="collapse hide" id="mycard-collapse1">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExpor1" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="recherche">N°</th>
                                <th class="recherche">PRESTATION</th>
                                <th class="recherche">N° FACTURE</th>
                                <th class="recherche">PERIODES</th>
                                <th class="recherche">TYPE</th>
                                <th class="recherche">MONTANT</th>
                                <th class="recherche">CREANCES TOGOCOM</th>

                                <th class="recherche">DETTES TOGOCOM</th>
                                <th class="recherche">SOLDE</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1; ?>

                            @foreach ($operations as $operation)
                                @if ($operation->invoice->invoice_type == 'litigious')
                                    <tr style="background-color: #03a04f; color:aliceblue;">
                                        <td>{{ $n }} </td>
                                        <td style="width:15%">{{ $operation->operation_name }} </td>



                                        @if ($operation->invoice->invoice_number != null)
                                            <td style="width:15%">{{ $operation->invoice->invoice_number }} </td>
                                        @endif

                                        @if ($operation->invoice->invoice_number == null)
                                            <td style="width:15%">------------ </td>
                                        @endif

                                        @if ($operation->invoice->period != null)
                                            <td>{{ periodePrint($operation->invoice->period) }}</td>
                                        @endif

                                        @if ($operation->invoice->period == null)
                                            <td>-------</td>
                                        @endif

                                        <td>Facture Litigieuse</td>


                                        <td>{{ number_format($operation->amount, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable - $operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>


                                        <td style="width:10%">
                                            <span data-toggle="tooltip" data-placement="top" title="Voir la facture">
                                                <a class=" mb-2 btn btn-sm btn-warning"  
                                                    href="{{$operation->facture_name}}" target="blank">
                                                    <i class="fas fa-eye text-white "> </i>
                                                </a>
                                            </span>

                                            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                                <span data-toggle="tooltip" data-placement="top"
                                                    title="Mettre à jour les informations  de la facture">
                                                    <a class=" mb-2 btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="{{ '#update_all_invoice' . $operation->id }}">
                                                        <i class=" fas fa-file-signature text-white "> </i>
                                                    </a>
                                                </span>

                                                <span data-toggle="tooltip" data-placement="top"
                                                    title="Ajouter une  note de credit">
                                                    <a class=" mb-2 btn btn-sm btn-danger" data-toggle="modal"
                                                        data-target="{{ '#cn' . $operation->id }}">
                                                        <i class="fas fa-handshake text-white "> </i>
                                                    </a>
                                                </span>
                                            @endif





                                        </td>
                                    </tr>
                                @endif


                                @if ($operation->invoice->invoice_type == 'estimated')
                                    <tr style="background-color: #fcca29;">
                                        <td>{{ $n }} </td>
                                        <td style="width:15%">{{ $operation->operation_name }} </td>
                                        @if ($operation->invoice->invoice_number != null)
                                            <td style="width:15%">{{ $operation->invoice->invoice_number }} </td>
                                        @endif

                                        @if ($operation->invoice->invoice_number == null)
                                            <td style="width:15%">------------ </td>
                                        @endif

                                        @if ($operation->invoice->period != null)
                                            <td>{{ periodePrint($operation->invoice->period) }}</td>
                                        @endif

                                        @if ($operation->invoice->period == null)
                                            <td>-------</td>
                                        @endif

                                        <td>Facture Estimée</td>


                                        <td>{{ number_format($operation->amount, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable - $operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>


                                        <td style="width:10%">
                                            <span data-toggle="tooltip" data-placement="top" title="Voir la facture">
                                                <a class=" mb-2 btn btn-sm btn-primary" data-toggle="modal"
                                                href="{{$operation->facture_name}}" target="blank">
                                                    <i class="fas fa-eye text-white "> </i>
                                                </a>
                                            </span>
                                            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                                <span data-toggle="tooltip" data-placement="top"
                                                    title="Mettre à jour les informations  de la facture">
                                                    <a class=" mb-2 btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="{{ '#update_all_invoice' . $operation->id }}">
                                                        <i class=" fas fa-file-signature text-white "> </i>
                                                    </a>
                                                </span>
                                            @endif


                                        </td>
                                    </tr>
                                @endif


                                @if ($operation->invoice->invoice_type != 'litigious' && $operation->invoice->invoice_type != 'estimated')
                                    <tr>
                                        <td>{{ $n }} </td>
                                        <td style="width:15%">{{ $operation->operation_name }} </td>
                                        @if ($operation->invoice->invoice_number != null)
                                            <td style="width:15%">{{ $operation->invoice->invoice_number }} </td>
                                        @endif

                                        @if ($operation->invoice->invoice_number == null)
                                            <td style="width:15%">------------ </td>
                                        @endif

                                        @if ($operation->invoice->period != null)
                                            <td>{{ periodePrint($operation->invoice->period) }}</td>
                                        @endif

                                        @if ($operation->invoice->period == null)
                                            <td>-------</td>
                                        @endif
                                        @if ($operation->invoice_type == 'real')
                                            <td>Facture réelle</td>
                                        @endif


                                        @if (
                                            $operation->invoice_type != 'litigious' &&
                                                $operation->invoice_type != 'estimated' &&
                                                $operation->invoice_type != 'real')
                                            <td>{{ $operation->invoice_type }}</td>
                                        @endif

                                        <td>{{ number_format($operation->amount, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>
                                        <td>{{ number_format($operation->new_receivable - $operation->new_debt, 2, ',', ' ') . '  ' . $operator->currency }}
                                        </td>


                                        <td style="width:10%">
                                            <span data-toggle="tooltip" data-placement="top" title="Voir la facture">
                                                <a class=" mb-2 btn btn-sm btn-primary"  href="{{$operation->facture_name}}" target="blank">
                                                    <i class="fas fa-eye text-white "> </i>
                                                </a>
                                            </span>
                                            @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                                <span data-toggle="tooltip" data-placement="top"
                                                    title="Mettre à jour les informations  de la facture">
                                                    <a class=" mb-2 btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="{{ '#update_all_invoice' . $operation->id }}">
                                                        <i class=" fas fa-file-signature text-white "> </i>
                                                    </a>
                                                </span>


                                                <span data-toggle="tooltip" data-placement="top"
                                                    title="Ajouter une contestation à la facture">
                                                    <a class=" mb-2 btn btn-icon  btn-sm btn-dark" data-toggle="modal"
                                                        data-target="{{ '#contest_invoice' . $operation->id }}">

                                                        <i class="fas fa-balance-scale  text-white"> </i>

                                                    </a>
                                                </span>
                                            @endif

                                        </td>
                                    </tr>
                                @endif
                                <?php $n = $n + 1; ?>
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4>Toutes les opérations de {{ $operator->name }}</h4>
            <div class="card-header-action">
                <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i
                        class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="collapse hide" id="mycard-collapse4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableExpor2" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="recherche">N°</th>
                                <th class="recherche">Libellé</th>
                                <th class="recherche">Opérateur</th>
                                <th class="recherche">N° FACTURE</th>

                                <th class="recherche">Montant</th>
                                <th class="recherche">Devise</th>
                                <th class="recherche">Ajouter par:</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1; ?>

                            @foreach ($operations as $operation)
                                <tr>
                                    <td>{{ $n }} </td>
                                    <td style="width:25%">{{ $operation->operation_name }} </td>
                                    <td>{{ $operator->name }}</td>
                                    <td style="width:15%">{{ $operation->invoice->invoice_number }} </td>

                                    <td>{{ number_format($operation->amount, 2, ',', ' ') }}</td>
                                    <td>{{ $operator->currency }}</td>
                                    <td>{{ $operation->user->last_name . ' ' . $operation->user->first_name }}</td>



                                    <td style="width:10%">
                                        <span data-toggle="tooltip" data-placement="top" title="Voir la facture">
                                            <a class=" mb-2 btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="{{ '#invoice' . $operation->id }}">
                                                <i class="fas fa-eye text-white "> </i>
                                            </a>
                                        </span>


                                        @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                            <span data-toggle="tooltip" data-placement="top"
                                                title="Annuler l'opération">
                                                <a class=" delete-confirm mb-2 btn btn-sm btn-danger"
                                                    href="/cancel_operation/{{ $operation->id }}">
                                                    <i class="fas far fa-times-circle text-white"> </i>
                                                </a>
                                            </span>
                                        @endif


                                    </td>
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


@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#tableExpor235 thead  tr .recherche ').clone(true).appendTo('#tableExpor235 thead ').addClass("rech");
        $('#tableExpor235 thead .rech ').each(function(i) {
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

        var myVariable = <?php echo(json_encode($operator->name)); ?>;

        var table = $('#tableExpor235').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'Bfrtip',

           
            buttons: [
                
                { extend: 'copyHtml5', footer: true },
                { extend: 'excelHtml5', footer: true },
                { extend: 'csvHtml5', footer: true },
                { extend: 'pdfHtml5', footer: true,
                 filename: 'SOMME PAR ANNEES DES CREANCES ET DETTES DE : '.concat(myVariable),
                 title: 'SOMME PAR ANNEES DES CREANCES ET DETTES DE : '.concat(myVariable) 
 }
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

    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#tableExpor1 thead tr .recherche').clone(true).appendTo('#tableExpor1 thead').addClass("rech");
        $('#tableExpor1 thead .rech ').each(function(i) {
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

        var table = $('#tableExpor1').DataTable({
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


    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#tableExpor2 thead tr .recherche').clone(true).appendTo('#tableExpor2 thead').addClass("rech");
        $('#tableExpor2 thead .rech ').each(function(i) {
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

        var table = $('#tableExpor2').DataTable({
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
