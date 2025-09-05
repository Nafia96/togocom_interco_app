<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pivot Facturation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
            /* ✅ police un peu réduite */
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
            /* un peu plus petit */
            padding: 0.6rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* --- TABLEAU --- */
        table {
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.8rem;
            /* ✅ chiffres plus petits */
        }

        th,
        td {
            white-space: nowrap;
            padding: 4px 8px !important;
            /* ✅ réduit */
            vertical-align: middle !important;
        }

        .table-responsive {
            max-height: 70vh;
            /* ✅ occupe moins d’espace */
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

        /* --- BOUTON TOGGLE --- */
        .toggle-btn {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 15px;
        }

        /* --- GRAPHIQUES --- */
        .chart-container {
            height: 260px;
            /* ✅ réduit */
            margin-bottom: 1rem;
        }
    </style>

</head>

<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-table me-2"></i>
                    Analyse Pivot – Facturation par opérateur
                </span>
                <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">
                    Mode Progression
                </button>
            </div>
            <div class="card-body">
                {{-- Filtres --}}
                <form method="GET" action="{{ route('billingp') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="month" class="form-label fw-semibold">Mois :</label>
                        <input type="month" id="month" name="month" class="form-control"
                            value="{{ $month }}">
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

                    {{-- Nouveau : période personnalisée --}}
                    <div class="col-md-2">
                        <label for="start_date" class="form-label fw-semibold">Date début :</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ $startDate ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <label for="end_date" class="form-label fw-semibold">Date fin :</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ $endDate ?? '' }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>


                {{-- Tableau Valeurs --}}
                <div id="tableValeurs" class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Opérateur</th>
                                @foreach ($days as $day)
                                    <th class="text-center">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operators as $operator => $rows)
                                <tr>
                                    <td>{{ $operator }}</td>
                                    @php $sum = 0; @endphp
                                    @foreach ($days as $day)
                                        @php
                                            $val = $rows->firstWhere('day', $day)->total ?? 0;
                                            $sum += $val;
                                        @endphp
                                        <td class="text-end">{{ $val > 0 ? number_format($val, 0, ',', ' ') : '-' }}
                                        </td>
                                    @endforeach
                                    <td class="text-end bg-light fw-bold">{{ number_format($sum, 0, ',', ' ') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                @foreach ($days as $day)
                                    <td class="text-end">
                                        {{ $totals[$day] > 0 ? number_format($totals[$day], 0, ',', ' ') : '-' }}
                                    </td>
                                @endforeach
                                <td class="text-end">{{ number_format(array_sum($totals), 0, ',', ' ') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Tableau Progression --}}
                <div id="tableProgression" class="table-responsive d-none">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr class="table-success">
                                <th>Opérateur</th>
                                @foreach ($days as $day)
                                    <th class="text-center">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operators as $operator => $rows)
                                <tr>
                                    <td>{{ $operator }}</td>
                                    @php $prev = null; @endphp
                                    @foreach ($days as $day)
                                        @php
                                            $val = $rows->firstWhere('day', $day)->total ?? 0;
                                            $progression =
                                                $prev && $prev > 0 ? round((($val - $prev) / $prev) * 100, 1) : null;
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

                {{-- Graphes --}}
                <div class="mt-5" id="chartsValeurs">
                    <h5 class="mb-3">📊 Évolution journalière par opérateur</h5>
                    <canvas id="chartValeurs"></canvas>
                </div>
                <div class="mt-5 d-none" id="chartsProgression">
                    <h5 class="mb-3">📈 Progression des totaux journaliers (%)</h5>
                    <canvas id="chartProgression"></canvas>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("toggleTableBtn");
            const tableValeurs = document.getElementById("tableValeurs");
            const tableProgression = document.getElementById("tableProgression");
            const chartsValeurs = document.getElementById("chartsValeurs");
            const chartsProgression = document.getElementById("chartsProgression");

            let mode = "valeurs"; // par défaut

            // === Toggle entre Valeurs / Progression ===
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

            // === Données injectées depuis Laravel ===
            const days = @json($days);
            const totals = @json($totals);
            const operators = @json($operators);

            // === Graphe Évolution journalière par opérateur ===
            const ctxValeurs = document.getElementById("chartValeurs").getContext("2d");
            new Chart(ctxValeurs, {
                type: "line",
                data: {
                    labels: days,
                    datasets: Object.keys(operators).map(op => ({
                        label: op,
                        data: days.map(d => {
                            const found = operators[op].find(r => r.day == d);
                            return found ? found.total : 0;
                        }),
                        fill: false,
                        borderWidth: 2
                    }))
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: "index",
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            onClick: (e, legendItem, legend) => {
                                const chart = legend.chart;
                                const index = legendItem.datasetIndex;
                                const ci = chart.data.datasets;

                                const isActive = chart.getActiveElements().some(el => el
                                    .datasetIndex === index);

                                if (!isActive) {
                                    ci.forEach((ds, i) => {
                                        chart.setDatasetVisibility(i, i === index);
                                    });
                                } else {
                                    ci.forEach((ds, i) => chart.setDatasetVisibility(i, true));
                                }
                                chart.update();
                            }
                        }
                    }
                }
            });

            // === Graphe Progression des totaux journaliers (%) ===
            // === Graphe Progression des totaux journaliers (%) ===
            const progression = [];
            for (let i = 1; i < days.length; i++) {
                const prev = totals[days[i - 1]] || 0;
                const curr = totals[days[i]] || 0;
                const change = prev > 0 ? ((curr - prev) / prev * 100).toFixed(1) : 0;
                progression.push(parseFloat(change));
            }

            const ctxProgression = document.getElementById("chartProgression").getContext("2d");
            new Chart(ctxProgression, {
                type: "bar",
                data: {
                    labels: days.slice(1),
                    datasets: [{
                        label: "% Progression",
                        data: progression,
                        backgroundColor: progression.map(val => val >= 0 ? "#28a745" :
                            "#dc3545") // ✅ vert ou rouge
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
