@extends('tamplate.homeTamplate')
@section('title','Information sur la société')
@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{'/'}}">Dashboard</a></li>

            @if($nouveau->suspect)
            <li class="breadcrumb-item active" aria-current="page">Information sur le suspect</li>
                @endif
            @if($nouveau->prospect)
                <li class="breadcrumb-item active" aria-current="page">Information sur le prospect</li>
            @endif
            @if($nouveau->client)
                <li class="breadcrumb-item active" aria-current="page">Information sur le client</li>
            @endif

            <div class="d-flex justify-content-end container-fluid mt-n3">
                @if($nouveau->client)
                    <a href="{{route('client_liste')}}" class="btn btn-primary mr-1">Liste des Clients</a>
                @elseif($nouveau->suspect)
                    <a href="{{route('suspect_liste')}}" class="btn btn-primary mr-1">Liste des suspects</a>

                @else($nouveau->prospect)
                    <a href="{{route('prospect_liste')}}" class="btn btn-primary mr-1">Liste des prospects</a>
                @endif

                <a href="{{url('update_tiers/'.$nouveau->id)}}" class="btn btn-primary ">Modifier</a>

                    @if($nouveau->client)
                        <a href="{{route('clientRegister')}}" class="btn btn-primary ml-1">Ajouter Client</a>
                    @elseif($nouveau->suspect)
                        <a href="{{route('suspectRegister')}}" class="btn btn-primary ml-1">Ajouter suspect</a>

                    @else($nouveau->prospect)
                        <a href="{{route('prospectRegister')}}" class="btn btn-primary ml-1">Ajouter prospect</a>
                    @endif

            </div>
        </ol>
    </nav>
@stop

