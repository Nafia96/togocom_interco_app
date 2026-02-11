<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>KPI Interco Pivot</title>
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

.kpi-bad { background:#f8d7da; color:#842029; font-weight:bold; }
.kpi-warn { background:#fff3cd; color:#664d03; font-weight:bold; }

thead th {
    position:sticky;
    top:0;
    background: linear-gradient(90deg, #133272 0%, #1e4a98 100%);
    color: #ffd100;
    z-index:5;
    font-weight: 700;
}
tbody td:first-child, thead th:first-child {
    position:sticky; left:0; background:#fff; z-index:6; font-weight:bold;
}

.table-hover tbody tr:hover { background: rgba(19, 50, 114, 0.03); }
</style>
</head>

<body>
<div class="container-fluid py-4">

<div class="card">
<div class="card-header bg-warning fw-bold d-flex justify-content-between">
    <span>PARTNER KPI – {{ strtoupper($direction) }} – {{ strtoupper($view) }}</span>

    <div class="d-flex gap-2">
        <a href="?direction=IN&view={{$view}}&start_date={{$start}}&end_date={{$end}}"
           class="btn btn-sm {{ $direction=='IN'?'btn-success':'btn-light' }}">Incoming</a>

        <a href="?direction=OUT&view={{$view}}&start_date={{$start}}&end_date={{$end}}"
           class="btn btn-sm {{ $direction=='OUT'?'btn-success':'btn-light' }}">Outgoing</a>
    </div>
</div>

<div class="card-body">

{{-- FILTRES --}}
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <label class="form-label">Vue</label>
        <select name="view" class="form-select" onchange="this.form.submit()">
            <option value="day" {{ $view=='day'?'selected':'' }}>Journalier</option>
            <option value="month" {{ $view=='month'?'selected':'' }}>Mensuel</option>
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

{{-- TABLEAU PIVOT --}}
<div class="table-responsive">
<table class="table table-bordered table-hover table-sm">

<thead>
<tr>
    <th rowspan="2">Partner</th>
    @foreach($periods as $p)
        <th colspan="4">
            {{ $view=='day' ? \Carbon\Carbon::parse($p)->format('d') : $p }}
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
@foreach($partners as $partner => $rows)
<tr>
    <td class="text-primary">{{ $partner }}</td>

    @foreach($periods as $p)
        @php $r = $rows->firstWhere('PERIOD',$p); @endphp

        <td class="text-end">{{ $r?number_format($r->ATTEMPTS,0):'-' }}</td>

        <td class="text-end {{ $r && $r->NER < 95 ? 'kpi-bad':'' }}">
            {{ $r?$r->NER.'%':'-' }}
        </td>

        <td class="text-end {{ $r && $r->ASR < 95 ? 'kpi-bad':'' }}">
            {{ $r?$r->ASR.'%':'-' }}
        </td>

        <td class="text-end {{ $r && $r->ACD_SEC > 180 ? 'kpi-warn':'' }}">
            {{ $r?number_format($r->ACD_SEC,0):'-' }}
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
