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

                    <input type="hidden" name="direction" id="measure_direction_input" value="">

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="periode">Période (YYYY-MM)</label>
                            <input id="periode" name="periode" type="month" class="form-control @if(isset($errors) && $errors->has('periode')) is-invalid @endif" value="{{ old('periode') }}" required>
                            @if(isset($errors) && $errors->has('periode'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('periode') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label id="label_m_tgt" for="m_tgt">Déclaration TGT</label>
                            <input id="m_tgt" name="m_tgt" type="number" step="0.01" min="0" lang="en" class="form-control @if(isset($errors) && $errors->has('m_tgt')) is-invalid @endif" value="{{ old('m_tgt') }}" required>
                            @if(isset($errors) && $errors->has('m_tgt'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('m_tgt') }}</strong></span>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label id="label_m_tgc" for="m_tgc">Mesure de TGC</label>
                            <input id="m_tgc" name="m_tgc" type="number" step="0.01" min="0" lang="en" class="form-control @if(isset($errors) && $errors->has('m_tgc')) is-invalid @endif" value="{{ old('m_tgc') }}">
                            @if(isset($errors) && $errors->has('m_tgc'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('m_tgc') }}</strong></span>
                            @endif

                            <label id="label_m_mat" for="m_mat" style="display:none; margin-top:8px;">Mesure MAT</label>
                            <input id="m_mat" name="m_mat" type="number" step="0.01" min="0" lang="en" class="form-control @if(isset($errors) && $errors->has('m_mat')) is-invalid @endif" value="{{ old('m_mat') }}" style="display:none;">
                            @if(isset($errors) && $errors->has('m_mat'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('m_mat') }}</strong></span>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Commentaire sur la mesure</label>
                            <textarea name="comment" class="form-control @if(isset($errors) && $errors->has('comment')) is-invalid @endif">{{ old('comment') }}</textarea>
                            @if(isset($errors) && $errors->has('comment'))
                                <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('comment') }}</strong></span>
                            @endif
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
        if(!direction) direction = '';
        $('#measure_direction_input').val(direction);
        $(this).find('#myLargeModalLabel').text("AJOUT D'UNE MESURE" + (direction ? ' - ' + direction.replace('->', ' VERS ') : ''));

        // Update labels according to direction
        function normalizeDir(d){
            if(!d) return '';
            var s = d.toString().toUpperCase().trim();
            s = s.replace(/\s*[\-_\/]+\s*/g,'->');
            s = s.replace(/\s*->\s*/g,'->');
            s = s.replace(/\s+/g,'');
            return s;
        }
        var d = normalizeDir(direction);
        // Labels must describe the operator for each numeric input
        // Note: `#m_tgt` always holds the TGT value, `#m_tgc` holds the TGC or MAT value
        var labelForTgt = 'Déclaration TGT';
        var labelForTgc = 'Mesure de TGC';

        if (d.indexOf('TGT->TGC') !== -1) {
            labelForTgt = 'Déclaration TGT';
            labelForTgc = 'Mesure de TGC';
            // show TGC input, hide MAT
            $('#m_tgc').show().prop('required', true);
            $('#label_m_tgc').show();
            $('#m_mat').hide().prop('required', false);
            $('#label_m_mat').hide();
        } else if (d.indexOf('TGC->TGT') !== -1) {
            // m_tgt is TGT (measured), m_tgc is TGC (declaration)
            labelForTgt = 'Mesure TGT';
            labelForTgc = 'Déclaration TGC';
        } else if (d.indexOf('TGT->MAT') !== -1) {
            labelForTgt = 'Déclaration TGT';
            labelForTgc = 'Mesure MAT';
            // show MAT input, hide TGC
            $('#m_mat').show().prop('required', true);
            $('#label_m_mat').show();
            $('#m_tgc').hide().prop('required', false);
            $('#label_m_tgc').hide();
        } else if (d.indexOf('MAT->TGT') !== -1) {
            labelForTgt = 'Mesure TGT';
            labelForTgc = 'Déclaration MAT';
            // show MAT input, hide TGC
            $('#m_mat').show().prop('required', true);
            $('#label_m_mat').show();
            $('#m_tgc').hide().prop('required', false);
            $('#label_m_tgc').hide();
        } else if (d) {
            // Generic: keep operator names if possible
            labelForTgt = 'Déclaration TGT';
            labelForTgc = 'Mesure';
            $('#m_tgc').show().prop('required', true);
            $('#label_m_tgc').show();
            $('#m_mat').hide().prop('required', false);
            $('#label_m_mat').hide();
        }

        $('#label_m_tgt').text(labelForTgt);
        $('#label_m_tgc').text(labelForTgc);
    });
</script>
