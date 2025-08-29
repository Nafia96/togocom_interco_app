@extends('template.principal_tamplate')

@section('title', 'Liste des opérateurs')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Selection des opérateurs</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="col-12 col-md-12 col-lg-12">


        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#F5F5DC">
                <h4 class="mb-0">Liste des opérateurs (Envoi des factures)</h4>
                <form method="POST" action="{{ route('selection') }}">
                    @csrf
                    <div class="d-flex align-items-center gap-2">
                        <!-- Sélection période -->
                        <div style="width: 200px;">
                            Peridode de facturation:
                            <input type="month" id="invoice_period" required name="invoice_period" class="form-control"
                                value="{{ request('invoice_period') ?? '' }}" min="2020-01"
                                max="{{ now()->format('Y-m') }}">
                        </div>

                        <!-- Choix du répertoire -->


                    </div>
            </div>


            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="width:100%;">
                        <thead>
                            <tr>
                                <!-- colonnes du tableau -->
                                <th>N°</th>

                                <th>Sélection</th>
                                <th>Nom de l'opérateur</th>
                                <th>Adresse principale</th>
                                <th>Adresse 2</th>
                                <th>Adresse 3</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($operateurs as $index => $op)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <input type="checkbox" name="operateurs[]" value="{{ $op->id }}">
                                    </td>

                                    <td>{{ $op->name }}</td>
                                    <td>
                                        {{ $op->email }}
                                    </td>
                                    <td>
                                        {{ $op->email1 }}
                                    </td>
                                    <td>
                                        {{ $op->email2 }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun opérateur trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" id="checkAllBtn" class="btn btn-secondary">Tout cocher</button>
                    <button type="submit" class="btn btn-primary">Valider la sélection</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')

    <script>
        $(document).ready(function() {
            // ✅ Tout cocher / décocher
            $('#checkAllBtn').on('click', function() {
                let checkboxes = $('input[name="operateurs[]"]');
                let allChecked = checkboxes.length === checkboxes.filter(':checked').length;

                checkboxes.prop('checked', !allChecked);

                $(this).text(allChecked ? "Tout cocher" : "Tout décocher");
            });
            // Ajout des champs de recherche
            $('#tableExpor thead tr .recherche').clone(true).appendTo('#tableExpor thead').addClass("rech");
            $('#tableExpor thead .rech').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');
                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });



            var table = $('#tableExpor').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                footer: true, // Nécessaire pour inclure le footer dans les exports
                buttons: [{
                        extend: 'excelHtml5',
                        footer: true,
                        filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS ',
                        title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS ',
                        exportOptions: {
                            columns: ':not(:last-child)', // Exclut la colonne ACTION
                            format: {
                                body: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text(); // Supprime HTML
                                    return cleanText.replace(/\s/g, '').replace(',',
                                        '.'); // Nettoyage nombre
                                },
                                footer: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text();
                                    return cleanText.replace(/\s/g, '').replace(',', '.');
                                }
                            }
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Parcourt chaque cellule
                            $('row c[r]', sheet).each(function() {
                                var cell = $(this);
                                var value = $('v', cell).text();

                                // Si valeur numérique avec virgule ou espace
                                if (value && /^[\d\s,.]+$/.test(value)) {
                                    var cleaned = value.replace(/\s/g, '').replace(',',
                                        '.');
                                    $('v', cell).text(cleaned);
                                    cell.attr('t', 'n'); // force le type 'number'
                                }
                            });
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        footer: true,
                        filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                        title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            format: {
                                body: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text();
                                    return cleanText.replace(/\s/g, '').replace(',', '.');
                                },
                                footer: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text();
                                    return cleanText.replace(/\s/g, '').replace(',', '.');
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        footer: true,
                        filename: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                        title: 'LISTE DES CREANCES, DETTES ET NETTING DES OPERATEURS',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            format: {
                                body: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text();
                                    return cleanText.replace(/\s/g, '').replace(',', '.');
                                },
                                footer: function(data, row, column, node) {
                                    var cleanText = $('<div>').html(data).text();
                                    return cleanText.replace(/\s/g, '').replace(',', '.');
                                }
                            }
                        }
                    }
                ]
            });
        });

        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Voulez-vous vraiment supprimer cet opérateur?',
                text: 'Cet opérateur sera supprimé définitivement de cette liste!',
                icon: 'warning',
                buttons: ["Annuler", "Oui!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });


        new SlimSelect({
            select: '.demo'
        })
    </script>
@stop
