@extends('template.principal_tamplate3')

@section('title')
    Transit Fees
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tgc-tgt') }}">National</a></li>
            <li class="breadcrumb-item">International</li>
            <li class="breadcrumb-item active" aria-current="page">Transit Fees</li>
        </ol>
    </nav>
@endsection

@section('content')
    <style>
        .transit-fees-page {
            padding-top: 1.25rem;
            padding-bottom: 1.5rem;
        }

        .transit-fees-header {
            margin-bottom: 1.5rem;
        }

        .transit-fees-panel {
            padding: 1rem;
            background: #fff;
            border: 1px solid #e6e9ef;
            border-radius: 4px;
        }

        .transit-fees-toolbar,
        .transit-fees-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .transit-fees-toolbar {
            margin-bottom: 1rem;
        }

        .transit-fees-footer {
            margin-top: 1rem;
        }

        .transit-fees-exports .dt-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .transit-fees-exports .dt-button {
            margin: 0;
        }

        .transit-fees-search,
        .transit-fees-info,
        .transit-fees-pagination {
            min-width: 0;
        }

        .transit-fees-search .dataTables_filter,
        .transit-fees-info .dataTables_info,
        .transit-fees-pagination .dataTables_paginate {
            margin: 0;
            padding: 0;
        }

        .transit-fees-search .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: 0;
        }

        .transit-fees-search .dataTables_filter input {
            width: min(16rem, 55vw);
            margin-left: 0;
        }

        .transit-fees-scroll {
            width: 100%;
            overflow-x: auto;
        }

        #internationalTransitFeesTable {
            width: 100% !important;
            min-width: 620px;
            margin-bottom: 0;
        }

        #internationalTransitFeesTable thead th {
            white-space: nowrap;
            vertical-align: middle;
        }

        #internationalTransitFeesTable tbody th,
        #internationalTransitFeesTable tbody td {
            vertical-align: middle;
        }

        #internationalTransitFeesTable tbody td:not(:first-child) {
            text-align: right;
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .transit-fees-panel {
                padding: .75rem;
            }

            .transit-fees-toolbar,
            .transit-fees-footer {
                align-items: stretch;
                flex-direction: column;
                gap: .75rem;
            }

            .transit-fees-search .dataTables_filter label {
                align-items: flex-start;
                flex-direction: column;
            }

            .transit-fees-search .dataTables_filter input {
                width: 100%;
            }

            .transit-fees-pagination .dataTables_paginate {
                float: none;
                text-align: left;
            }
        }
    </style>

    @php
        $formatAmount = function ($amount) {
            if ($amount === null) {
                return '—';
            }

            $parts = explode('.', (string) $amount, 2);
            $whole = preg_replace('/\\B(?=(\\d{3})+(?!\\d))/', ' ', $parts[0]);
            $fraction = substr(str_pad($parts[1] ?? '', 2, '0'), 0, 2);

            return $whole . ',' . $fraction;
        };
    @endphp

    <div class="container-fluid transit-fees-page">
        <div class="transit-fees-header">
            <h4 class="mb-1">Transit Fees</h4>
            <p class="text-muted mb-0">Montant calculé par période : 2 % du total Retail TGC + Wholesale.</p>
        </div>

        <div class="transit-fees-panel">
            <table id="internationalTransitFeesTable" class="table table-striped table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Période</th>
                        <th scope="col">Retail TGC</th>
                        <th scope="col">Wholesale</th>
                        <th scope="col">Total</th>
                        <th scope="col">Transit Fees (2 %)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transitFees as $row)
                        <tr>
                            <th scope="row">{{ $row->period }}</th>
                            <td>{{ $formatAmount($row->retail_tgc) }}</td>
                            <td>{{ $formatAmount($row->wholesale) }}</td>
                            <td>{{ $formatAmount($row->total) }}</td>
                            <td>{{ $formatAmount($row->transit_fees) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Aucune déclaration enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function () {
            $('#internationalTransitFeesTable').DataTable({
                dom: '<"transit-fees-toolbar"<"transit-fees-exports"B><"transit-fees-search"f>><"transit-fees-scroll"t><"transit-fees-footer"<"transit-fees-info"i><"transit-fees-pagination"p>>',
                order: [[0, 'desc']],
                buttons: [
                    { extend: 'copy', text: 'Copier', className: 'btn btn-sm btn-outline-secondary' },
                    { extend: 'csv', text: 'CSV', className: 'btn btn-sm btn-outline-secondary' },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-outline-secondary' },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-sm btn-outline-secondary', orientation: 'landscape' },
                    { extend: 'print', text: 'Imprimer', className: 'btn btn-sm btn-outline-secondary' },
                ],
                language: {
                    emptyTable: 'Aucune déclaration enregistrée.',
                    search: 'Rechercher :',
                    lengthMenu: 'Afficher _MENU_ lignes',
                    info: '_START_ à _END_ sur _TOTAL_ lignes',
                    infoEmpty: 'Aucune déclaration',
                    zeroRecords: 'Aucune déclaration correspondante',
                    paginate: { first: 'Première', last: 'Dernière', next: 'Suivante', previous: 'Précédente' }
                }
            });
        });
    </script>
@endsection
