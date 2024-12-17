@extends('template.principal_tamplate')
@section('title', "Mise à jours des informations du client")

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mise à jours des informations du client</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Mettez à jours des informations du client</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('update_client') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}



                    <div class="form-row">

                        <div class="form-group col-md-4">
                            <label for="inputEmail4">Nom</label>
                            <input name="last_name" type="text" class="form-control  @error('last_name') is-invalid @enderror"
                                value="{{ $client->user->last_name}}" placeholder="" required>

                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail10">Prénoms</label>
                            <input name="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror"
                                placeholder="" value="{{ $client->user->first_name}}" required>

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <input type="hidden" name="id" value="{{ $client->user->id}}" id="">
                        <input type="hidden" name="client_id" value="{{ $client->id}}" id="">


                        <div class="form-group col-md-4">
                            <label for="inputEmail4">Email</label>

                            <input type="email" name="email"
                                class="form-control  @error('email') is-invalid @enderror" value="{{ $client->user->email}}"
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
                        value="{{ $client->user->tel}}" id="" placeholder="">

                        @error('tel1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Activité commercial </label>
                        <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror"
                        value="{{ $client->occupation}}" id="" placeholder="">

                        @error('occupation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Contact boutique</label>
                        <input type="tel" name="tel_b"
                            class="form-control @error('tel_b') is-invalid @enderror" id=""
                            value="{{ $client->tel_b}}" placeholder="">

                        @error('tel_b')
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
                            value="{{ $client->user->adresse}}" placeholder="">

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
                            value="{{ $client->user->quartier}}" placeholder="">

                        @error('quartier')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Ville </label>
                        <select name="ville" id="inputState" class="form-control">
                            <option {{$client->user->ville == 'Tchamba' ? 'selected' : ''}} value="Tchamba">Tchamba</option>
                            <option {{$client->user->ville == 'Lome' ? 'selected' : ''}} value="Lome">Lomé</option>
                            <option {{$client->user->ville == 'kara' ? 'selected' : ''}} value="kara">Kara</option>
                            <option {{$client->user->ville == 'sokode' ? 'selected' : ''}} value="sokode">Sokode</option>
                        </select>
                    </div>

                </div>

                <div class="form-row ">

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Choisir le type de carte à enregistrer</label>
                        <select name="type_carte" id="inputState" class="form-control">
                            <option {{$client->user->ville == 'CNI' ? 'selected' : ''}} value="CNI">Carte d'identité national</option>
                            <option {{$client->user->ville == "Carte d'electeur" ? 'selected' : ''}} value="Carte d'electeur">Carte d'électeur</option>
                            <option {{$client->user->ville == 'Passeport' ? 'selected' : ''}} value="Passeport">Passeport</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">N° de carte</label>
                        <input type="text" name="card_number"
                            class="form-control @error('card_number') is-invalid @enderror" id=""
                            value="{{$client->user->card_number }}" placeholder="">

                        @error('card_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>


                <div class="form-row ">




                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Adresse de collecte</label>
                        <input type="text" name="adresse_collecte"
                            class="form-control @error('adresse_collecte') is-invalid @enderror" id=""
                            value="{{ $client->adresse_collecte}}" placeholder="">

                        @error('adresse_collecte')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Type de client </label>
                        <select name="type_client" id="inputState" class="form-control">
                            <option value="Physique">Physique</option>
                            <option value="Moral">Moral</option>>
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









                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Sauvegarder</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
