<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pivot Network Mensuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }
        .table-heading-country {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%) !important;
            color: #ffd100 !important;
            font-weight: bold !important;
            text-transform: uppercase;
            text-align: center;
            font-size: 1.1em !important;
            letter-spacing: 0.5px !important;
            border: 2px solid rgba(19,50,114,0.9) !important;
        }

        .col-md-3.d-flex.align-items-end .btn,
        .col-md-2.d-flex.align-items-end .btn,
        .filter-action .btn {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100 !important;
            border: none;
            font-weight: 700;
        }
        .col-md-3.d-flex.align-items-end .btn:hover,
        .col-md-2.d-flex.align-items-end .btn:hover,
        .filter-action .btn:hover {
            filter: brightness(0.95);
        }
        .card { border-radius: 15px; overflow: hidden; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06); }
        .card-header {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.75rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #004aad;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -2px 6px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem 0.5rem 0 0;
        }
        table { border-collapse: separate; border-spacing: 0; font-size: 0.8rem; }
        th, td { white-space: nowrap; padding: 4px 8px !important; vertical-align: middle !important; }
        .table-responsive { max-height: 70vh; overflow-y: auto; overflow-x: auto; }
        thead th { position: sticky; top: 0; background: #e9ecef; z-index: 10; font-size: 0.8rem; }
        tbody td:first-child, thead th:first-child, tfoot td:first-child { position: sticky; left: 0; background: #fff; z-index: 11; font-weight: 600; }
        tfoot { position: sticky; bottom: 0; z-index: 9; }
        tfoot tr { background: #198754 !important; color: white !important; font-weight: bold; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #e3fcec !important; }
        .table-striped tbody tr:nth-of-type(even) { background-color: #ffffff !important; }
        .btn-light { background: #f8f9fa; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: 0.25rem 0.5rem; display: flex; align-items: center;">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                        <span>Launchpad</span>
                    </a>
                    <i class="fas fa-network-wired me-2"></i>
                    <span>Analyse Pivot Network – Mensuelle</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php
                        $qs = ['filter' => $filter];
                        if ($startDate) $qs['start_date'] = $startDate;
                        if ($endDate) $qs['end_date'] = $endDate;
                    @endphp
                    <a href="{{ route('billingPivotCountryCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Pays</a>
                    <a href="{{ route('billingp', $qs) }}" class="btn btn-sm btn-light text-primary">Opérateurs</a>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="px-3 pt-2">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><strong>Vue :</strong>
                        @php $vt = request('view_type', 'month'); @endphp
                        {{ $vt == 'day' ? 'Journalière' : ($vt == 'month' ? 'Mensuelle' : 'Annuelle') }}
                    </li>
                    <li class="breadcrumb-item"><strong>Type :</strong> {{ $filter }}</li>
                    <li class="breadcrumb-item"><strong>Pays :</strong> {{ request('orig_country_name') ? request('orig_country_name') : 'Tous' }}</li>
                </ol>
            </nav>
            <div class="card-body">
                <form method="GET" action="{{ route('billingPivotNetCarrier') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label for="view_type" class="form-label fw-semibold">Vue :</label>
                        <select id="view_type" name="view_type" class="form-select">
                            <option value="day" {{ request('view_type','month') == 'day' ? 'selected' : '' }}>Journalière</option>
                            <option value="month" {{ request('view_type','month') == 'month' ? 'selected' : '' }}>Mensuelle</option>
                            <option value="year" {{ request('view_type','month') == 'year' ? 'selected' : '' }}>Annuelle</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter" class="form-label fw-semibold">Type :</label>
                        <select id="filter" name="filter" class="form-select">
                            <option value="entrant" {{ $filter == 'entrant' ? 'selected' : '' }}>Volume entrant</option>
                            <option value="sortant" {{ $filter == 'sortant' ? 'selected' : '' }}>Volume sortant</option>
                            <option value="revenu" {{ $filter == 'revenu' ? 'selected' : '' }}>Revenu</option>
                            <option value="charge" {{ $filter == 'charge' ? 'selected' : '' }}>Charge</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">Date début :</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">Date fin :</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th class="table-heading-country">Network</th>
                                @foreach ($months as $month)
                                    @php
                                        $year = substr($month, 0, 4);
                                        $monthNum = substr($month, 5, 2);
                                        $monthName = \Carbon\Carbon::createFromDate($year, $monthNum, 1)->translatedFormat('M y');
                                    @endphp
                                    <th class="text-center">{{ $monthName }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $networkGroups = $records->groupBy('orig_net_name');
                            @endphp
                            @foreach ($networkGroups as $network => $records)
                                <tr>
                                    <td>{{ $network }}</td>
                                    @php $sum = 0; @endphp
                                    @foreach ($months as $month)
                                        @php
                                            $year = (int) substr($month, 0, 4);
                                            $monthNum = (int) substr($month, 5, 2);
                                            $val = $records->where('year', $year)->where('month', $monthNum)->sum('value');
                                            $sum += $val;
                                        @endphp
                                        <td class="text-end">{{ $val > 0 ? number_format($val, 0, ',', ' ') : '-' }}</td>
                                    @endforeach
                                    <td class="text-end bg-light fw-bold">{{ number_format($sum, 0, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                @foreach ($months as $month)
                                    @php
                                        $year = (int) substr($month, 0, 4);
                                        $monthNum = (int) substr($month, 5, 2);
                                        $total = $records->where('year', $year)->where('month', $monthNum)->sum('value');
                                    @endphp
                                    <td class="text-end">{{ $total > 0 ? number_format($total, 0, ',', ' ') : '-' }}</td>
                                @endforeach
                                <td class="text-end">{{ number_format($records->sum('value'), 0, ',', ' ') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>
</body>
</html>
