@extends('template.principal_tamplate')
@section('title', 'Ajouter un utilisateur')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajouter un utilisateur</li>

            <div class="d-flex justify-content-end container-fluid mt-n3">
                <a href="{{route('users_list')}}" class="btn btn-primary ">Liste des utilisateurs </a>
            </div>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations de l'utilisateur</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('user_register') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Nom de l'utilisateur</label>
                        <input name="last_name" type="text" class="form-control  @error('last_name') is-invalid @enderror"
                            value="{{ @old('last_name') }}" placeholder="" required>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Prenom de l'utilisateur</label>
                        <input name="first_name" type="text" class="form-control  @error('first_name') is-invalid @enderror"
                            value="{{ @old('first_name') }}" placeholder="" required>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Telephone </label>
                        <input type="tel" name="tel" class="form-control @error('tel') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('tel') }}">

                        @error('tel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>




                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Email </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('email') }}">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Poste occupé</label>
                        <input type="text" name="post" class="form-control @error('post') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('post') }}">

                        @error('post')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Niveau d'utilisateur </label>
                        <select name="type_user" id="inputState" class="form-control">
                            <option value="0"  selected="selected" >1 (visualiser les informations)</option>
                            <option value="1"  >2 (visualiser et extraire les données)</option>
                            <option value="2"  >3 (Admin)</option>
                            <option value="3"  >4 (Super Admin)</option>
                            <option value="6"  >5 (Agent BI)</option>

                        </select>
                    </div>



                </div>

                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Login </label>
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('login') }}">

                        @error('login')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>



                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Mot de passe</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('password') }}">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Confirmation du mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="" placeholder="" value="{{ @old('password_confirmation') }}">

                        @error('password_confirmation')
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
