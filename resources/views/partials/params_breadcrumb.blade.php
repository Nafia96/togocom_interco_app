@php
    $qp = request()->query();
    $bc_direction = isset($direction) ? $direction : ($qp['direction'] ?? null);
    $bc_view = isset($view) ? $view : ($qp['view'] ?? null);
    $bc_start = isset($start) ? $start : ($qp['start_date'] ?? null);
    $bc_end = isset($end) ? $end : ($qp['end_date'] ?? null);
    $bc_network = isset($network) ? $network : ($qp['network'] ?? 'ALL');
    $bc_partner = isset($partner) ? $partner : ($qp['partner'] ?? null);
@endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">Direction: <strong>{{ $bc_direction ?? '-' }}</strong></li>
        <li class="breadcrumb-item">Vue: <strong>{{ $bc_view ?? '-' }}</strong></li>
        <li class="breadcrumb-item">Début: <strong>{{ $bc_start ?? '-' }}</strong></li>
        <li class="breadcrumb-item">Fin: <strong>{{ $bc_end ?? '-' }}</strong></li>
        <li class="breadcrumb-item">Network: <strong>{{ $bc_network ?? 'ALL' }}</strong></li>
        @if($bc_partner)
            <li class="breadcrumb-item">Partner: <strong>{{ $bc_partner }}</strong></li>
        @endif
    </ol>
</nav>
