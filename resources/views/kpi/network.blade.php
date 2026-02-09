<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>NETWORK KPI Pivot</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; font-size:0.85rem; }

th { text-align:center; white-space:nowrap; }
td { white-space:nowrap; }

.kpi-bad { background:#f8d7da; color:#842029; font-weight:bold; }
.kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

table.table thead th {
    position: sticky;
    top: 0;
    background: linear-gradient(180deg,#0b5ed7,#0a58ca);
    color: #fff;
    z-index: 6;
    border-bottom: 2px solid rgba(255,255,255,0.08);
    text-transform: uppercase;
    font-size: .85rem;
    letter-spacing: .02em;
    vertical-align: middle;
}

tbody td:first-child, thead th:first-child {
    position: sticky; left: 0; background: #fff; z-index: 7; font-weight: 700; box-shadow: 2px 0 6px rgba(0,0,0,.04);
}

.table-hover tbody tr:hover { background: rgba(13,110,253,0.03); }
</style>
</head>

<body>
<div class="container-fluid py-4">

<div class="card">
<div class="card-header bg-warning fw-bold d-flex justify-content-between align-items-center">
    <span>
        NETWORK KPI – {{ strtoupper($direction) }} – {{ strtoupper($view) }}
    </span>

    @include('partials.kpi_nav')
</div>

<div class="card-body">
    @include('partials.params_breadcrumb')

    <div class="d-flex gap-2 mb-3">
        <a href="?direction=ORIGINATED&view={{$view}}&start_date={{$start}}&end_date={{$end}}"
           class="btn btn-sm kpi-toggle {{ $direction=='ORIGINATED' ? 'kpi-toggle-active' : 'kpi-toggle-inactive' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM11.354 7.646a.5.5 0 0 1-.708 0L8.5 5.5V10.5a.5.5 0 0 1-1 0V5.5L5.354 7.646a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708z"/></svg>
            Originated
        </a>

        <a href="?direction=DESTINATED&view={{$view}}&start_date={{$start}}&end_date={{$end}}"
           class="btn btn-sm kpi-toggle {{ $direction=='DESTINATED' ? 'kpi-toggle-active' : 'kpi-toggle-inactive' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zM4.646 8.354a.5.5 0 0 1 .708 0L7.5 10.5V5.5a.5.5 0 0 1 1 0v5l2.146-2.146a.5.5 0 1 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z"/></svg>
            Destinated
        </a>
    </div>

{{-- FILTRES --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <label class="form-label">Vue</label>
        <select name="view" class="form-select" onchange="this.form.submit()">
            <option value="day"   {{ $view=='day'?'selected':'' }}>Journalier</option>
            <option value="week"  {{ $view=='week'?'selected':'' }}>Hebdomadaire</option>
            <option value="month" {{ $view=='month'?'selected':'' }}>Mensuel</option>
            <option value="year"  {{ $view=='year'?'selected':'' }}>Annuel</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Date début</label>
        <input type="date" name="start_date" value="{{ $start }}" class="form-control">
    </div>

    <div class="col-md-3">
        <label class="form-label">Date fin</label>
        <input type="date" name="end_date" value="{{ $end }}" class="form-control">
    </div>

    <input type="hidden" name="direction" value="{{ $direction }}">
</form>

{{-- TABLEAU --}}
<div class="table-responsive">
<table class="table table-bordered table-hover table-sm">

<thead>
<tr>
    <th rowspan="2">Network</th>
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
@foreach($networks as $network => $rows)
<tr>
    <td class="text-primary fw-bold">{{ $network }}</td>

    @foreach($periods as $p)
        @php $r = $rows->firstWhere('PERIOD', $p); @endphp

        <td class="text-end">{{ $r ? number_format($r->ATTEMPTS,0) : '-' }}</td>

        <td class="text-end {{ $r && $r->NER < 95 ? 'kpi-bad':'' }}">
            {{ $r ? $r->NER.'%' : '-' }}
        </td>

        <td class="text-end {{ $r && $r->ASR < 95 ? 'kpi-bad':'' }}">
            {{ $r ? $r->ASR.'%' : '-' }}
        </td>

        <td class="text-end {{ $r && $r->ACD_SEC > 180 ? 'kpi-warn':'' }}">
            {{ $r ? number_format($r->ACD_SEC,0) : '-' }}
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
