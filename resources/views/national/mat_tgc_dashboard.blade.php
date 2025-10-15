@extends('template.principal_tamplate3')

@section('title')

    MAT-TGC Dashboard

@endsection

@section('content')


@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item " aria-current="page"><i class="fas fa-list"></i> Situation de :
                MAT VERS TOGOCOCEL</li>
            <div class="d-flex justify-content-end container-fluid mt-n3">
                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                    <a data-toggle="modal" data-target="#addMesurModal11" data-direction="MAT->TGC"> <button type="button" class=" btn btn-dark mx-1">+ AJOUTER
                            MESURE</button></a>
                @endif
            </div>
        </ol>
    </nav>
@stop
<div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">

            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">MESURES DE MAT
                    </h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p style="white-space: nowrap;"><span>
                            000
                        </span></p>

                </div>

                <div style="font-size: 100%" class=" mb-1 card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span> 000 </span></p>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">

            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">ECART (MAT - TGC)
                    </h4>
                </div>


                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p>

                        <span style="white-space: nowrap; color:#03a04f">00
                        </span>

                    </p>

                </div>

                <div style="font-size: 100%" class="card-body pull-center">

                    Total : <br>


                    <p>
                        <span style="white-space: nowrap; color:#03a04f">000

                    </p>

                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">

            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">MESURE DE TOGOCOCEL
                    </h4>
                </div>
                <div style="font-size: 140%" class="  card-body pull-center">

                    <br>
                    Année en cours : <br>
                    <p style="white-space: nowrap;"><span>

                            000 </span></p>


                </div>

                <div style="font-size: 100%" class="card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span>
                            000 </span></p>

                </div>
            </div>

        </div>
    </div>
</div>


<div class="col-12 col-sm-12 col-lg-12" style="margin-left: 0px;">
    <div class="card">
        <div class="card-header">
            <h4>MAT vers Togocel</h4>

        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tableExpor1" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="recherche">N°</th>
                            <th class="recherche">PÉRIODES</th>
                            <th class="recherche">DECLARATION MAT(1)</th>
                            <th class="recherche">MESURES TGC(2)</th>
                            <th class="recherche">ECART(2-1)</th>
                            <th class="recherche">...%...</th>
                            <th class="recherche">COMMENTAIRE</th>
                            <th class="recherche">TRAFIC VALIDÉ ET FACTURÉ</th>
                            <th class="recherche">VALORISATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; ?>
                        @if(isset($measures) && $measures->count() > 0)
                            @foreach($measures as $m)
                                <tr>
                                    <td>{{ $n++ }}</td>
                                    @php
                                        try {
                                            $displayPeriod = \Carbon\Carbon::createFromFormat('Y-m', $m->period)->format('M-Y');
                                        } catch (\Exception $e) {
                                            $displayPeriod = $m->period;
                                        }
                                    @endphp
                                    <td>{{ $displayPeriod }}</td>
                                    <td class="text-end">{{ number_format($m->m_tgt, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->m_tgc, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->diff, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->pct_diff, 2, ',', ' ') }}%</td>
                                    <td>{{ $m->comment }}</td>
                                    @php
                                        $useMeasured = abs(floatval($m->pct_diff)) < 2.0;
                                    @endphp
                                    <td class="text-end">
                                        @if($useMeasured)
                                            {{ number_format($m->m_tgc, 2, ',', ' ') }}
                                        @else
                                            @if($m->traffic_validated !== null)
                                                {{ number_format($m->traffic_validated, 2, ',', ' ') }}
                                            @else
                                                <button class="btn btn-sm btn-outline-primary set-validated-btn" data-id="{{ $m->id }}" data-period="{{ $m->period }}">Saisir</button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ number_format($m->valuation ?? 0, 2, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">Aucune mesure disponible.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>



@endsection

@section('script')
@parent
<script>
    $(function(){
        const modalHtml = `
        <div class="modal fade" id="setValidatedModal_MAT_TGC" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Saisir Trafic validé</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="setValidatedForm" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Valeur trafic validé</label>
                                <input type="number" step="0.01" min="0" name="traffic_validated" id="traffic_validated_input_MAT_TGC" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Commentaire (optionnel)</label>
                                <textarea name="validation_comment" id="validation_comment_input_MAT_TGC" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>`;
        $('body').append(modalHtml);

        $(document).on('click', '.set-validated-btn', function(){
            const id = $(this).data('id');
            const action = "{{ url('measures') }}" + "/" + id + "/set_validated";
            $('#setValidatedForm_MAT_TGC').attr('action', action);
            $('#traffic_validated_input_MAT_TGC').val('');
            $('#validation_comment_input_MAT_TGC').val('');
            $('#setValidatedModal_MAT_TGC').modal('show');
        });

        $(document).on('submit', '#setValidatedForm', function(e){
            e.preventDefault();
            const form = this;
            const value = $('#traffic_validated_input_MAT_TGC').val();
            const comment = $('#validation_comment_input_MAT_TGC').val();

            swal({
                title: 'Confirmer la validation',
                text: `Valider trafic = ${value}` + (comment ? `\nCommentaire: ${comment}` : ''),
                icon: 'warning',
                buttons: true,
                dangerMode: false,
            }).then((willConfirm) => {
                if (willConfirm) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection
