<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>KPI Roming</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:#f4f6f9;
    font-family: "Segoe UI", Roboto, Arial, sans-serif;
    font-size:0.85rem;
}

th { text-align:center; white-space:nowrap; }
td { white-space:nowrap; }

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

.kpi-bad  { background:#f8d7da; color:#842029; font-weight:bold; }
.kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

.kpi-toggle { padding: .35rem .65rem; font-size: .85rem; border-radius: .35rem; transition: all 0.2s ease; }
.kpi-toggle-active { background: #133272; color: #ffd100; border-color: #133272; font-weight: 600; }
.kpi-toggle-inactive { background: #fff; color: #133272; border: 1px solid rgba(19, 50, 114, .35); }

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
</style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('lunchepade') }}" class="btn btn-sm btn-secondary me-3" title="Retour au launchpad" style="padding: 0.25rem 0.5rem; display: flex; align-items: center;">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 24px; width: auto; object-fit: contain; margin-right: 6px;">
                    <span>Launchpad</span>
                </a>
                <span>
                    KPI ROMING – {{ strtoupper($direction ?: 'ALL') }} – {{ strtoupper($view) }}
                </span>
            </div>
            @include('partials.kpi_nav')
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('kpi.roaming') }}" class="row g-2 mb-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Direction</label>
                    <select name="direction" class="form-select" onchange="this.form.submit()">
                        <option value="ALL" {{ ($direction ?? 'ALL') == 'ALL' ? 'selected' : '' }}>Toutes</option>
                        @foreach ($directionOptions as $value)
                            <option value="{{ $value }}" {{ ($direction ?? 'ALL') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Vue</label>
                    <select name="view" class="form-select" onchange="this.form.submit()">
                        <option value="day" {{ ($view ?? 'day') == 'day' ? 'selected' : '' }}>Journalier</option>
                        <option value="week" {{ ($view ?? 'day') == 'week' ? 'selected' : '' }}>Hebdomadaire</option>
                        <option value="month" {{ ($view ?? 'day') == 'month' ? 'selected' : '' }}>Mensuel</option>
                        <option value="year" {{ ($view ?? 'day') == 'year' ? 'selected' : '' }}>Annuel</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date début</label>
                    <input type="date" name="start_date" value="{{ $start }}" class="form-control" onchange="this.form.submit()">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date fin</label>
                    <input type="date" name="end_date" value="{{ $end }}" class="form-control" onchange="this.form.submit()">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Orig type</label>
                    <select name="orig_type" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        @foreach ($origTypes as $value)
                            <option value="{{ $value }}" {{ ($origType ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Dest type</label>
                    <select name="dest_type" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        @foreach ($destTypes as $value)
                            <option value="{{ $value }}" {{ ($destType ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Orig net</label>
                    <select name="orig_net" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        @foreach ($origNets as $value)
                            <option value="{{ $value }}" {{ ($origNet ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Dest net</label>
                    <select name="dest_net" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous</option>
                        @foreach ($destNets as $value)
                            <option value="{{ $value }}" {{ ($destNet ?? '') == $value ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Partner</label>
                    <input type="search" name="partner" value="{{ $partner ?? '' }}" class="form-control" list="partner-list" placeholder="Rechercher un opérateur" onchange="this.form.submit()">
                    <datalist id="partner-list">
                        @foreach ($partners as $partnerName)
                            <option value="{{ $partnerName }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <a href="{{ route('kpi.roaming') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>

            @if (!empty($errorMessage))
                <div class="alert alert-warning mb-3" role="alert">
                    {{ $errorMessage }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th rowspan="2">Network</th>
                            <th rowspan="2">Partner</th>
                            @foreach($periods as $p)
                                <th colspan="4">
                                    @if($view === 'day')
                                        {{ \Carbon\Carbon::parse($p)->format('d/m') }}
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
                        @foreach($networks as $networkName => $partnersGroup)
                            @foreach($partnersGroup as $partnerName => $rows)
                                <tr>
                                    <td class="text-primary">{{ $networkName }}</td>
                                    <td>{{ $partnerName }}</td>

                                    @foreach($periods as $p)
                                        @php
                                            $r = $view === 'week'
                                                ? $rows->firstWhere('PERIOD_LABEL', $p)
                                                : $rows->firstWhere('PERIOD', $p);
                                        @endphp

                                        <td class="text-end">{{ $r ? number_format($r->ATTEMPTS, 0) : '-' }}</td>
                                        <td class="text-end {{ $r && $r->NER < 95 ? 'kpi-bad' : '' }}">{{ $r ? $r->NER.'%' : '-' }}</td>
                                        <td class="text-end {{ $r && $r->ASR < 95 ? 'kpi-bad' : '' }}">{{ $r ? $r->ASR.'%' : '-' }}</td>
                                        <td class="text-end {{ $r && $r->ACD_SEC > 180 ? 'kpi-warn' : '' }}">{{ $r ? number_format($r->ACD_SEC, 0) : '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
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
