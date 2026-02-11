<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>KPI Interco Pivot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }

        th {
            text-align: center;
            white-space: nowrap;
        }

        td {
            white-space: nowrap;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }

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

        /* --- BOUTONS DE NAVIGATION --- */
        .btn-nav {
            background: linear-gradient(180deg, #0056d2 0%, #004aad 100%);
            color: #fff !important;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 14px;
            margin-right: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }

        .btn-nav:hover {
            background: linear-gradient(180deg, #0069ff 0%, #0056d2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: #fff !important;
        }

        .btn-nav:active {
            transform: scale(0.97);
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .kpi-bad  { background:#f8d7da; color:#842029; font-weight:bold; }
        .kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

        table.table thead th {
            position: sticky;
            top: 0;
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100;
            z-index: 6;
            border-bottom: 2px solid #004aad;
            text-transform: uppercase;
            font-size: .85rem;
            letter-spacing: .02em;
            vertical-align: middle;
            font-weight: 700;
        }

        tbody td:first-child, thead th:first-child {
            position: sticky; left: 0; background: #fff; z-index: 7; font-weight: 700; box-shadow: 2px 0 6px rgba(0,0,0,.04);
        }

        .table-hover tbody tr:hover { background: rgba(19, 50, 114, 0.03); }

        /* Filter action button (right side) styled to match table heading */
        .col-md-3.d-flex.align-items-end .btn,
        .col-md-2.d-flex.align-items-end .btn,
        .col-md-1.d-flex.align-items-end .btn,
        .filter-action .btn {
            background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
            color: #ffd100 !important;
            border: none;
            font-weight: 700;
        }
        .col-md-3.d-flex.align-items-end .btn:hover,
        .col-md-2.d-flex.align-items-end .btn:hover,
        .col-md-1.d-flex.align-items-end .btn:hover,
        .filter-action .btn:hover {
            filter: brightness(0.95);
        }
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
                    <span>PARTNER KPI – {{ strtoupper($direction) }} – {{ strtoupper($view) }}</span>
                </div>

                @include('partials.kpi_nav')

            </div>


            <div class="card-body">
                @include('partials.params_breadcrumb')

                <div class="d-flex gap-2 mb-3">
                    <a href="?direction=IN&view={{ $view }}&start_date={{ $start }}&end_date={{ $end }}"
                        class="btn btn-sm kpi-toggle {{ $direction == 'IN' ? 'kpi-toggle-active' : 'kpi-toggle-inactive' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-down-circle" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM4.646 8.354a.5.5 0 0 1 .708 0L7.5 10.5V5.5a.5.5 0 0 1 1 0v5l2.146-2.146a.5.5 0 1 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z" />
                        </svg>
                        Incoming
                    </a>

                    <a href="?direction=OUT&view={{ $view }}&start_date={{ $start }}&end_date={{ $end }}"
                        class="btn btn-sm kpi-toggle {{ $direction == 'OUT' ? 'kpi-toggle-active' : 'kpi-toggle-inactive' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-up-circle" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM11.354 7.646a.5.5 0 0 1-.708 0L8.5 5.5V10.5a.5.5 0 0 1-1 0V5.5L5.354 7.646a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708z" />
                        </svg>
                        Outgoing
                    </a>
                </div>

                {{-- FILTRES --}}
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-2">
                        <label class="form-label">Vue</label>
                        <select name="view" class="form-select" onchange="this.form.submit()">
                            <option value="day" {{ $view == 'day' ? 'selected' : '' }}>Journalier</option>
                            <option value="week" {{ $view == 'week' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="month" {{ $view == 'month' ? 'selected' : '' }}>Mensuel</option>
                            <option value="year" {{ $view == 'year' ? 'selected' : '' }}>Annuel</option>
                        </select>

                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date début</label>
                        <input type="date" name="start_date" value="{{ $start }}" class="form-control" onchange="this.form.submit()">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="end_date" value="{{ $end }}" class="form-control" onchange="this.form.submit()">
                    </div>

                    <input type="hidden" name="direction" value="{{ $direction }}">
                </form>

                {{-- TABLEAU PIVOT --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">

                        <thead>
                            <tr>
                                <th rowspan="2">Partner</th>
                                @foreach($periods as $p)
                                    <th colspan="4">
                                        @if($view === 'day')
                                            {{ \Carbon\Carbon::parse($p)->format('d') }}
                                        @else
                                            {{ $p }}
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($periods as $p)
                                    <th>ATT</th>
                                    <th>NER</th>
                                    <th>ASR</th>
                                    <th>ACD</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($partners as $partner => $rows)
                                <tr>
                                    <td class="text-primary">
                                        @php
                                            $kc_direction =
                                                $direction === 'IN'
                                                    ? 'DESTINATED'
                                                    : ($direction === 'OUT'
                                                        ? 'ORIGINATED'
                                                        : $direction);
                                            $url = url('/kpi/KpinCarrier') . '?view=' . urlencode($view)
                                                . '&start_date=' . urlencode($start)
                                                . '&end_date=' . urlencode($end)
                                                . '&network=ALL'
                                                . '&partner=' . urlencode($partner)
                                                . '&direction=' . urlencode($direction);
                                        @endphp
                                        <a href="{{ $url }}" class="fw-bold text-decoration-underline" style="color:#133272;">
                                            {{ $partner }}
                                        </a>
                                    </td>

                                    @foreach ($periods as $p)
                                        @php $r = $rows->firstWhere('PERIOD',$p); @endphp

                                        <td class="text-end">{{ $r ? number_format($r->ATTEMPTS, 0) : '-' }}</td>

                                        <td class="text-end {{ $r && $r->NER < 95 ? 'kpi-bad' : '' }}">
                                            {{ $r ? $r->NER . '%' : '-' }}
                                        </td>

                                        <td class="text-end {{ $r && $r->ASR < 95 ? 'kpi-bad' : '' }}">
                                            {{ $r ? $r->ASR . '%' : '-' }}
                                        </td>

                                        <td class="text-end {{ $r && $r->ACD_SEC > 180 ? 'kpi-warn' : '' }}">
                                            {{ $r ? number_format($r->ACD_SEC, 0) : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    @include('partials.date_sync')
</body>

</html>
