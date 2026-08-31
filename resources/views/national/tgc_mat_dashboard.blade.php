@extends('template.principal_tamplate3')

@section('title')
    TGC-MAT Dashboard
@endsection

@section('content')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><i class="fas fa-list"></i> Situation de : TOGOCOCEL VERS MAT</li>

            <div class="d-flex justify-content-end container-fluid mt-n3">
                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                    <a data-toggle="modal" data-target="#addMesurModal_tgc_mat">
                        <button type="button" class="btn btn-dark mx-1">+ AJOUTER MESURE</button>
                    </a>
                @endif
            </div>
        </ol>
    </nav>
@stop
@include('national.modals.addMesure_TGC_MAT')

<div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold;">MESURES DE TOGOCOCEL</h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p style="white-space: nowrap;"><span>
                        {{ isset($tgc_mat_sums['year']) ? number_format($tgc_mat_sums['year'], 2, ',', ' ') : '0,00' }}
                    </span></p>
                </div>
                <div style="font-size: 100%" class="mb-1 card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span>{{ isset($tgc_mat_sums['total']) ? number_format($tgc_mat_sums['total'], 2, ',', ' ') : '0,00' }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold;">ECART (TGC - MAT)</h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p><span style="white-space: nowrap; color:#03a04f">{{ isset($ecart_sums['year']) ? number_format($ecart_sums['year'], 2, ',', ' ') : '0,00' }}</span></p>
                </div>
                <div style="font-size: 100%" class="card-body pull-center">
                    Total : <br>
                    <p><span style="white-space: nowrap; color:#03a04f">{{ isset($ecart_sums['total']) ? number_format($ecart_sums['total'], 2, ',', ' ') : '0,00' }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">
            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold;">MESURE DE MAT</h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p style="white-space: nowrap;"><span>{{ isset($mat_sums['year']) ? number_format($mat_sums['year'], 2, ',', ' ') : '0,00' }}</span></p>
                </div>
                <div style="font-size: 100%" class="card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span>{{ isset($mat_sums['total']) ? number_format($mat_sums['total'], 2, ',', ' ') : '0,00' }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 col-sm-12 col-lg-12" style="margin-left: 0px;">
    <div class="card">
        <div class="card-header">
            <h4>Togocel vers MAT</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex mb-2">
                    <form id="generateInvoiceForm_TGC_MAT" method="POST" action="{{ url('measures/generate_invoice') }}">
                        @csrf
                        <input type="hidden" name="selected_ids" id="selected_ids_input_TGC_MAT" value="">
                        <input type="hidden" name="direction" value="TGC->MAT">
                        <button id="generateInvoiceBtn_TGC_MAT" type="button" class="btn btn-success btn-sm">Générer facture (sélection)</button>
                    </form>
                </div>

                <div id="invoiceLoaderOverlay_TGC_MAT" style="display:none; position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:300000;align-items:center;justify-content:center;">
                    <div style="text-align:center;color:#fff">
                        <div class="spinner-border text-light" role="status" style="width:4rem;height:4rem;"></div>
                        <div style="margin-top:12px;font-size:1.1rem">Génération de la facture en cours... cela peut prendre quelques instants</div>
                        <div style="margin-top:8px;font-size:0.9rem">Ne pas fermer cette fenêtre.</div>
                    </div>
                </div>

                <table class="table table-striped table-hover" id="tableExpor1_TGC_MAT" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width:1%"><input type="checkbox" id="select_all_rows_TGC_MAT"></th>
                            <th class="recherche">N°</th>
                            <th class="recherche">PÉRIODES</th>
                            <th class="recherche">DECLARATION TGC(1)</th>
                            <th class="recherche">MESURES MAT(2)</th>
                            <th class="recherche">ECART(2-1)</th>
                            <th class="recherche">...%...</th>
                            <th class="recherche">TRAFIC VALIDÉ ET FACTURÉ</th>
                            <th class="recherche">VALORISATION</th>
                            <th class="recherche">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; ?>
                        @if(isset($measures) && $measures->count() > 0)
                            @foreach($measures as $m)
                                <tr>
                                    <td><input type="checkbox" class="select-row-TGC-MAT" value="{{ $m->id }}"></td>
                                    <td style="width:1%">{{ $n++ }}</td>
                                    @php
                                        try {
                                            $displayPeriod = \Carbon\Carbon::createFromFormat('Y-m', $m->period)->format('M-Y');
                                        } catch (\Exception $e) {
                                            $displayPeriod = $m->period;
                                        }
                                    @endphp
                                    <td>{{ $displayPeriod }}</td>
                                    <td class="text-end">{{ number_format($m->m_tgc, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->m_mat, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->diff, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($m->pct_diff, 2, ',', ' ') }}%</td>
                                    @php
                                        $useMeasured = abs(floatval($m->pct_diff)) < 2.0;
                                    @endphp
                                    <td class="text-end">
                                        @if($useMeasured)
                                            {{ number_format($m->m_mat, 2, ',', ' ') }}
                                        @else
                                            @if($m->traffic_validated !== null)
                                                {{ number_format($m->traffic_validated, 2, ',', ' ') }}
                                            @else
                                                <button class="btn btn-sm btn-outline-primary set-validated-btn" data-id="{{ $m->id }}" data-period="{{ $m->period }}">Saisir</button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ number_format($m->valuation ?? 0, 2, ',', ' ') }}</td>
                                    <td style="width:10%">
                                        <span data-toggle="tooltip" data-placement="top" title="Voir commentaire">
                                            <a href="#" class="btn btn-sm btn-primary view-comment-btn" data-display-period="{{ e($displayPeriod) }}" data-period="{{ $m->period }}" data-m_tgc="{{ $m->m_tgc }}" data-m_tgt="{{ $m->m_tgt }}" data-comment="{{ e($m->comment ?? '') }}"><i class="fas fa-comment text-white"></i></a>
                                        </span>
                                        <span data-toggle="tooltip" data-placement="top" title="Modifier mesure">
                                            <a href="#" class="btn btn-sm btn-warning edit-measure-btn" data-id="{{ $m->id }}" data-period="{{ $m->period }}" data-m_tgc="{{ $m->m_tgc }}" data-m_tgt="{{ $m->m_tgt }}" data-comment="{{ e($m->comment ?? '') }}"><i class="fas fa-edit text-white"></i></a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="text-center">Aucune mesure disponible.</td>
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
<script>
    const commentModalHtml_TGC_MAT = `
    <div class="modal fade" id="viewCommentModal_TGC_MAT" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Commentaire</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="viewCommentContent_TGC_MAT"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>`;
    $('body').append(commentModalHtml_TGC_MAT);

    const editModalHtml_TGC_MAT = `
    <div class="modal fade" id="editMeasureModal_TGC_MAT" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier mesure</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="editMeasureForm_TGC_MAT" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Période</label>
                            <input type="month" name="periode" id="edit_periode_TGC_MAT" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Déclaration TGC</label>
                            <input type="number" step="0.01" min="0" name="m_tgc" id="edit_m_tgc_TGC_MAT" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Mesure MAT</label>
                            <input type="number" step="0.01" min="0" name="m_mat" id="edit_m_mat_TGC_MAT" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Trafic validé</label>
                            <input type="number" step="0.01" min="0" name="traffic_validated" id="edit_traffic_validated_TGC_MAT" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Commentaire sur la validation</label>
                            <textarea name="validation_comment" id="edit_validation_comment_TGC_MAT" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Commentaire général</label>
                            <textarea name="comment" id="edit_comment_TGC_MAT" class="form-control" rows="2"></textarea>
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
    $('body').append(editModalHtml_TGC_MAT);

    $(document).ready(function () {
        const modalHtml = `
        <div class="modal fade" id="setValidatedModal_TGC_MAT" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Saisir Trafic validé</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="setValidatedForm_TGC_MAT" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Valeur trafic validé</label>
                                <input type="number" step="0.01" min="0" name="traffic_validated" id="traffic_validated_input_TGC_MAT" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Commentaire (optionnel)</label>
                                <textarea name="validation_comment" id="validation_comment_input_TGC_MAT" class="form-control" rows="2"></textarea>
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

        $(document).on('click', '.set-validated-btn', function () {
            const id = $(this).data('id');
            const action = "{{ url('measures') }}" + "/" + id + "/set_validated";
            $('#setValidatedForm_TGC_MAT').attr('action', action);
            $('#traffic_validated_input_TGC_MAT').val('');
            $('#validation_comment_input_TGC_MAT').val('');
            $('#setValidatedModal_TGC_MAT').modal('show');
        });

        $(document).on('click', '.view-comment-btn', function () {
            const id = $(this).closest('td').find('.edit-measure-btn').data('id') || $(this).data('id');
            const displayPeriod = $(this).data('display-period') || '';
            const m_tgc = $(this).data('m_tgc') || '';
            const m_tgt = $(this).data('m_tgt') || '';

            $('#viewCommentContent_TGC_MAT').html('<p>Chargement...</p>');
            $('#viewCommentModal_TGC_MAT').modal('show');

            $.getJSON("{{ url('measures') }}" + "/" + id + "/audits", function (resp) {
                const measureComment = resp.measure_comment || 'Aucun commentaire.';
                const audits = resp.audits || [];

                let html = '';
                html += `<p><strong>Période :</strong> ${displayPeriod}</p>`;
                html += `<p><strong>Déclaration TGC :</strong> ${Number(m_tgc).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2})}</p>`;
                html += `<p><strong>Mesure MAT :</strong> ${Number(m_tgt).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2})}</p>`;
                html += '<hr>';
                html += `<h6>Commentaire (saisie mesure)</h6><p>${measureComment}</p>`;

                if (audits.length > 0) {
                    html += '<hr><h6>Commentaires de validation</h6>';
                    html += '<ul class="list-unstyled small">';
                    audits.forEach(function (a) {
                        const when = new Date(a.created_at).toLocaleString('fr-FR');
                        const user = a.changed_by || 'Utilisateur';
                        const oldv = a.old_value !== null ? Number(a.old_value).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
                        const newv = a.new_value !== null ? Number(a.new_value).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
                        const commentVal = a.comment || '';
                        html += `<li class="mb-2"><strong>${when}</strong> — ${user}<br/>Valeur: ${oldv} → ${newv}<br/>${commentVal}</li>`;
                    });
                    html += '</ul>';
                }

                $('#viewCommentContent_TGC_MAT').html(html);
            }).fail(function () {
                $('#viewCommentContent_TGC_MAT').html('<p>Impossible de charger les commentaires.</p>');
            });
        });

        $(document).on('click', '.edit-measure-btn', function () {
            const id = $(this).data('id');
            const period = $(this).data('period');
            const m_tgc = $(this).data('m_tgc');
            const m_tgt = $(this).data('m_tgt');
            const comment = $(this).data('comment');
            const traffic_validated = $(this).closest('tr').find('td:eq(7)').text().trim().replace(/[^\d,]/g, '').replace(',', '.') || '';

            const action = "{{ url('measures') }}" + "/" + id + "/update";
            $('#editMeasureForm_TGC_MAT').attr('action', action);
            $('#edit_periode_TGC_MAT').val(period);
            $('#edit_m_tgc_TGC_MAT').val(m_tgc);
            $('#edit_m_mat_TGC_MAT').val(m_tgt);
            $('#edit_traffic_validated_TGC_MAT').val(traffic_validated);
            $('#edit_validation_comment_TGC_MAT').val('');
            $('#edit_comment_TGC_MAT').val(comment);
            $('#editMeasureModal_TGC_MAT').modal('show');
        });
    });

    $('#generateInvoiceBtn_TGC_MAT').on('click', function (e) {
        const $btn = $(this);
        const selected = $('.select-row-TGC-MAT:checked').map(function () { return $(this).val(); }).get();
        if (!selected || selected.length === 0) {
            alert('Veuillez sélectionner au moins une ligne pour générer la facture.');
            return;
        }

        $('#selected_ids_input_TGC_MAT').val(selected.join(','));
        $('#invoiceLoaderOverlay_TGC_MAT').css('display', 'flex');
        $btn.prop('disabled', true).text('Génération en cours...');

        const fallback = setTimeout(function () {
            $('#invoiceLoaderOverlay_TGC_MAT').hide();
            $btn.prop('disabled', false).text('Générer facture (sélection)');
            alert('La génération prend trop de temps. Vérifiez le serveur ou réessayez.');
        }, 180000);

        $('#generateInvoiceForm_TGC_MAT').submit();
    });

    $('#tableExpor1_TGC_MAT thead tr .recherche').clone(true).appendTo('#tableExpor1_TGC_MAT thead').addClass('rech');
    $('#tableExpor1_TGC_MAT thead .rech').each(function (i) {
        const title = $(this).text();
        $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title + '" />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table.column(i).search(this.value).draw();
            }
        });
    });

    const table = $('#tableExpor1_TGC_MAT').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        language: {
            emptyTable: 'Aucune donnée disponible dans le tableau',
            lengthMenu: 'Afficher _MENU_ éléments',
            loadingRecords: 'Chargement...',
            processing: 'Traitement...',
            zeroRecords: 'Aucun élément correspondant trouvé',
            paginate: {
                first: 'Premier',
                last: 'Dernier',
                next: 'Suivant',
                previous: 'Précédent'
            },
            aria: {
                sortAscending: ': activer pour trier la colonne par ordre croissant',
                sortDescending: ': activer pour trier la colonne par ordre décroissant'
            }
        }
    });

    $(document).on('change', '#select_all_rows_TGC_MAT', function () {
        const checked = $(this).is(':checked');
        $('input.select-row-TGC-MAT').prop('checked', checked);
    });
</script>
@stop
