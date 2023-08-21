@extends('template.principal_tamplate')
@section('title', "Mise à jours d'un agent")

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mise à jours d'un agent</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Mettez à jours les informations de l'agent</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('update_agent') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Nom</label>
                        <input name="last_name" type="text" class="form-control  @error('last_name') is-invalid @enderror"
                            value="{{ $agent->user->last_name}}" placeholder="" required>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail10">Prénoms</label>
                        <input name="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror"
                            placeholder="" value="{{ $agent->user->first_name}}" required>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <input type="hidden" name="id" value="{{ $agent->user->id}}" id="">
                    <input type="hidden" name="agent_id" value="{{ $agent->id}}" id="">


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Email</label>

                        <input type="email" name="email"
                            class="form-control  @error('email') is-invalid @enderror" value="{{ $agent->user->email}}"
                            id="inputEmail4" placeholder="">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>




                </div>



                <div class="form-row ">



                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Téléphone </label>
                        <input type="tel" name="tel1" class="form-control @error('tel1') is-invalid @enderror"
                        value="{{ $agent->user->tel}}" id="" placeholder="">

                        @error('tel1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Niveau d'étude </label>
                        <select name="diplome" id="inputState" class="form-control">
                            <option {{$agent->diplome == 'CEPD' ? 'selected' : ''}} value="CEPD">CEPD</option>
                            <option {{$agent->diplome == 'BEPC' ? 'selected' : ''}} value="BEPC">BEPC</option>
                            <option {{$agent->diplome == 'BAC 1' ? 'selected' : ''}} value="BAC 1">BAC 1</option>
                            <option {{$agent->diplome == 'BAC 2' ? 'selected' : ''}} value="BAC 2">BAC 2</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Avatar </label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id=""
                            placeholder="">

                        @error('avatar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Adresse</label>
                        <input type="text" name="adresse"
                            class="form-control @error('adresse') is-invalid @enderror" id=""
                            value="{{ $agent->user->adresse}}" placeholder="">

                        @error('adresse')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Quartier/localité</label>
                        <input type="text" name="quartier"
                            class="form-control @error('quartier') is-invalid @enderror" id=""
                            value="{{ $agent->user->quartier}}" placeholder="">

                        @error('quartier')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Ville </label>
                        <select name="ville" id="inputState" class="form-control">
                            <option {{$agent->user->ville == 'Tchamba' ? 'selected' : ''}} value="Tchamba">Tchamba</option>
                            <option {{$agent->user->ville == 'Lome' ? 'selected' : ''}} value="Lome">Lomé</option>
                            <option {{$agent->user->ville == 'kara' ? 'selected' : ''}} value="kara">Kara</option>
                            <option {{$agent->user->ville == 'sokode' ? 'selected' : ''}} value="sokode">Sokode</option>
                        </select>
                    </div>

                </div>

                <div class="form-row ">

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Choisir le type de carte à enregistrer</label>
                        <select name="type_carte" id="inputState" class="form-control">
                            <option {{$agent->user->ville == 'CNI' ? 'selected' : ''}} value="CNI">Carte d'identité national</option>
                            <option {{$agent->user->ville == "Carte d'electeur" ? 'selected' : ''}} value="Carte d'electeur">Carte d'électeur</option>
                            <option {{$agent->user->ville == 'Passeport' ? 'selected' : ''}} value="Passeport">Passeport</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">N° de carte</label>
                        <input type="text" name="card_number"
                            class="form-control @error('card_number') is-invalid @enderror" id=""
                            value="{{$agent->user->card_number }}" placeholder="">

                        @error('card_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>



                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Date de naissance</label>
                        <input type="date" name="date_naissance"
                            class="form-control @error('date_naissance') is-invalid @enderror" id=""
                            value="{{ $agent->date_naissance}}" placeholder="">

                        @error('date_naissance')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Zone de collecte</label>
                        <input type="text" name="zone"
                            class="form-control @error('zone') is-invalid @enderror" id=""
                            value="{{ $agent->zone}}" placeholder="">

                        @error('zone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Type de contrat </label>
                        <select name="type_contrat" id="inputState" class="form-control">
                            <option {{$agent->type_contrat == 'CDI' ? 'selected' : ''}} value="CDI">CDI</option>
                            <option {{$agent->type_contrat == 'CDD' ? 'selected' : ''}} value="CDD">CDD</option>>
                        </select>
                    </div>

                </div>




                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Sauvegarder</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
