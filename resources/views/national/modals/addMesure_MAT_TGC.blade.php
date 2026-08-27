<div class="modal fade bd-example-modal-lg" id="addMesurModal_mat_tgc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; " id="myLargeModalLabel">AJOUT D'UNE MESURE - MAT → TGC</h6>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('mesure') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    <input type="hidden" name="direction" value="MAT->TGC">

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="periode_mat_tgc">Période (YYYY-MM)</label>
                            <input id="periode_mat_tgc" name="periode" type="month" class="form-control" value="{{ old('periode') }}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="m_mat_mat_tgc">Déclaration MAT</label>
                            <input id="m_mat_mat_tgc" name="m_mat" type="number" step="0.01" min="0" lang="en" class="form-control" value="{{ old('m_mat') }}" required>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="m_tgc_mat_tgc">Mesure TGC</label>
                            <input id="m_tgc_mat_tgc" name="m_tgc" type="number" step="0.01" min="0" lang="en" class="form-control" value="{{ old('m_tgc') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="m_tgt_mat_tgc">Référence / Valeur de comparaison</label>
                            <input id="m_tgt_mat_tgc" name="m_tgt" type="number" step="0.01" min="0" lang="en" class="form-control" value="{{ old('m_tgt') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Commentaire sur la mesure</label>
                            <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end container-fluid">
                        <button type="submit" class="btn btn-primary waves-effect mb-2">Ajouter la mesure</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
