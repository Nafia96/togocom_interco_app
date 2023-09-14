@extends('template.principal_tamplate')
@section('title', 'Modification du mot de passe')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modification du mot de passe</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations </h4>
        </div>
        <div class="card-body">

            <form action="{{ url('update_password_save') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}










                    <div class="form-row">


                        <div class="form-group col-md-4">
                            <label for="inputEmail4"> Encien mot de passe </label>
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror"
                                value="{{ @old('old_password') }}" placeholder="" required>

                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail4">Nouveau mot de passe </label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="" required>

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







                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Sauvegarder</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
