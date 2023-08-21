@extends('template.principal_tamplate')
@section('title', 'Versement')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Versement</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('versement_save') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-12">
                        <label for="inputEmail4">Solde total sur votre compte d'agence</label>
                        <input name="solde_agence" type="text" disabled class="form-control  @error('solde_agence') is-invalid @enderror"
                            value="{{$solde}}fr Cfa" placeholder="" required>

                        @error('solde_agence')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="form-group col-md-12">
                    <label>La somme à verser <strong>(la somme total de vos versement est de {{$versement}}fr Cfa)</strong></label>
                    <div class="input-group">

                      <input type="numeric" name="somme" class="form-control currency  @error('somme') is-invalid @enderror"
                      value="{{ @old('somme') }}" placeholder="" required>

                      @error('somme')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                        @enderror
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                              Fr cfa
                            </div>
                          </div>
                    </div>
                  </div>






                <div class="row">
                    <div class="card-footer align-center ">
                        <button class="btn btn-primary align-content-center">Effectuer le versement</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