@section('content')

    <div class="section-body">
    <div class="row mt-sm-4">
        <div class="col-12 col-md-12 col-lg-4">
            <div class="card author-box">
                <div class="card-body">
                    <div class="author-box-center">
                        <img alt="image" src="{{asset('storage/tiers/'.$nouveau->logo)}}"
                             class="rounded-circle author-box-picture">
                        <div class="clearfix"></div>
                        <div class="author-box-name">
                            <a href="#">{{$nouveau->name }}</a>
                        </div>
                        <div class="author-box-job">{{$nouveau->post}}</div>
                    </div>
                    <div class="text-center">

                        <div class="mb-2 mt-3">

                            <div class="text-small font-weight-bold"><a target="_blank" href="https://{{$nouveau->site_web}}">
                                    {{$nouveau->site_web}}
                                </a></div>
                        </div>
                        <a href="{{$nouveau->url_facebook}}" class="btn btn-social-icon mr-1 btn-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{$nouveau->url_linkedin}}" class="btn btn-social-icon mr-1 btn-linkedin">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="{{$nouveau->url_instagram}}" class="btn btn-social-icon mr-1 btn-instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <div class="w-100 d-sm-none"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Contacts</h4>
                </div>
                <div class="card-body">
                    <div class="py-4">
                        <p class="clearfix">
                                <span class="float-left">
                                   {{"Date d'ajout"}}
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->created_at}}
                                </span>

                        </p>
                        <p class="clearfix">
                                <span class="float-left">
                                    Dernnièr mise à jour
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->updated_at}}
                                </span>

                        </p>
                        <p class="clearfix">
                                <span class="float-left">
                                    Email  :
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->email}}
                                </span>

                        </p>

                        <p class="clearfix">
                                <span class="float-left">
                                    Email 2:
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->email2}}
                                </span>

                        </p>

                        <p class="clearfix">
                                <span class="float-left">
                                    Tel:
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->tel}}
                                </span>

                        </p>

                        <p class="clearfix">
                                <span class="float-left">
                                    Tel 2:
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->tel2}}
                                </span>

                        </p>

                        <p class="clearfix">
                                <span class="float-left">
                                   Code postal :
                                </span>
                            <span class="float-right text-muted">
                                     {{$nouveau->code_postal}}
                                </span>

                        </p>


                    </div>
                </div>
            </div>

        </div>
        <div class="col-12 col-md-12 col-lg-8">
            <div class="card">
                <div class="padding-20">
                    <ul class="nav nav-tabs" id="myTab2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#about" role="tab"
                               aria-selected="true">Informations générales</a>
                        </li>

                        @if($nouveau->client || $nouveau->prospect)


                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#prospection" role="tab"
                                   aria-selected="true">Prospection </a>
                            </li>


                            @endif



                        @if($nouveau->client)


                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab2" data-toggle="tab" href="#commades" role="tab"
                                   aria-selected="true">Commandes </a>
                            </li>


                        @endif



                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                            <br>
                            @if($nouveau->prospect)
                                <div style="color: #865d34"><h3 class="author-box-name align-center">{{$nouveau->name}}</h3></div>
                                <br>
                            @endif
                            @if($nouveau->suspect)
                               <div style="color: #ff776d"><h3 class="author-box-name align-center">{{$nouveau->name}}</h3></div>
                                <br>
                            @endif
                            @if($nouveau->client)
                                <div style="color: #28530e"><h3 class="author-box-name align-center">{{$nouveau->name}}</h3></div>
                                <br>
                            @endif

                            <div class="row">
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Type de personne</strong>
                                    <br>
                                    <p class="text-muted">{{'Personne '.$nouveau->type_personne}}</p>
                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Responsable</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->nom_responsable.' '.$nouveau->prenom_responsable}}</p>
                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Numéros de TVA</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->numeros_tva}}</p>
                                </div>
                                <div class="col-md-3 col-6">
                                    <strong>Ville</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->ville.'-'.$nouveau->pays}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Statut</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->statut}}</p>
                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Nombre d'employé</strong>
                                    <br>

                                        <p class="text-muted">{{$nouveau->nombre_employe}}</p>


                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Potentiel</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->potentiel}}</p>
                                </div>
                                <div class="col-md-3 col-6">
                                    <strong>Importance</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->importance}}</p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Secteur d'activité</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->secteur_activite}}</p>
                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Segment </strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->segment}}</p>


                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Personne source </strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->personne_source_nom.' '.$nouveau->personne_source_prenom}}</p>
                                </div>
                                <div class="col-md-3 col-6">
                                    <strong>Email personne source</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->email_p}}%</p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Email 2 personne source</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->email2_p}}</p>
                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Tel personne source</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->tel_p}}</p>


                                </div>
                                <div class="col-md-3 col-6 b-r">
                                    <strong>Tel 2 personne source</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->tel2_p}}</p>
                                </div>

                                <div class="col-md-3 col-6 b-r">
                                    <strong>Personne source poste</strong>
                                    <br>
                                    <p class="text-muted">{{$nouveau->post_p}}</p>
                                </div>


                            </div>
                            <div class="row">

                                @if($nouveau->prospect || $nouveau->client)

                                <div class="col-md-3 col-6">
                                <strong>Chiffre d'affaire estimé</strong>
                                <br>
                                <p class="text-muted">{{$nouveau->ca_estime}}</p>
                            </div>
                            <div class="col-md-3 col-6">
                                <strong>Frequence de CA estimé</strong>
                                <br>
                                <p class="text-muted">{{$nouveau->frequence_ca}}</p>
                            </div>
                                @endif
                             <div class="col-md-3 col-6">
                                <strong>Localité/Quartier</strong>
                                <br>
                                <p class="text-muted">{{$nouveau->quartier}}</p>
                            </div>
                            </div>

                            </div>

                        <div class="row container-fluid mt-3 ">

                            <div class="mt-3 col-12 text-center m-auto" style="color: #95a0f4">
                                <strong> Opportunité d’affaire</strong>
                            </div>

                            <div class="mt-2 col-12 text-center m-auto">
                                {{$nouveau->opportunite}}
                            </div>
                        </div>

                        <div class="row container-fluid mt-3 ">

                            <div class="mt-3 col-12 text-center m-auto" style="color: #95a0f4">
                                <strong> Commentaire</strong>
                            </div>

                            <div class="mt-2 col-12 text-center m-auto">
                                {!! $nouveau->commentaire !!}
                            </div>
                        </div>




                        <div class="tab-pane fade " id="prospection" role="tabpanel" aria-labelledby="home-tab2">

                        </div>

                        <div class="tab-pane fade" id="commandes" role="tabpanel" aria-labelledby="home-tab2">


                        </div>
                </div>
            </div>
        </div>
    </div>






    </div >


    </div>


@stop
