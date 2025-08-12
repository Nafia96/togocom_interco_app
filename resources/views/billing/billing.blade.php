@extends('template.billing_tamplate')

@section('title', 'Statistiques de facturation')

@section('content')

    <section class="section">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-2">

                    <div class="card-wrap">

                        <div class="card-body pull-left">
                            Entrant {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}

                        </div>
                    </div>

                    <br>

                    <div style="font-size: 140%" class="card-body pull-center"> <br>


                        <p style="white-space: nowrap;"><span>
                                Volume
                                : {{ number_format(8952222222, 0, ',', ' ') }} <br>
                                Revenue
                                : {{ number_format(8655422665, 0, ',', ' ') }}<br>
                            </span></p>

                    </div>




                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card card-statistic-2">

                    <div class="card-wrap">

                        <div class="card-body pull-left">
                            Sortant {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                        </div>
                    </div>
                    <br>
                    <div style="font-size: 140%" class="card-body pull-center">
                        <br>
                        <p style="white-space: nowrap;"><span>
                                Volume: 1886965225445 <br>
                                Dette : 1886965225445 <br>
                            </span></p>

                    </div>
                </div>
            </div>

        </div>

        <!-- Filtres -->
        <div class="container-fluid">

            {{-- Filtres --}}
            <form method="GET" action="{{ url('billing') }}" class="mb-4">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label>Direction</label>
                        <select name="direction" class="form-control">
                            <option value="">-- Toutes --</option>
                            <option value="Entrant" {{ request('direction') == 'Entrant' ? 'selected' : '' }}>Entrant
                            </option>
                            <option value="Sortant" {{ request('direction') == 'Sortant' ? 'selected' : '' }}>Sortant
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Opérateur</label>
                        <select name="operator" class="form-control">
                            <option value="">-- Tous --</option>
                            @foreach ($operators as $operator)
                                <option value="{{ $operator }}"
                                    {{ request('operator') == $operator ? 'selected' : '' }}>{{ $operator }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Période début</label>
                        <input type="month" class="form-control" name="start_period"
                            value="{{ request('start_period') }}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Période fin</label>
                        <input type="month" class="form-control" name="end_period" value="{{ request('end_period') }}">
                    </div>

                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button class="btn btn-success" type="submit">Filtrer</button>
                    </div>
                </div>
            </form>

            {{-- Tableau --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tableExpor">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Opérateur</th>
                            <th>Minutes Charge</th>
                            <th>Montant Charge</th>
                            <th>Minutes Revenue</th>
                            <th>Montant Revenue</th>
                            <th>Netting (Montant)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row['carrier_name'] }}</td>
                                <td>{{ number_format($row['charge_minutes'], 0, ',', ' ') }}</td>
                                <td>{{ number_format($row['charge_amount'], 0, ',', ' ') }}</td>
                                <td>{{ number_format($row['revenue_minutes'], 0, ',', ' ') }}</td>
                                <td>{{ number_format($row['revenue_amount'], 0, ',', ' ') }}</td>
                                <td>
                                    {{ number_format($row['revenue_amount'] - $row['charge_amount'], 0, ',', ' ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Totaux</th>
                            <th>{{ number_format($totals['charge_minutes'], 0, ',', ' ') }}</th>
                            <th>{{ number_format($totals['charge_amount'], 0, ',', ' ') }}</th>
                            <th>{{ number_format($totals['revenue_minutes'], 0, ',', ' ') }}</th>
                            <th>{{ number_format($totals['revenue_amount'], 0, ',', ' ') }}</th>
                            <th>{{ number_format($totals['revenue_amount'] - $totals['charge_amount'], 0, ',', ' ') }}</th>
                        </tr>
                    </tfoot>
                </table>


            </div>

          
        </div>

    </section>








@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Ajout champs de recherche
            $('#tableExpor thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#tableExpor thead');

            $('#tableExpor').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        footer: true,
                        filename: 'Statistiques_Opérateurs',
                        exportOptions: {
                            columns: ':not(:last-child)',
                            format: {
                                body: function(data) {
                                    return $('<div>').html(data).text().replace(/\s/g, '').replace(
                                        ',', '.');
                                }
                            }
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        footer: true
                    },
                    {
                        extend: 'pdfHtml5',
                        footer: true
                    }
                ],
                initComplete: function() {
                    var api = this.api();
                    api.columns().every(function() {
                        var column = this;
                        $('input', this.header()).on('keyup change clear', function() {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    });
                }
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
            select: '.demo',
            placeholder: 'Sélectionnez un ou plusieurs opérateurs'
        })
    </script>
@stop
