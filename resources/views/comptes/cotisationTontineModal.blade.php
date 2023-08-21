<div class="modal fade bd-example-modal-lg" id="{{ 'cotisationTontineModal' . $compte->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="myLargeModalLabel">Cotisation sur le compte tontine </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="cotisation_tontine" enctype="multipart/form-data" method="post">
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
                                        <strong>Taux de cotisantion</strong>
                                        <br>
                                        <p class="text-muted">{{ $compte->taux_cotisation }} par jours</p>
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
                                <label for="inputEmail4"> Nombre de jours</label>
                                <select name="nombre_de_jour" id="inputState" class="form-control">
                                    <option value="1">1 jours</option>
                                    <option value="2">2 jours</option>
                                    <option value="3">3 jours</option>
                                    <option value="4">4 jours</option>
                                    <option value="5">5 jours</option>
                                    <option value="6">6 jours</option>
                                    <option value="7">7 jours</option>
                                    <option value="8">8 jours</option>
                                    <option value="9">9 jours</option>
                                    <option value="10">10 jours</option>

                                    <option value="11">11 jours</option>
                                    <option value="12">12 jours</option>
                                    <option value="13">13 jours</option>
                                    <option value="14">14 jours</option>
                                    <option value="15">15 jours</option>
                                    <option value="16">16 jours</option>
                                    <option value="17">17 jours</option>
                                    <option value="18">18 jours</option>
                                    <option value="19">19 jours</option>
                                    <option value="20">20 jours</option>

                                    <option value="21">21 jours</option>
                                    <option value="22">22 jours</option>
                                    <option value="23">23 jours</option>
                                    <option value="24">24 jours</option>
                                    <option value="25">25 jours</option>
                                    <option value="26">26 jours</option>
                                    <option value="27">27 jours</option>
                                    <option value="28">28 jours</option>
                                    <option value="29">29 jours</option>
                                    <option value="30">30 jours</option>
                                    <option value="31">31 jours</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Retirer la commission(la cotisation d'une journée)</label>
                                <select name="comission" id="inputState" class="form-control">
                                    <option value="1">Oui</option>

                                    <option selected value="0">Non</option>


                                </select>
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
