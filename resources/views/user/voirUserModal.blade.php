<div class="modal fade bd-example-modal-lg" id="{{ 'voirUserModal'.$user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="color: #03a04f">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="myLargeModalLabel">LES DETAILS DE L'UTILISATEUR </h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($user))

            



                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active" id="about" role="tabpanel"
                            aria-labelledby="home-tab2" style="background-color:#E5E8E8;  border-radius: 13px;">


                            <div class="row">

                                <div class="col-md-6 col-6 b-r" style="color:#03a04f; font-weight: bold; ">
                                    <strong>Nom de l'utilisateur</strong>
                                    <br>
                                    <p style="color:#ec1f28; font-weight: bold; ">{{ $user->last_name.' '. $user->first_name}}</p>
                                </div>

                                <div class="col-md-6 col-6 b-r">
                                    <strong>Poste</strong>
                                    <br>
                                    <p style="color:#ec1f28; font-weight: bold; ">{{ $user->post}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Telephone</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $user->tel }}</p>
                                </div>

                                <div class="col-md-6 col-6 b-r">
                                    <strong>Email </strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $user->email }}</p>
                                </div>

                       

                            </div>

  

                            <div class="row">
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Créer le :</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $user->created_at }}</p>
                                </div>
                                <div class="col-md-6 col-6 b-r">
                                    <strong>Dernière modification</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $user->updated_at }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-12 b-r  text-center  m-auto">
                                    <strong>Description</strong>
                                    <br>
                                    <p  style="color:#ec1f28; font-weight: bold; ">{{ $user->description }}</p>
                                </div>

                                

                            </div>








                            
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
