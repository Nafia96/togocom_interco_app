<div class="modal fade bd-example-modal-lg"  id="{{'voiragentModal'.$agent->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">

            <div class="row container-fluid mt-3 ">
                <div class="mt-3 col-12 text-center  m-auto" >
                    <h6 class="modal-title" id="myLargeModalLabel">Les détails sur l'agent </h6>
                </div>
            </div>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
           @if(isset($agent))


           <div class="mt-3  col-12 text-center  m-auto">
            <img style="height: 200px; width: 200px; margin-bottom: 10px" alt="image"
            src="{{ asset($agent->user->avatar) }}"
            class=" rounded-circle author-box-picture">
            <div class="clearfix"></div>
            <div class="author-box-name mb-3">
                <h5>{{ $agent->user->last_name }} {{ $agent->user->first_name }}</h5>
            </div>
        </div>

        <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2" style="background-color:#E5E8E8;  border-radius: 13px;">
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Agence</strong>
                        <br>
                        <p style="background-color: rgb(173, 125, 125)" class="text-muted">{{ $agent->agence->nom }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Nom de l'agent</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->last_name.' '.$agent->user->first_name }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Telephone</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->tel }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Zone de collecte</strong>
                        <br>
                        <p class="text-muted">{{ $agent->zone}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Email</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->email }}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Adresse</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->adresse.' / '.$agent->user->ville }}-Togo</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Type de carte</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->type_carte}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Numéros de carte</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->card_number}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Type de contrat</strong>
                        <br>
                        <p class="text-muted">{{ $agent->type_contat}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Niveau d'étude</strong>
                        <br>
                        <p class="text-muted">{{ $agent->user->email }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Date de naissance</strong>
                        <br>
                        <p class="text-muted">{{ $agent->date_naissance}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Nombre de client</strong>
                        <br>
                        <p class="text-muted"> 001</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6 b-r">
                        <strong>Créer le :</strong>
                        <br>
                        <p class="text-muted">{{ $agent->created_at}}</p>
                    </div>
                    <div class="col-md-6 col-6 b-r">
                        <strong>Dernière modification</strong>
                        <br>
                        <p class="text-muted">{{ $agent->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>
