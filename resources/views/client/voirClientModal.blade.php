<div class="modal fade bd-example-modal-lg" id="{{ 'voirClientModal' . $client->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="myLargeModalLabel">Les détails sur le client </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($client))

                    <div class="mt-3  col-12 text-center  m-auto">
                        <img style="height: 200px; width: 200px; margin-bottom: 10px" alt="image"
                            src="{{ asset($client->user->avatar) }}"
                        class=" rounded-circle author-box-picture">
                        <div class="clearfix"></div>
                        <div class="author-box-name mb-3">
                            <h5>{{ $client->user->last_name }} {{ $client->user->first_name }}</h5>
                        </div>
                    </div>


                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2"
                            style="background-color:#E5E8E8;  border-radius: 13px;">
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Agence</strong>
                                    <br>
                                    <p style="background-color: rgb(173, 125, 125)" class="text-muted">
                                        {{ $client->agence->nom }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Nom du client</strong>
                                    <br>
                                    <p class="text-muted">
                                        {{ $client->user->last_name . ' ' . $client->user->first_name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Telephone</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->user->tel }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Adresse de collecte</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->adresse_collecte }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Type de carte</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->user->type_carte}}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Numéros de carte</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->user->card_number}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Email</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->user->email }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Adresse</strong>
                                    <br>
                                    <p class="text-muted">
                                        {{ $client->user->adresse . ' / ' . $client->user->ville }}-Togo</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Type de client</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->type_client }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Activité commercial</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->occupation }}</p>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Créer le :</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->created_at }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Dernière modification</strong>
                                    <br>
                                    <p class="text-muted">{{ $client->updated_at }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Ajouter par :</strong>
                                    <br>

                                    @if ($client->type_of_add_by == 2)

                                        <p class="text-muted">Le chef d'agence</p>
                                    @endif
                                    @if ($client->type_of_add_by == 3)

                                        <p class="text-muted">{{ getAddingAgent($client->id_agent) }}</p>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
