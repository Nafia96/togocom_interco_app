@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">
                <i class="fas fa-network-wired"></i> KPI Réseau par Opérateur
            </h2>
        </div>
    </div>

    <!-- Formulaire de filtrage -->
    <div class="card mb-4">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0">Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date de début</label>
                    <input type="date" class="form-control" name="start_period"
                           value="{{ $start }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Date de fin</label>
                    <input type="date" class="form-control" name="end_period"
                           value="{{ $end }}" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Type d'appel</label>
                    <select class="form-select" name="call_type">
                        <option value="">Tous</option>
                        @foreach($callTypes as $type)
                            <option value="{{ $type }}" {{ $callType == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Opérateur</label>
                    <select class="form-select" name="partner_name">
                        <option value="">Tous les opérateurs</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner }}" {{ $partnerName == $partner ? 'selected' : '' }}>
                                {{ $partner }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau Pivot des KPIs par Jour -->
    <div class="card">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0">KPI Réseau par Jour</h5>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            @if($records->count() > 0)
                <table class="table table-sm table-bordered table-hover" style="min-width: 1200px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 150px; position: sticky; left: 0; background-color: #f8f9fa;">Jour</th>
                            <th style="width: 120px; position: sticky; left: 150px; background-color: #f8f9fa;">Type Appel</th>
                            <th style="width: 150px; position: sticky; left: 270px; background-color: #f8f9fa;">Opérateur</th>
                            <th style="width: 120px; position: sticky; left: 420px; background-color: #f8f9fa;">Réseau</th>
                            <th class="text-end">Tentatives</th>
                            <th class="text-end">Complétés</th>
                            <th class="text-end">Répondus</th>
                            <th class="text-end">Minutes</th>
                            <th class="text-center">NER %</th>
                            <th class="text-center">ASR %</th>
                            <th class="text-end">ACD Sec</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentDay = null;
                            $dayGroup = [];
                        @endphp

                        @foreach($records as $record)
                            @php
                                if ($currentDay != $record->day) {
                                    $currentDay = $record->day;
                                    $dayGroup = [];
                                }
                                $dayGroup[] = $record;
                            @endphp

                            <tr>
                                <td style="position: sticky; left: 0; background-color: white; font-weight: bold;">
                                    {{ \Carbon\Carbon::parse($record->day)->format('d/m/Y (D)') }}
                                </td>
                                <td style="position: sticky; left: 150px; background-color: white;">
                                    @if(strtolower($record->call_type) === 'incoming')
                                        <span class="badge bg-info">Entrant</span>
                                    @else
                                        <span class="badge bg-warning">Sortant</span>
                                    @endif
                                </td>
                                <td style="position: sticky; left: 270px; background-color: white;">
                                    <strong>{{ $record->partner_name }}</strong>
                                </td>
                                <td style="position: sticky; left: 420px; background-color: white;">
                                    {{ $record->net_name }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($record->attempt, 0, ',', ' ') }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($record->completed, 0, ',', ' ') }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($record->answered, 0, ',', ' ') }}
                                </td>
                                <td class="text-end">
                                    {{ number_format($record->total_minutes, 2, ',', ' ') }}
                                </td>
                                <td class="text-center">
                                    @if($record->ner_pct >= 75)
                                        <span class="badge bg-success">{{ $record->ner_pct }}%</span>
                                    @elseif($record->ner_pct >= 60)
                                        <span class="badge bg-warning">{{ $record->ner_pct }}%</span>
                                    @else
                                        <span class="badge bg-danger">{{ $record->ner_pct }}%</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($record->asr_pct >= 75)
                                        <span class="badge bg-success">{{ $record->asr_pct }}%</span>
                                    @elseif($record->asr_pct >= 60)
                                        <span class="badge bg-warning">{{ $record->asr_pct }}%</span>
                                    @else
                                        <span class="badge bg-danger">{{ $record->asr_pct }}%</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong>{{ number_format($record->acd_sec, 2) }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Aucune donnée disponible pour la période sélectionnée.
                </div>
            @endif
        </div>
    </div>

    <!-- Tableau Récapitulatif par Opérateur -->
    <div class="card mt-4">
        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h5 class="mb-0">Récapitulatif par Opérateur (Période)</h5>
        </div>
        <div class="card-body">
            @php
                $summary = collect($records)->groupBy('partner_name')->map(function($group) {
                    $totalAttempt = $group->sum('attempt');
                    $totalCompleted = $group->sum('completed');
                    $totalAnswered = $group->sum('answered');
                    $totalMinutes = $group->sum('total_minutes');

                    return [
                        'partner' => $group->first()->partner_name,
                        'attempt' => $totalAttempt,
                        'completed' => $totalCompleted,
                        'answered' => $totalAnswered,
                        'minutes' => $totalMinutes,
                        'ner' => $totalAttempt > 0 ? round(($totalCompleted / $totalAttempt) * 100, 2) : 0,
                        'asr' => $totalAttempt > 0 ? round(($totalAnswered / $totalAttempt) * 100, 2) : 0,
                        'acd' => $totalAnswered > 0 ? round(($totalMinutes * 60) / $totalAnswered, 2) : 0,
                    ];
                });
            @endphp

            @if($summary->count() > 0)
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Opérateur</th>
                            <th class="text-end">Tentatives</th>
                            <th class="text-end">Complétés</th>
                            <th class="text-end">Répondus</th>
                            <th class="text-end">Minutes</th>
                            <th class="text-center">NER %</th>
                            <th class="text-center">ASR %</th>
                            <th class="text-end">ACD Sec</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary as $item)
                            <tr>
                                <td><strong>{{ $item['partner'] }}</strong></td>
                                <td class="text-end">{{ number_format($item['attempt'], 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($item['completed'], 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($item['answered'], 0, ',', ' ') }}</td>
                                <td class="text-end">{{ number_format($item['minutes'], 2, ',', ' ') }}</td>
                                <td class="text-center">
                                    @if($item['ner'] >= 75)
                                        <span class="badge bg-success">{{ $item['ner'] }}%</span>
                                    @elseif($item['ner'] >= 60)
                                        <span class="badge bg-warning">{{ $item['ner'] }}%</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item['ner'] }}%</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item['asr'] >= 75)
                                        <span class="badge bg-success">{{ $item['asr'] }}%</span>
                                    @elseif($item['asr'] >= 60)
                                        <span class="badge bg-warning">{{ $item['asr'] }}%</span>
                                    @else
                                        <span class="badge bg-danger">{{ $item['asr'] }}%</span>
                                    @endif
                                </td>
                                <td class="text-end"><strong>{{ number_format($item['acd'], 2) }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card-header {
        border-radius: 8px 8px 0 0;
    }

    .table thead th {
        font-weight: 600;
        border-top: 2px solid #dee2e6;
    }

    .badge {
        padding: 6px 10px;
        font-size: 0.85rem;
    }
</style>
@endsection
