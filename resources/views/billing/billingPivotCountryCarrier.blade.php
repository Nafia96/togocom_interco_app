<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pivot Facturation par Pays & Opérateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }
        .card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.6rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.8rem;
        }
        th,
        td {
            white-space: nowrap;
            padding: 4px 8px !important;
            vertical-align: middle !important;
        }
        .table-responsive {
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            background: #e9ecef;
            z-index: 10;
            font-size: 0.8rem;
        }
        tbody td:first-child,
        thead th:first-child,
        tfoot td:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 11;
            font-weight: 600;
        }
        tfoot {
            position: sticky;
            bottom: 0;
            z-index: 9;
        }
        tfoot tr {
            background: #198754 !important;
            color: white !important;
            font-weight: bold;
        }
        .toggle-btn {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 15px;
        }
        .chart-container {
            height: 260px;
            margin-bottom: 1rem;
        }
        /* Zébrage personnalisé : vert clair et blanc */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e3fcec !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff !important;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-table me-2"></i>
                Pivot – Facturation par pays origine et opérateur
            </span>
            <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">
                Mode Progression
            </button>
        </div>
        <!-- Breadcrumb Filtres -->
        <nav aria-label="breadcrumb" class="px-3 pt-2">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><strong>Mois :</strong> {{ $month ?? '-' }}</li>
                <li class="breadcrumb-item"><strong>Type :</strong> {{ $filter == 'revenu' ? 'Revenu' : 'Volume entrant' }}</li>
                <li class="breadcrumb-item"><strong>Opérateur :</strong> {{ $carrier ? $carrier : 'Tous' }}</li>
                <li class="breadcrumb-item"><strong>Pays origine :</strong> {{ request('orig_country_name') ? request('orig_country_name') : 'Tous' }}</li>
                <li class="breadcrumb-item"><strong>Date début :</strong> {{ $startDate ?? '-' }}</li>
                <li class="breadcrumb-item"><strong>Date fin :</strong> {{ $endDate ?? '-' }}</li>
            </ol>
        </nav>
        <div class="card-body">
            {{-- Filtres --}}
            <form method="GET" action="{{ route('billingPivotCountryCarrier') }}" class="row g-2 mb-4 align-items-end flex-nowrap">
                <div class="col-auto">
                    <label for="month" class="form-label fw-semibold mb-1">Mois :</label>
                    <input type="month" id="month" name="month" class="form-control form-control-sm" value="{{ $month }}">
                </div>
                <div class="col-auto">
                    <label for="filter" class="form-label fw-semibold mb-1">Type :</label>
                    <select id="filter" name="filter" class="form-select form-control-sm">
                        <option value="entrant" {{ $filter == 'entrant' ? 'selected' : '' }}>Volume entrant</option>
                        <option value="revenu" {{ $filter == 'revenu' ? 'selected' : '' }}>Revenu</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="start_date" class="form-label fw-semibold mb-1">Date début :</label>
                    <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{ $startDate ?? '' }}">
                </div>
                <div class="col-auto">
                    <label for="end_date" class="form-label fw-semibold mb-1">Date fin :</label>
                    <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{ $endDate ?? '' }}">
                </div>
                <div class="col-auto">
                    <label for="carrier_name" class="form-label fw-semibold mb-1">Opérateur :</label>
                    <select id="carrier_name" name="carrier_name" class="form-select form-control-sm">
                        <option value="">Tous</option>
                        @foreach ($allCarriers as $carrierOption)
                            <option value="{{ $carrierOption }}" {{ request('carrier_name') == $carrierOption ? 'selected' : '' }}>
                                {{ $carrierOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="orig_country_name" class="form-label fw-semibold mb-1">Pays origine :</label>
                    <input type="text" id="orig_country_name" name="orig_country_name" class="form-control form-control-sm" value="{{ request('orig_country_name') }}" placeholder="Filtrer pays...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success btn-sm w-100">Filtrer</button>
                </div>
            </form>

            {{-- Tableau Valeurs --}}
            <div id="tableValeurs" class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="table-success text-dark">Pays origine</th>
                            @foreach ($days as $day)
                                <th class="text-center table-success text-dark">{{ $day }}</th>
                            @endforeach
                            <th class="table-success text-dark">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Regroupement des données par pays origine
                            $pivot = [];
                            foreach ($records as $row) {
                                $country = $row->orig_country_name;
                                $day = $row->period;
                                $pivot[$country][$day] = $row->value;
                            }
                        @endphp
                        @foreach ($pivot as $country => $rowDays)
                            @php
                                $rowColor = $loop->odd ? '#e3fcec' : '#ffffff';
                            @endphp
                            <tr style="background-color: {{ $rowColor }};">
                                <td>
                                    <a href="{{ route('billingPivotNetCarrier', array_merge(request()->except('page'), ['orig_net_name' => $country])) }}" class="text-decoration-underline text-success">
                                        {{ $country }}
                                    </a>
                                </td>
                                @php $sum = 0; @endphp
                                @foreach ($days as $day)
                                    @php
                                        $val = $rowDays[$day] ?? 0;
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
                            @php
                                $totals = [];
                                foreach ($days as $day) {
                                    $totals[$day] = $records->where('period', $day)->sum('value');
                                }
                            @endphp
                            @foreach ($days as $day)
                                <td class="text-end">{{ $totals[$day] > 0 ? number_format($totals[$day], 0, ',', ' ') : '-' }}</td>
                            @endforeach
                            <td class="text-end">{{ number_format(array_sum($totals), 0, ',', ' ') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            {{-- Tableau Progression --}}
            <div id="tableProgression" class="table-responsive d-none">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead>
                        <tr class="table-success">
                            <th class="table-success text-dark">Pays origine</th>
                            @foreach ($days as $day)
                                <th class="text-center table-success text-dark">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $pivot = [];
                            foreach ($records as $row) {
                                $country = $row->orig_country_name;
                                $day = $row->period;
                                $pivot[$country][$day] = $row->value;
                            }
                        @endphp
                        @foreach ($pivot as $country => $rowDays)
                            @php
                                $rowColor = $loop->odd ? '#e3fcec' : '#ffffff';
                            @endphp
                            <tr style="background-color: {{ $rowColor }};">
                                <td>{{ $country }}</td>
                                @php $prev = null; @endphp
                                @foreach ($days as $day)
                                    @php
                                        $val = $rowDays[$day] ?? 0;
                                        $progression = $prev && $prev > 0 ? round((($val - $prev) / $prev) * 100, 1) : null;
                                        $prev = $val;
                                    @endphp
                                    <td class="text-end">
                                        @if (!is_null($progression))
                                            <span class="{{ $progression >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $progression }} %
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Graphiques --}}
            <div class="mt-5" id="chartsValeurs">
                <h5 class="mb-3">📊 Évolution par opérateur</h5>
                <canvas id="chartValeurs"></canvas>
            </div>
            <div class="mt-5 d-none" id="chartsProgression">
                <h5 class="mb-3">📈 Progression des totaux journaliers (%)</h5>
                <canvas id="chartProgression"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("toggleTableBtn");
    const tableValeurs = document.getElementById("tableValeurs");
    const tableProgression = document.getElementById("tableProgression");
    const chartsValeurs = document.getElementById("chartsValeurs");
    const chartsProgression = document.getElementById("chartsProgression");
    let mode = "valeurs";
    toggleBtn.addEventListener("click", function() {
        if (mode === "valeurs") {
            tableValeurs.classList.add("d-none");
            tableProgression.classList.remove("d-none");
            chartsValeurs.classList.add("d-none");
            chartsProgression.classList.remove("d-none");
            toggleBtn.innerText = "Mode Valeurs";
            mode = "progression";
        } else {
            tableValeurs.classList.remove("d-none");
            tableProgression.classList.add("d-none");
            chartsValeurs.classList.remove("d-none");
            chartsProgression.classList.add("d-none");
            toggleBtn.innerText = "Mode Progression";
            mode = "valeurs";
        }
    });
    const records = @json($records);
    // Courbe unique : somme de tous les réseaux origine par jour
    const selectedCarrier = "{{ request('carrier_name') ? request('carrier_name') : 'Tous' }}";
    const selectedCountry = "{{ request('orig_country_name') ? request('orig_country_name') : 'Tous' }}";
    let curveLabel = 'Tous';
    if (selectedCarrier !== 'Tous' && selectedCountry !== 'Tous') {
        curveLabel = selectedCarrier + '-' + selectedCountry;
    } else if (selectedCarrier !== 'Tous') {
        curveLabel = selectedCarrier;
    } else if (selectedCountry !== 'Tous') {
        curveLabel = selectedCountry;
    }
    const periods = [...new Set(records.map(r => r.period))].sort();
    const dailyTotals = periods.map(p => {
        // Si un opérateur est sélectionné, filtrer par carrier_name
        let filtered = records.filter(r => r.period === p);
        if (selectedCarrier !== 'Tous') {
            filtered = filtered.filter(r => r.carrier_name === selectedCarrier);
        }
        return filtered.reduce((acc, r) => acc + parseFloat(r.value ?? 0), 0);
    });
    console.log('Valeurs cumulées par jour pour le graphe:', dailyTotals);
    const ctxValeurs = document.getElementById("chartValeurs").getContext("2d");
    new Chart(ctxValeurs, {
        type: "line",
        data: {
            labels: periods,
            datasets: [{
                label: curveLabel,
                data: dailyTotals,
                borderWidth: 2,
                fill: false,
                borderColor: '#28a745',
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    const totals = {};
    periods.forEach(p => {
        totals[p] = records.filter(r => r.period === p).reduce((acc, r) => acc + (r.value ?? 0), 0);
    });
    const progression = [];
    for (let i = 1; i < periods.length; i++) {
        const prev = totals[periods[i - 1]] || 0;
        const curr = totals[periods[i]] || 0;
        const change = prev > 0 ? ((curr - prev) / prev * 100).toFixed(1) : 0;
        progression.push(parseFloat(change));
    }
    const ctxProgression = document.getElementById("chartProgression").getContext("2d");
    new Chart(ctxProgression, {
        type: "bar",
        data: {
            labels: periods.slice(1),
            datasets: [{
                label: "% Progression",
                data: progression,
                backgroundColor: progression.map(val => val >= 0 ? "#28a745" : "#dc3545")
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    color: "#000",
                    anchor: "end",
                    align: "top",
                    formatter: (val) => val + "%"
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => val + "%"
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
<script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>
</body>
</html>
