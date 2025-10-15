<div class="modal fade bd-example-modal-lg" id="addMesurModal11" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row container-fluid mt-3 ">
                    <div class="mt-3 col-12 text-center  m-auto">
                        <h6 class="modal-title" style="background-color:#03a04f; color: aliceblue ; " id="myLargeModalLabel">AJOUT D'UNE MESURE</h6>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('mesure') }}" enctype="multipart/form-data" method="post">
                    @csrf

                    <input type="hidden" name="direction" id="measure_direction_input" value="TGT->TGC">

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="periode">Période (YYYY-MM)</label>
                            <input id="periode" name="periode" type="month" class="form-control @error('periode') is-invalid @enderror" value="{{ old('periode') }}" required>
                            @error('periode')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="m_tgt">Déclaration TGT</label>
                            <input id="m_tgt" name="m_tgt" type="number" step="0.01" min="0" lang="en" class="form-control @error('m_tgt') is-invalid @enderror" value="{{ old('m_tgt') }}" required>
                            @error('m_tgt')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="m_tgc">Mesure de TGC</label>
                            <input id="m_tgc" name="m_tgc" type="number" step="0.01" min="0" lang="en" class="form-control @error('m_tgc') is-invalid @enderror" value="{{ old('m_tgc') }}" required>
                            @error('m_tgc')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Commentaire sur la mesure</label>
                            <textarea name="comment" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
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

<script>
    $(document).on('show.bs.modal', '#addMesurModal11', function (event) {
        var button = $(event.relatedTarget);
        var direction = button ? button.data('direction') : null;
        if(!direction) direction = 'TGT->TGC';
        $('#measure_direction_input').val(direction);
        $(this).find('#myLargeModalLabel').text("AJOUT D'UNE MESURE - " + direction.replace('->', ' VERS '));
    });
</script>
