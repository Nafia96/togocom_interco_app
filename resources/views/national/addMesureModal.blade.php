<div class="modal fade bd-example-modal-lg" id="addMesurModal11" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; "
                            id="myLargeModalLabel">AJOUT D'UNE MESURE DE TOGOTELECOM VER TOGOCEL</h6>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('mesure_tgt_tgc') }}" enctype="multipart/form-data" method="post">

                    @csrf


                    <div class="tab-content tab-bordered" id="myTab3Content">

                        <br>


                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label>Periode :</label>
                                <input class="form-control" type="month" id="start" name="periode" min="2020-01" value="{{ date('Y-m') }}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mesure de TGT :</label>
                                 <input name="m_tgt" type="number" step="0.01" min="0"
                                            lang="en" class="form-control  @error('m_tgt') is-invalid @enderror"
                                            value="{{ @old('m_tgt') }}" placeholder="" required>

                                        @error('m_tgt')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                            </div>


                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Mesure de TGC</label>
                                <input name="m_tgc"  type="number" step="0.01" min="0"
                                            lang="en" class="form-control  @error('m_tgt') is-invalid @enderror"
                                            value="{{ @old('m_tgt') }}" placeholder="" required>

                                @error('m_tgc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-12">
                                <label>Commentaire sur la facture</label>
                                <textarea name="comment" class="form-control @error('comment') is-invalid @enderror">{{ @old('comment') }}</textarea>

                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>


                    </div>

                    <div class="d-flex justify-content-end container-fluid">

                        <button type="submit" class="btn btn-primary waves-effect mb-2">Ajouter la facture</button>
                    </div>


                </form>
            </div>



        </div>



    </div>
</div>
