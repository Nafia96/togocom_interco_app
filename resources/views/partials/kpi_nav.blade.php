@php
    $qs = request()->query();
    // ensure direction mapping for pivot pages (IN/OUT) if present
    if(isset($qs['direction']) && ($qs['direction']=='IN' || $qs['direction']=='OUT')){
        $qs_for_kpin = $qs;
        $qs_for_kpin['direction'] = $qs['direction'] === 'IN' ? 'DESTINATED' : 'ORIGINATED';
    } else {
        $qs_for_kpin = $qs;
    }
@endphp

<style>
    .kpi-nav .btn { padding: .45rem .7rem; font-size: .9rem; border-radius: .35rem; display: inline-flex; align-items: center; gap: .4rem; }
    .kpi-nav .kpi-active { background: #0d6efd; color: #fff; border-color: #0d6efd; box-shadow: 0 2px 6px rgba(13,110,253,.15); }
    .kpi-nav .kpi-inactive { background: #fff; color: #0d6efd; border: 1px solid rgba(13,110,253,.25); }
    .kpi-nav .kpi-inactive:hover { background: rgba(13,110,253,.04); }
    .kpi-nav svg { width: 1rem; height: 1rem; }

    .kpi-toggle { padding: .35rem .65rem; font-size: .85rem; border-radius: .35rem; }
    .kpi-toggle-active { background: #198754; color: #fff; border-color: #198754; }
    .kpi-toggle-inactive { background: #fff; color: #198754; border: 1px solid rgba(25,135,84,.25); }
    .kpi-toggle-inactive:hover { background: rgba(25,135,84,.04); }
</style>

@php
    // helper to choose class
    $cls = function($route){ return request()->routeIs($route) ? 'kpi-active' : 'kpi-inactive'; };
@endphp

<div class="mb-2 kpi-nav d-flex align-items-center">
    <div class="btn-group" role="group" aria-label="KPI nav">
        <a href="{{ route('kpi.pivot', $qs) }}" class="btn btn-sm {{ $cls('kpi.pivot') }}">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3 2.5a.5.5 0 0 1 .5-.5H6a.5.5 0 0 1 .5.5V5a.5.5 0 0 1-.5.5H3.5A.5.5 0 0 1 3 5V2.5zM3.5 6.5H6a.5.5 0 0 1 .5.5V9a.5.5 0 0 1-.5.5H3.5A.5.5 0 0 1 3 9V7a.5.5 0 0 1 .5-.5zM9.5 2H12a.5.5 0 0 1 .5.5V5a.5.5 0 0 1-.5.5H9.5A.5.5 0 0 1 9 5V2.5A.5.5 0 0 1 9.5 2zM9.5 6.5H12a.5.5 0 0 1 .5.5V9a.5.5 0 0 1-.5.5H9.5A.5.5 0 0 1 9 9V7a.5.5 0 0 1 .5-.5z"/></svg>
            Partner KPI
        </a>

        <a href="{{ route('kpi.network', $qs) }}" class="btn btn-sm {{ $cls('kpi.network') }}">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 4h-2A5.5 5.5 0 0 0 4 9.5v2A3.5 3.5 0 0 1 7.5 8h4A1.5 1.5 0 0 0 13.5 6V4z"/></svg>
            Network KPI
        </a>

        <a href="{{ route('kpi.KpinCarrier', $qs_for_kpin) }}" class="btn btn-sm {{ $cls('kpi.KpinCarrier') }}">
            <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M2 2h12v3H2V2zm0 4h5v8H2V6zm6 0h6v8H8V6z"/></svg>
            Routing View
        </a>
    </div>

    <a href="{{ route('billingp', $qs) }}" class="btn btn-sm btn-warning ms-3 px-3" style="font-weight:700;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h11A1.5 1.5 0 0 1 15 2.5v11a.5.5 0 0 1-.757.429L12 12.5l-2.243 1.429A.5.5 0 0 1 9 13.5V12l-2.243 1.429A.5.5 0 0 1 6 13.5V12l-2.243 1.429A.5.5 0 0 1 3 13.5V2.5a.5.5 0 0 1 .5-.5H1z"/></svg>
        Billing
    </a>
</div>
