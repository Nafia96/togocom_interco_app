@extends('template.principal_tamplate3')

@section('title')

    TGT-TGC Dashboard

@endsection

@section('content')


@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">


            <li class="breadcrumb-item " aria-current="page"><i class="fas fa-list"></i> Situation de :
                TOGOTELECOM VERS TOGOCOCEL</li>


            <div class="d-flex justify-content-end container-fluid mt-n3">
                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                    <!-- Open add measure modal; form inside will post to generic mesure.route and include direction -->
            <a data-toggle="modal" data-target="#addMesurModal11" data-direction="TGT->TGC"> <button type="button" class=" btn btn-dark mx-1">+ AJOUTER
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
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">MESURES DE TOGOTELECOM
                    </h4>
                </div>
                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p style="white-space: nowrap;"><span>
                            {{ isset($tgt_tgc_sums['year']) ? number_format($tgt_tgc_sums['year'], 2, ',', ' ') : '0,00' }}
                        </span></p>

                </div>

                <div style="font-size: 100%" class=" mb-1 card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span> {{ isset($tgt_tgc_sums['total']) ? number_format($tgt_tgc_sums['total'], 2, ',', ' ') : '0,00' }} </span></p>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card card-statistic-2">

            <div class="card-wrap">
                <div class="card-header">
                    <h4 class="pull-left" style="color:#ec1f28; font-weight: bold; ">ECART (TGT - TGC)
                    </h4>
                </div>


                <div style="font-size: 140%" class="card-body pull-center">
                    <br>
                    Année en cours : <br>
                    <p>

                        <span style="white-space: nowrap; color:#03a04f">{{ isset($ecart_sums['year']) ? number_format($ecart_sums['year'], 2, ',', ' ') : '0,00' }}
                        </span>

                    </p>

                </div>

                <div style="font-size: 100%" class="card-body pull-center">

                    Total : <br>


                    <p>
                        <span style="white-space: nowrap; color:#03a04f">{{ isset($ecart_sums['total']) ? number_format($ecart_sums['total'], 2, ',', ' ') : '0,00' }}</span>

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

                            {{ isset($togococel_sums['year']) ? number_format($togococel_sums['year'], 2, ',', ' ') : '0,00' }} </span></p>


                </div>

                <div style="font-size: 100%" class="card-body pull-center">
                    Total : <br>
                    <p style="white-space: nowrap;"><span>
                            {{ isset($togococel_sums['total']) ? number_format($togococel_sums['total'], 2, ',', ' ') : '0,00' }} </span></p>

                </div>
            </div>

        </div>
    </div>
</div>


