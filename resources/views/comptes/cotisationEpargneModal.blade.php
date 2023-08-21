<div class="modal fade bd-example-modal-lg" id="{{ 'cotisationEpargneModal' . $compte->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="myLargeModalLabel">Depot sur le compte épargne </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="epargne_tontine" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($compte))

                    <div class="mt-3  col-12 text-center  m-auto">
                        <img style="height: 200px; width: 200px; margin-bottom: 10px" alt="image"  src="{{ $compte->client->user->avatar}}""
                             class="rounded-circle author-box-picture">
                        <div class="clearfix"></div>
                        <div class="author-box-name mb-3">
                            <h5>{{ $compte->client->user->last_name }} {{ $compte->client->user->first_name }}</h5>
                        </div>
                    </div>



                        <div class="tab-content tab-bordered" id="myTab3Content">
                            <div class="tab-pane fade show active" id="about" role="tabpanel"
                                aria-labelledby="home-tab2" style="background-color:#E5E8E8;  border-radius: 13px;">
                                <div class="row">
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Numéros de compte</strong>
                                        <br>
                                        <p style="background-color: rgb(173, 125, 125)" class="text-muted">
                                            {{ $compte->account_number }}</p>
                                    </div>
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Nom du client</strong>
                                        <br>
                                        <p class="text-muted">
                                            {{ $compte->client->user->last_name . ' ' . $compte->client->user->first_name }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Benefice sur le compte</strong>
                                        <br>
                                        <p class="text-muted">{{ $compte->beniefice_accumule }} </p>
                                    </div>
                                    <div class="col-md-6 col-6 b-r">
                                        <strong>Solde Total</strong>
                                        <br>
                                        <p class="text-muted">{{ $compte->solde_actuelle }}</p>
                                    </div>
                                </div>





                            </div>

                            <input type="hidden" name="id_compte" value="{{$compte->id}}">
<br>

<div class="form-group col-md-12">
    <label>La somme à verser</label>
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


                        </div>



            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Ajouter au compte</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
