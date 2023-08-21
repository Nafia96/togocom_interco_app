@extends('template.principal_tamplate')
@section('title', 'Création d\'un compte dépôt')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Création d'un compte dépôt</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Ajouter un compte dépôt</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('add_depot') }}" enctype="multipart/form-data" method="post">
                @csrf

                <div class="form-row">


                    <label>Date d'dépôt</label>
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

                <div class="form-row">


                    <label for="inputEmail4">Sélectionnez le client qui ouvre le compte</label>
                    <select name="client_id" id="demo">
                        @foreach ($clients as $client)
                            <option selected value="{{ $client->user->id }}">
                                <div class="form-group col-6">{{ $client->user->last_name . ' ' . $client->user->first_name }}</option>
                        @endforeach
                    </select>





                </div>

                <br>

                <div class="form-row">


                        <label for="inputEmail4">Premier versement (Dépôt)</label>
                        <input name="epargne" type="number" class="form-control  @error('epargne') is-invalid @enderror"
                            value="{{ @old('last_name') }}" placeholder="" required>

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
                        value="">  </textarea>

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
