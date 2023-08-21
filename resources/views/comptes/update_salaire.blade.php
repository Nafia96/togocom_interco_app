@extends('template.principal_tamplate')
@section('title', 'Modification d\'un compte salaire')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Midification d'un compte salaire</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Modification d'un compte salaire</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('save_salaire_update') }}" enctype="multipart/form-data" method="post">
                @csrf

                <div class="form-row">


                    <label>Date de debut</label>
                    <div class="input-group col-12">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                        <input name="start_date" type="date" class="form-control datepicker">
                    </div>





                </div>

            </br>
            <input type="hidden" name="id_compte" value="{{$compte->id}}">

                <div class="form-row">


                    <label for="inputEmail4">Le client du compte (Vous ne pouvez pas modifier le client)</label>
                    <select name="client_id" id="demo" disabled>

                            <option selected value="{{ $compte->client->id }}">
                                <div class="form-group col-6">{{ $compte->client->last_name . ' ' . $compte->client->first_name }}</option>

                    </select>





                </div>

                <br>

                <div class="form-row">


                        <label for="inputEmail4">Premier verssement (Salaire)</label>
                        <input name="epargne" type="number" class="form-control  @error('epargne') is-invalid @enderror"
                            value="{{$compte->solde_actuelle }}" placeholder="" required>

                        @error('epargne')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <br>


                <div class="form-group">
                    <label>Description</label>

                    <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                        value="">  {{$compte->description }}</textarea>

                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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

@section('script')




    <script>
        new SlimSelect({
            select: '#demo'
        })

        new SlimSelect({
            select: '#demo2'
        })
    </script>

@stop