<div class="col-12 col-sm-12 col-lg-12" style="margin-left: 0px;">
    <div class="card">
        <div class="card-header">
            <h4>Togotelecom vers Togocel (Charges de Togotelecom) </h4>

        </div>
        <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex mb-2">
                <form id="generateInvoiceForm" method="POST" action="{{ url('measures/generate_invoice') }}">
                    @csrf
                    <input type="hidden" name="selected_ids" id="selected_ids_input" value="">
                    <input type="hidden" name="direction" value="TGT->TGC">
                    <button id="generateInvoiceBtn" type="button" class="btn btn-success btn-sm">Générer facture (sélection)</button>
                </form>
            </div>

            <!-- Loader overlay (hidden by default) -->
            <div id="invoiceLoaderOverlay" style="display:none; position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:300000;align-items:center;justify-content:center;">
                <div style="text-align:center;color:#fff">
                    <div class="spinner-border text-light" role="status" style="width:4rem;height:4rem;"></div>
                    <div style="margin-top:12px;font-size:1.1rem">Génération de la facture en cours... cela peut prendre quelques instants</div>
                    <div style="margin-top:8px;font-size:0.9rem">Ne pas fermer cette fenêtre.</div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="tableExpor1" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width:1%"><input type="checkbox" id="select_all_rows"></th>
                            <th class="recherche">N°</th>
                            <th class="recherche">PÉRIODES</th>
                            <th class="recherche">DECLARATION TGT(1)</th>
                            <th class="recherche">MESURES TGC(2)</th>
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
                                    <td><input type="checkbox" class="select-row" value="{{ $m->id }}"></td>
                                    <td style="width:1%">{{ $n++ }}</td>
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
                                    @php
                                        // Determine traffic_validated display logic: if absolute pct_diff < 2 => use measured (m_tgc)
                                        $useMeasured = abs(floatval($m->pct_diff)) < 2.0;
                                        $tv = $useMeasured ? $m->m_tgc : ($m->traffic_validated ?? null);
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
                                    <td style="width:10%">
                                        {{-- Icon actions (view comment, edit) --}}
                                        <span data-toggle="tooltip" data-placement="top" title="Voir commentaire">
                                            <a href="#" class="btn btn-sm btn-primary view-comment-btn" data-display-period="{{ e($displayPeriod) }}" data-period="{{ $m->period }}" data-m_tgt="{{ $m->m_tgt }}" data-m_tgc="{{ $m->m_tgc }}" data-comment="{{ e($m->comment ?? '') }}"><i class="fas fa-comment text-white"></i></a>
                                        </span>
                                        <span data-toggle="tooltip" data-placement="top" title="Modifier mesure">
                                            <a href="#" class="btn btn-sm btn-warning edit-measure-btn" data-id="{{ $m->id }}" data-period="{{ $m->period }}" data-m_tgt="{{ $m->m_tgt }}" data-m_tgc="{{ $m->m_tgc }}" data-comment="{{ e($m->comment ?? '') }}"><i class="fas fa-edit text-white"></i></a>
                                        </span>
                                    </td>

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
<script>
        // Comment view modal HTML (appended once)
        const commentModalHtml = `
        <div class="modal fade" id="viewCommentModal_TGT_TGC" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Commentaire</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="viewCommentContent"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>`;
        $('body').append(commentModalHtml);

        // Edit modal HTML
        const editModalHtml = `
        <div class="modal fade" id="editMeasureModal_TGT_TGC" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Modifier mesure</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="editMeasureForm_TGT_TGC" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Période</label>
                                <input type="month" name="periode" id="edit_periode_TGT_TGC" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Declaration TGT</label>
                                <input type="number" step="0.01" min="0" name="m_tgt" id="edit_m_tgt_TGT_TGC" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Mesure TGC</label>
                                <input type="number" step="0.01" min="0" name="m_tgc" id="edit_m_tgc_TGT_TGC" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Trafic validé</label>
                                <input type="number" step="0.01" min="0" name="traffic_validated" id="edit_traffic_validated_TGT_TGC" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Commentaire sur la validation</label>
                                <textarea name="validation_comment" id="edit_validation_comment_TGT_TGC" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Commentaire général</label>
                                <textarea name="comment" id="edit_comment_TGT_TGC" class="form-control" rows="2"></textarea>
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
        $('body').append(editModalHtml);

    $(document).ready(function() {
    // Modal for entering traffic validated
    $(function(){
    // append modal to body (unique to TGT->TGC)
    const modalHtml = `
    <div class="modal fade" id="setValidatedModal_TGT_TGC" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Saisir Trafic validé</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="setValidatedForm_TGT_TGC" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Valeur trafic validé</label>
                                <input type="number" step="0.01" min="0" name="traffic_validated" id="traffic_validated_input_TGT_TGC" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Commentaire (optionnel)</label>
                                <textarea name="validation_comment" id="validation_comment_input_TGT_TGC" class="form-control" rows="2"></textarea>
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
            $('#setValidatedForm_TGT_TGC').attr('action', action);
            $('#traffic_validated_input_TGT_TGC').val('');
            $('#validation_comment_input_TGT_TGC').val('');
            $('#setValidatedModal_TGT_TGC').modal('show');
        });

        // View comment button - fetch original comment + validation audits via AJAX and render
        $(document).on('click', '.view-comment-btn', function(){
            const id = $(this).closest('td').find('.edit-measure-btn').data('id') || $(this).data('id');
            const displayPeriod = $(this).data('display-period') || '';
            const m_tgt = $(this).data('m_tgt') || '';
            const m_tgc = $(this).data('m_tgc') || '';

            $('#viewCommentContent').html('<p>Chargement...</p>');
            $('#viewCommentModal_TGT_TGC').modal('show');

            $.getJSON("{{ url('measures') }}" + "/" + id + "/audits", function(resp){
                const measureComment = resp.measure_comment || 'Aucun commentaire.';
                const audits = resp.audits || [];

                let html = '';
                html += `<p><strong>Période :</strong> ${displayPeriod}</p>`;
                html += `<p><strong>Declaration TGT :</strong> ${Number(m_tgt).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2})}</p>`;
                html += `<p><strong>Mesure TGC :</strong> ${Number(m_tgc).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2})}</p>`;
                html += '<hr>';
                html += `<h6>Commentaire (saisie mesure)</h6><p>${measureComment}</p>`;

                if (audits.length > 0) {
                    html += '<hr><h6>Commentaires de validation</h6>';
                    html += '<ul class="list-unstyled small">';
                    audits.forEach(function(a){
                        const when = new Date(a.created_at).toLocaleString('fr-FR');
                        const user = a.changed_by || 'Utilisateur';
                        const oldv = a.old_value !== null ? Number(a.old_value).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
                        const newv = a.new_value !== null ? Number(a.new_value).toLocaleString('fr-FR', {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
                        const commentVal = a.comment || '';
                        html += `<li class="mb-2"><strong>${when}</strong> — ${user}<br/>Valeur: ${oldv} → ${newv}<br/>${commentVal}</li>`;
                    });
                    html += '</ul>';
                }

                $('#viewCommentContent').html(html);
            }).fail(function(){
                $('#viewCommentContent').html('<p>Impossible de charger les commentaires.</p>');
            });
        });

        // Edit measure button
        $(document).on('click', '.edit-measure-btn', function(){
            const id = $(this).data('id');
            const period = $(this).data('period');
            const m_tgt = $(this).data('m_tgt');
            const m_tgc = $(this).data('m_tgc');
            const comment = $(this).data('comment');
            const traffic_validated = $(this).closest('tr').find('td:eq(6)').text().trim().replace(/[^\d,]/g, '').replace(',', '.') || '';

            const action = "{{ url('measures') }}" + "/" + id + "/update";
            $('#editMeasureForm_TGT_TGC').attr('action', action);
            $('#edit_periode_TGT_TGC').val(period);
            $('#edit_m_tgt_TGT_TGC').val(m_tgt);
            $('#edit_m_tgc_TGT_TGC').val(m_tgc);
            $('#edit_traffic_validated_TGT_TGC').val(traffic_validated);
            $('#edit_validation_comment_TGT_TGC').val('');
            $('#edit_comment_TGT_TGC').val(comment);
            $('#editMeasureModal_TGT_TGC').modal('show');
        });

        // No confirmation on submit for this page; let the form submit normally
    });

    // Invoice generation loader handling
    $('#generateInvoiceBtn').on('click', function(e){
        var $btn = $(this);
        var selected = $('.select-row:checked').map(function(){ return $(this).val(); }).get();
        if (!selected || selected.length === 0) {
            alert('Veuillez sélectionner au moins une ligne pour générer la facture.');
            return;
        }
        // populate hidden input
        $('#selected_ids_input').val(selected.join(','));

        // show loader
        $('#invoiceLoaderOverlay').css('display','flex');
        $btn.prop('disabled', true).text('Génération en cours...');

        // set a client-side fallback timeout (180s)
        var fallback = setTimeout(function(){
            $('#invoiceLoaderOverlay').hide();
            $btn.prop('disabled', false).text('Générer facture (sélection)');
            alert('La génération prend trop de temps. Vérifiez le serveur ou réessayez.');
        }, 180000); // 3 minutes

        // Submit the form normally (browser will handle download response)
        $('#generateInvoiceForm').submit();
    });
        // Setup - add a text input to each footer cell
        $('#tableExpor1 thead tr .recherche').clone(true).appendTo('#tableExpor1 thead').addClass("rech");
        $('#tableExpor1 thead .rech ').each(function(i) {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                '" />');

            $('input', this).on('keyup change', function() {
                if (table.column(i).search() !== this.value) {
                    table
                        .column(i)
                        .search(this.value)
                        .draw();
                }
            });
        });

        var table = $('#tableExpor1').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            "language": {
                "emptyTable": "Aucune donnée disponible dans le tableau",
                "lengthMenu": "Afficher _MENU_ éléments",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                },
                "aria": {
                    "sortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        "_": "%d lignes sélectionnées",
                        "0": "Aucune ligne sélectionnée",
                        "1": "1 ligne sélectionnée"
                    },
                    "1": "1 ligne selectionnée",
                    "_": "%d lignes selectionnées",
                    "cells": {
                        "1": "1 cellule sélectionnée",
                        "_": "%d cellules sélectionnées"
                    },
                    "columns": {
                        "1": "1 colonne sélectionnée",
                        "_": "%d colonnes sélectionnées"
                    }
                },
                "autoFill": {
                    "cancel": "Annuler",
                    "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                    "fillHorizontal": "Remplir les cellules horizontalement",
                    "fillVertical": "Remplir les cellules verticalement",
                    "info": "Exemple de remplissage automatique"
                },
                "searchBuilder": {
                    "conditions": {
                        "date": {
                            "after": "Après le",
                            "before": "Avant le",
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "moment": {
                            "after": "Après le",
                            "before": "Avant le",
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "number": {
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "gt": "Supérieur à",
                            "gte": "Supérieur ou égal à",
                            "lt": "Inférieur à",
                            "lte": "Inférieur ou égal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "string": {
                            "contains": "Contient",
                            "empty": "Vide",
                            "endsWith": "Se termine par",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "startsWith": "Commence par"
                        },
                        "array": {
                            "equals": "Egal à",
                            "empty": "Vide",
                            "contains": "Contient",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "without": "Sans"
                        }
                    },
                    "add": "Ajouter une condition",
                    "button": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "clearAll": "Effacer tout",
                    "condition": "Condition",
                    "data": "Donnée",
                    "deleteTitle": "Supprimer la règle de filtrage",
                    "logicAnd": "Et",
                    "logicOr": "Ou",
                    "title": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "value": "Valeur"
                },
                "searchPanes": {
                    "clearMessage": "Effacer tout",
                    "count": "{total}",
                    "title": "Filtres actifs - %d",
                    "collapse": {
                        "0": "Volet de recherche",
                        "_": "Volet de recherche (%d)"
                    },
                    "countFiltered": "{shown} ({total})",
                    "emptyPanes": "Pas de volet de recherche",
                    "loadMessage": "Chargement du volet de recherche..."
                },
                "buttons": {
                    "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                    "collection": "Collection",
                    "colvis": "Visibilité colonnes",
                    "colvisRestore": "Rétablir visibilité",

                    "copySuccess": {
                        "1": "1 ligne copiée dans le presse-papier",
                        "_": "%ds lignes copiées dans le presse-papier"
                    },
                    "copyTitle": "Copier dans le presse-papier",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "Afficher toutes les lignes",
                        "1": "Afficher 1 ligne",
                        "_": "Afficher %d lignes"
                    },
                    "pdf": "PDF",

                },
                "decimal": ",",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                "infoThousands": ".",
                "search": "Rechercher:",
                "searchPlaceholder": "...",
                "thousands": "."
            }
        });
        // Selection and invoice generation handlers
        $(document).on('change', '#select_all_rows', function(){
            const checked = $(this).is(':checked');
            $('input.select-row').prop('checked', checked);
        });

        $(document).on('click', '#generateInvoiceBtn', function(){
            const ids = $('input.select-row:checked').map(function(){ return $(this).val(); }).get();
            if (!ids || ids.length === 0) {
                alert('Sélectionnez au moins une ligne pour générer la facture.');
                return;
            }
            $('#selected_ids_input').val(ids.join(','));
            if (confirm('Générer la facture pour ' + ids.length + ' lignes ?')) {
                $('#generateInvoiceForm').submit();
            }
        });
    });
</script>
@stop
