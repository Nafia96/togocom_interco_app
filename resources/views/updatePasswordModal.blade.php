<div class="modal fade bd-example-modal-lg" id="{{ 'updatePasswordModal' . $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" id="mySmallModalLabel">Modification du mot de passe</h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="cotisation_tontine" enctype="multipart/form-data" method="post">
                    @csrf

                    @if (isset($user))




                        <div class="form-group col-md-4">
                            <label for="inputEmail4">Entrer l'encien mot de passe</label>
                            <input name="last_name" type="password" class="form-control  @error('last_name') is-invalid @enderror"
                                value="{{ @old('last_name') }}" placeholder="" required>

                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail10">Entrer le nouveau mot de passe </label>
                            <input name="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                                placeholder="" value="{{ @old('first_name') }}" required>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="inputEmail10">Confirmer le nouveau mot de passe </label>
                            <input name="first_name" type="password" class="form-control  @error('password_confirmation') is-invalid @enderror"
                                placeholder="" value="{{ @old('password_confirmation') }}" required>

                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>











            </div>


            <div class="d-flex justify-content-end container-fluid">

                <button type="submit" class="btn btn-primary waves-effect mb-2">Sauvegarder</button>
            </div>
        </div>
        @endif

        </form>
    </div>
</div>
</div>
</div>
