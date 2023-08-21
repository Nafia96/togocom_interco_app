@extends('template.principal_tamplate')
@section('title', 'Ajouter un Chef d\'agence')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajouter un chef d'agence</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations du nouveau chef d'agence ({{$agence->nom}})</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('add_new_chef_agence') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Nom</label>
                        <input name="last_name" type="text" class="form-control  @error('last_name') is-invalid @enderror" value="{{@old('last_name')}}" placeholder="" required>

                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail10">Prénoms</label>
                        <input name="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror" placeholder="" value="{{@old('first_name')}}" required>

                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Email</label>

                        <input type="email" name="user_email" class="form-control  @error('user_email') is-invalid @enderror" value="{{@old('user_email')}}"  id="inputEmail4" placeholder="">

                        @error('user_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>




                </div>

                <input type="hidden" name="id_agence" value="{{$agence->id}}">
                <input type="hidden" name="nom_agence" value="{{$agence->nom}}">



                <div class="form-row ">


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Téléphone </label>
                        <input type="tel" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{@old('telephone')}}" id="" placeholder="" >

                        @error('telephone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Poste administratif </label>
                        <input type="text" name="poste" class="form-control @error('poste') is-invalid @enderror" id="" placeholder="" value="{{@old('poste')}}" >

                        @error('poste')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                     <div class="form-group col-md-4">
                        <label for="inputEmail4">Avatar </label>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" id="" placeholder="" >

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
                        <input type="text" name="user_adresse" class="form-control @error('user_adresse') is-invalid @enderror" id="" value="{{@old('adresse')}}" placeholder="">

                        @error('user_adresse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Quartier/localité</label>
                        <input type="text" name="user_quartier" class="form-control @error('user_quartier') is-invalid @enderror" id="" value="{{@old('quartier')}}" placeholder="">

                        @error('user_quartier')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Ville </label>
                        <select  name="user_ville" id="inputState" class="form-control">
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
                        <select  name="type_carte" id="inputState" class="form-control">
                            <option value="CNI">Carte d'identité national</option>
                            <option value="Carte d'électeur">Carte d'electeur</option>
                            <option value="Passeport">Passeport</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">N° de carte</label>
                        <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror" id="" value="{{@old('adresse')}}" placeholder="">

                        @error('card_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                </div>

                <fieldset>
                    <legend>
                        <h5>Identifiants de connexion du nouveau chef d'agence</h5>
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

         </div>











            </form>
        </div>
    </div>


@stop
