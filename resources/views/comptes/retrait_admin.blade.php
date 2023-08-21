@extends('template.principal_tamplate')
@section('title', 'Versement')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Retraits sur le compte de la société</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Remplissez les informations</h4>
        </div>
        <div class="card-body">

            <form action="{{ url('retrait_admin_save') }}" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}

                <div class="form-row">

                    <div class="form-group col-md-12">
                        <label for="inputEmail4">Part beneficiaire actuelle de la société</label>
                        <input name="benefice_act" type="text" disabled class="form-control  @error('benefice_act') is-invalid @enderror"
                            value="{{$benefice_act}}fr Cfa" placeholder="" required>

                        @error('benefice_act')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group col-md-12">
                        <label for="inputEmail4">Solde totale de vos retrait sur le compte de la société est de :</label>
                        <input name="retrait_admin" type="text" disabled class="form-control  @error('retrait_admin') is-invalid @enderror"
                            value="{{$retrait_admin}}fr Cfa" placeholder="" required>

                        @error('retrait_admin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>
                <input type="hidden" name="solde" value="{{$benefice_act }}">

                <div class="form-group col-md-12">
                    <label>La somme à retirer :<strong>(Vous ne pouvez retirer au plus  {{$benefice_act }}fr Cfa)</strong></label>
                    <div class="input-group">

                      <input type="numeric" name="somme" class="form-control currency  @error('somme') is-invalid @enderror"
                      value="{{ @old('somme') }}" placeholder="" required>

                      @error('somme')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>versement
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
                        <button class="btn btn-primary align-content-center">Effectuer le retrait</button>
                    </div>


                </div>



            </form>
        </div>
    </div>


@stop
