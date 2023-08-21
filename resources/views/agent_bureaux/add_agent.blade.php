@extends('template.principal_tamplate')
@section('title', 'Ajouter un agent bureau')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajouter un agent bureau</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations de l'agent du bureau</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('agentadd_agent_br_register') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Nom</label>
                        <input name="last_name" type="text" class="form-control  @error('last_name') is-invalid @enderror"
                            value="{{ @old('last_name') }}" placeholder="" required>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail10">Prénoms</label>
                        <input name="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror"
                            placeholder="" value="{{ @old('first_name') }}" required>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Email</label>

                        <input type="email" name="email"
                            class="form-control  @error('email') is-invalid @enderror" value="{{ @old('email') }}"
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
                            value="{{ @old('tel1') }}" id="" placeholder="">

                        @error('tel1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Niveau d'étude </label>
                        <select name="diplome" id="inputState" class="form-control">
                            <option value="CEPD">CEPD</option>
                            <option value="BEPC">BEPC</option>
                            <option value="BAC 1">BAC 1</option>
                            <option value="BAC 2">BAC 2</option>
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
                            value="{{ @old('adresse') }}" placeholder="">

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
                            value="{{ @old('quartier') }}" placeholder="">

                        @error('quartier')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Ville </label>
                        <select name="ville" id="inputState" class="form-control">
                            <option value="Tchamba">Tchamba</option>
                            <option value="Lome">Lomé</option>
                            <option value="kara">Kara</option>
                            <option value="sokode">Sokode</option>
                        </select>
                    </div>

                </div>

                <div class="form-row ">

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Choisir le type de carte à enregistrer</label>
                        <select name="type_carte" id="inputState" class="form-control">
                            <option value="CNI">Carte d'identité national</option>
                            <option value="Carte d'electeur">Carte d'électeur</option>
                            <option value="Passeport">Passeport</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">N° de carte</label>
                        <input type="text" name="card_number"
                            class="form-control @error('card_number') is-invalid @enderror" id=""
                            value="{{ @old('card_number') }}" placeholder="">

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
                            value="{{ @old('date_naissance') }}" placeholder="">

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
                            value="{{ @old('zone') }}" placeholder="">

                        @error('zone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Type de contrat </label>
                        <select name="type_contrat" id="inputState" class="form-control">
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>>
                        </select>
                    </div>

                </div>





                <fieldset>
                    <legend>
                        <h5>Identifiants de connexion de l'agent </h5>
                    </legend>

                    <div class="form-row">


                        <div class="form-group col-md-4">
                            <label for="inputEmail4"> Login </label>
                            <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                                value="{{ @old('login') }}" placeholder="" required>

                            @error('login')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail4"> Mot de passe </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail4"> Confirmer le mot de passe </label>
                            <input type="password" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" placeholder=""
                                required>

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                    </div>



                </fieldset>




                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Sauvegarder</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
