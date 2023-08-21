<div class="modal fade bd-example-modal-lg" id="{{ 'voirOperatorModal'.$operator->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="color: #fcca29">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="myLargeModalLabel">Les détails sur l'opérateur </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($operator))


                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active" id="about" role="tabpanel"
                            aria-labelledby="home-tab2" style="background-color:#E5E8E8;  border-radius: 13px;">
                            <div class="row">

                                <div class="col-md-6 col-6 b-r" style="color:#fcca29; font-weight: bold; ">
                                    <strong>Nom de l'opérateur</strong>
                                    <br>
                                    <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->name }}</p>
                                </div>

                                <div class="col-md-6 col-6 b-r">
                                    <strong>Adresse</strong>
                                    <br>
                                    <p style="color:#ec1f28; font-weight: bold; ">{{ $operator->adresse . ' / ' . $operator->country }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Telephone</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->tel }}</p>
                                </div>

                                <div class="col-md-6 col-6 b-r">
                                    <strong>Email</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->email }}</p>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Description</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->description }}</p>
                                </div>

                                <div class="col-md-6 col-6 b-r">
                                    <strong>La devise</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->currency }}</p>
                                </div>

                            </div>








                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Créer le :</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->created_at }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Dernière modification</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $operator->updated_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
