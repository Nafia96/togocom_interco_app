<div class="modal fade bd-example-modal-lg"  id="{{'voirDepotModal'.$compte->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">

            <div class="row container-fluid mt-3 ">
                <div class="mt-3 col-12 text-center  m-auto" >
                    <h6 class="modal-title" id="myLargeModalLabel">Les détails sur le compte dépôt </h6>
                </div>
            </div>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
           @if(isset($compte))


        <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2" style="background-color:#E5E8E8;  border-radius: 13px;">
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Numéros de compte</strong>
                        <br>
                        <p style="background-color: rgb(173, 125, 125)" class="text-muted">{{ $compte->account_number }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Nom du client</strong>
                        <br>
                        <p class="text-muted">{{ $compte->client->last_name.' '.$compte->client->first_name }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Solde Total</strong>
                        <br>
                        <p class="text-muted">{{ $compte->solde_actuelle }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Total des retraits</strong>
                        <br>
                        <p class="text-muted">{{ $compte->somme_retire }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Benefice accumulé</strong>
                        <br>
                        <p class="text-muted">{{ $compte->beniefice_accumule }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Nombre de retrait</strong>
                        <br>
                        <p class="text-muted">{{ $compte->nombre_de_retrait }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Agence </strong>
                        <br>
                        <p class="text-muted">{{ $compte->agence->nom}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Début de dépôt</strong>
                        <br>
                        <p class="text-muted">{{ $compte->debut_de_cotisation }}</p>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Créer le :</strong>
                        <br>
                        <p class="text-muted">{{ $compte->created_at}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Dernière modification</strong>
                        <br>
                        <p class="text-muted">{{ $compte->updated_at }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Ajouter par :</strong>
                        <br>

                        <p class="text-muted">{{ $compte->user_add_by->last_name.' '.$compte->user_add_by->first_name}}</p>

                    </div>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>
