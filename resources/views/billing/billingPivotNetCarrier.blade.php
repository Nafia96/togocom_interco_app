{{-- filepath: c:\Users\ndjire\Desktop\togocom_interco_app\resources\views\billing\billingPivotNetCarrier.blade.php --}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Pivot Facturation par Réseau & Opérateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
   <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
            /* ✅ police un peu réduite */
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
            /* fine ligne bleue en bas */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: inset 0 -2px 6px rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem 0.5rem 0 0;
            /* arrondi haut */
        }

        /* --- Style du select Top N --- */
        #topNSelect_nets {
            font-size: 0.85rem;
            font-weight: 500;
            color: #004aad;
            border: 1.5px solid #004aad;
            border-radius: 10px;
            background-color: #fff8cc;
            /* jaune pâle en harmonie avec la carte */
            padding: 4px 10px;
            transition: all 0.25s ease;
        }

        /* --- Effet survol et focus --- */
        #topNSelect_nets:focus,
        #topNSelect_nets:hover {
            background-color: #004aad;
            color: #fff;
            border-color: #004aad;
            font-weight: 600;
            box-shadow: 0 0 6px rgba(0, 74, 173, 0.4);
        }

        /* --- Option visuelle pour le menu déroulant (facultatif, selon navigateur) --- */
        #topNSelect_nets option {
            background-color: #fff;
            color: #004aad;
            font-weight: 500;
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

        /* Bouton "Mode Progression" (vert) */
        .btn-toggle-progress {
            background: linear-gradient(180deg, #33b864 0%, #198754 100%);
            color: #fff !important;
            font-weight: 600;
            border-radius: 20px;
            padding: 6px 14px;
            transition: all 0.2s ease-in-out;
        }

        .btn-toggle-progress:hover {
            background: linear-gradient(180deg, #4dd97f 0%, #2dc46a 100%);
            transform: translateY(-2px);
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

        /* Zébrage personnalisé : vert clair et blanc */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e3fcec !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff !important;
        }

        /* Harmonized pivot header title color */
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
                    <i class="fas fa-table me-2"></i>
                    <span class="pivot-header-title">Pivot – Facturation par réseau destination et opérateur</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php
                        $qs = [];
                        foreach (['view_type', 'filter', 'start_date', 'end_date', 'carrier_name'] as $k) {
                            if (request($k) !== null && request($k) !== '') {
                                $qs[$k] = request($k);
                            }
                        }
                    @endphp
                    <a href="{{ route('billingp', $qs) }}" class="btn btn-sm btn-light text-primary">Opérateurs</a>
                    <a href="{{ route('billingPivotNetCarrier', $qs) }}"
                        class="btn btn-sm btn-warning text-dark" style="font-weight: 700;">Network</a>
                    <a href="{{ route('billingPivotCountryCarrier', $qs) }}"
                        class="btn btn-sm btn-light text-primary">Pays</a>
                    <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">Mode Progression</button>
                    <a href="{{ route('kpi.pivot', $qs) }}" class="btn btn-sm btn-warning ms-3 px-3" style="font-weight:700;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow me-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10.293 3.293a1 1 0 0 1 1.414 0L15 6.586V4a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.586L11.707 6.707a1 1 0 0 1 0-1.414l-1.414-1.414zM5 9l2-2 3 3 4-4 1 1-5 5-3-3-2 2-1-1z"/></svg>
                        KPI
                    </a>
                </div>
                @php
                    $isOutbound = in_array(strtolower($filter ?? 'entrant'), ['charge', 'sortant']);
                @endphp
            </div>
            <!-- Breadcrumb Filtres -->
            <nav aria-label="breadcrumb" class="px-3 pt-2">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><strong>Vue :</strong>
                        @php $vt = request('view_type', 'day'); @endphp
                        {{ $vt == 'day' ? 'Journalière' : ($vt == 'month' ? 'Mensuelle' : 'Annuelle') }}
                    </li>
                    <li class="breadcrumb-item"><strong>Mois :</strong> {{ $month ?? '-' }}</li>
                    <li class="breadcrumb-item"><strong>Type :</strong>
                        @switch($filter)
                            @case('revenu')
                                Revenu
                            @break

                            @case('charge')
                                Charge
                            @break

                            @case('sortant')
                                Volume sortant
                            @break

                            @default
                                Volume entrant
                        @endswitch
                    </li>
                    {{-- <li class="breadcrumb-item"><strong>Opérateur :</strong> {{ $carrier ? $carrier : 'Tous' }}</li> --}}
                        <li class="breadcrumb-item"><strong>Pays :</strong> {{ request('orig_country_name') ? request('orig_country_name') : 'Tous' }}</li>
                        <li class="breadcrumb-item"><strong>{{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }}
                            :</strong> {{ request('orig_net_name') ? request('orig_net_name') : 'Tous' }}</li>
                    <li class="breadcrumb-item"><strong>Date début :</strong> {{ $startDate ?? '-' }}</li>
                    <li class="breadcrumb-item"><strong>Date fin :</strong> {{ $endDate ?? '-' }}</li>
                </ol>
            </nav>
            <div class="card-body">
                {{-- Filtres --}}
                <form method="GET" action="{{ route('billingPivotNetCarrier') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label for="view_type" class="form-label fw-semibold">Vue :</label>
                        <select id="view_type" name="view_type" class="form-select">
                            <option value="day" {{ request('view_type', 'day') == 'day' ? 'selected' : '' }}>Journalière</option>
                            <option value="month" {{ request('view_type', 'day') == 'month' ? 'selected' : '' }}>Mensuelle</option>
                            <option value="year" {{ request('view_type', 'day') == 'year' ? 'selected' : '' }}>Annuelle</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="month" class="form-label fw-semibold">Mois :</label>
                        <input type="month" id="month" name="month" class="form-control"
                            value="{{ $month }}">
                    </div>
                    <div class="col-md-2">
                        <label for="filter" class="form-label fw-semibold">Type :</label>
                        <select id="filter" name="filter" class="form-select">
                            <option value="entrant" {{ $filter == 'entrant' ? 'selected' : '' }}>Volume entrant
                            </option>
                            <option value="sortant" {{ $filter == 'sortant' ? 'selected' : '' }}>Volume sortant
                            </option>
                            <option value="revenu" {{ $filter == 'revenu' ? 'selected' : '' }}>Revenu</option>
                            <option value="charge" {{ $filter == 'charge' ? 'selected' : '' }}>Charge</option>
                        </select>
                    </div>
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
                    <div class="col-md-2">
                        <label for="carrier_name" class="form-label fw-semibold">Opérateur :</label>
                        <select id="carrier_name" name="carrier_name" class="form-select" >
                            <option value="">Tous</option>
                            @foreach ($allCarriers as $carrierOption)
                                <option value="{{ $carrierOption }}"
                                    {{ request('carrier_name') == $carrierOption ? 'selected' : '' }}>
                                    {{ $carrierOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>

                {{-- Tableau Valeurs --}}
                <div id="tableValeurs" class="table-responsive">
                    <div class="d-flex justify-content-end mb-2 gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width:180px;">
                            <button class="btn btn-outline-secondary" type="button" id="sortTotalBtn_nets"
                                title="Trier par Total">Trier Total ▲▼</button>
                            <select id="topNSelect_nets " >
                                <option value="all">Tous</option>
                                <option value="5">Top 5</option>
                                <option value="10">Top 10</option>
                            </select>
                        </div>
                    </div>
                    <table id="pivotTableNet" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="table-heading-country">
                                    {{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }}</th>
                                @foreach ($days as $day)
                                    <th class="text-center table-success text-dark">{{ $day }}</th>
                                @endforeach
                                <th class="table-success text-dark">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Regroupement des données par réseau origine
                                $pivot = [];
                                foreach ($records as $row) {
                                    $net = $row->orig_net_name;
                                    $day = $row->period;
                                    $pivot[$net][$day] = $row->value;
                                }
                            @endphp
                            @foreach ($pivot as $net => $rowDays)
                                @php
                                    $rowColor = $loop->odd ? '#e3fcec' : '#ffffff';
                                @endphp
                                <tr style="background-color: {{ $rowColor }};">
                                    <td>{{ $net }}</td>
                                    @php $sum = 0; @endphp
                                    @foreach ($days as $day)
                                        @php
                                            $val = $rowDays[$day] ?? 0;
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
                                @php
                                    $totals = [];
                                    foreach ($days as $day) {
                                        $totals[$day] = $records->where('period', $day)->sum('value');
                                    }
                                @endphp
                                @foreach ($days as $day)
                                    <td class="text-end">
                                        {{ $totals[$day] > 0 ? number_format($totals[$day], 0, ',', ' ') : '-' }}</td>
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
                                <th class="table-heading-country">
                                    {{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }}</th>
                                @foreach ($days as $day)
                                    <th class="text-center table-success text-dark">{{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $pivot = [];
                                foreach ($records as $row) {
                                    $net = $row->orig_net_name;
                                    $day = $row->period;
                                    $pivot[$net][$day] = $row->value;
                                }
                            @endphp
                            @foreach ($pivot as $net => $rowDays)
                                @php
                                    $rowColor = $loop->odd ? '#e3fcec' : '#ffffff';
                                @endphp
                                <tr style="background-color: {{ $rowColor }};">
                                    <td>{{ $net }}</td>
                                    @php $prev = null; @endphp
                                    @foreach ($days as $day)
                                        @php
                                            $val = $rowDays[$day] ?? 0;
                                            $progression =
                                                $prev && $prev > 0 ? round((($val - $prev) / $prev) * 100, 1) : null;
                                            $prev = $val;
                                        @endphp
                                        <td class="text-end">
                                            @if (!is_null($progression))
                                                <span
                                                    class="{{ $progression >= 0 ? 'text-success' : 'text-danger' }}">
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
                    <div id="chartControls" class="mb-2 d-flex flex-wrap gap-2 align-items-center">
                        <!-- Master toggle (Check/Uncheck all) -->
                        <div class="form-check form-check-inline me-2">
                            <input class="form-check-input" type="checkbox" id="toggleAllNets" checked>
                            <label class="form-check-label small" for="toggleAllNets">Toggle tous les réseaux</label>
                        </div>
                        <!-- checkboxes for per-network toggles (populated by JS) -->
                    </div>
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
            const selectedNet = "{{ request('orig_net_name') ? request('orig_net_name') : 'Tous' }}";
            let curveLabel = 'Tous';
            if (selectedCarrier !== 'Tous' && selectedNet !== 'Tous') {
                curveLabel = selectedCarrier + '-' + selectedNet;
            } else if (selectedCarrier !== 'Tous') {
                curveLabel = selectedCarrier;
            } else if (selectedNet !== 'Tous') {
                curveLabel = selectedNet;
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

            // Color palette and deterministic name -> color mapping
            // Use a central palette if provided, otherwise fall back to the default
            window.PIVOT_PALETTE = window.PIVOT_PALETTE || [
                '#1f77b4', '#ff7f0e', '#2ca02c', '#d62728', '#9467bd', '#8c564b', '#e377c2', '#7f7f7f',
                '#bcbd22', '#17becf',
                '#393b79', '#637939', '#8c6d31', '#843c39', '#7b4173', '#3182bd', '#31a354', '#756bb1',
                '#636363', '#de9ed6',
                '#393b79', '#5254a3', '#6b6ecf', '#9c9ede', '#637939', '#8ca252', '#b5cf6b', '#cedb9c',
                '#8c6d31', '#bd9e39',
                '#e7ba52', '#e7cb94', '#7b4173', '#a55194', '#ce6dbd', '#de9ed6', '#9c9ede', '#393b79',
                '#1f77b4', '#aec7e8'
            ];
            const PALETTE = window.PIVOT_PALETTE;

            function hashStringToIndex(s) {
                let h = 2166136261 >>> 0; // FNV-1a 32-bit
                for (let i = 0; i < s.length; i++) {
                    h ^= s.charCodeAt(i);
                    h += (h << 1) + (h << 4) + (h << 7) + (h << 8) + (h << 24);
                }
                return Math.abs(h) % PALETTE.length;
            }

            // Deterministic, collision-resistant name -> color mapping using linear probing
            // Restore previous assignments from localStorage when available so colors remain stable
            const _colorMap = (function loadColorMap() {
                try {
                    const raw = localStorage.getItem('pivot_color_map_v1');
                    if (!raw) return {};
                    const parsed = JSON.parse(raw);
                    if (typeof parsed === 'object' && parsed !== null) return parsed;
                } catch (e) {
                    // ignore
                }
                return {};
            })();

            function persistColorMap() {
                try {
                    localStorage.setItem('pivot_color_map_v1', JSON.stringify(_colorMap));
                } catch (e) {
                    /* ignore */ }
            }

            function colorForName(name) {
                if (!name) return '#999999';
                if (_colorMap[name]) return _colorMap[name];

                const baseIndex = hashStringToIndex(name.toString());
                // linear probe palette for an unused color (deterministic)
                for (let probe = 0; probe < PALETTE.length; probe++) {
                    const idx = (baseIndex + probe) % PALETTE.length;
                    const candidate = PALETTE[idx];
                    // If candidate not already used by another name, assign it
                    const usedBy = Object.keys(_colorMap).find(k => _colorMap[k] === candidate);
                    if (!usedBy) {
                        _colorMap[name] = candidate;
                        persistColorMap();
                        return candidate;
                    }
                }

                // Palette exhausted: fallback to generated HSL based on full hash
                const fallbackHue = (baseIndex * 23) % 360;
                const fallback = `hsl(${fallbackHue},65%,45%)`;
                _colorMap[name] = fallback;
                persistColorMap();
                return fallback;
            }

            // Build datasets starting with the original aggregated curve
            const datasets = [];
            datasets.push({
                label: curveLabel,
                data: dailyTotals,
                borderWidth: 2,
                fill: false,
                borderColor: '#28a745',
                backgroundColor: '#28a745',
                tension: 0.2,
                pointRadius: 3
            });

            // If user entered a filter in 'Réseau origine', add one dataset per matched origin network
            if (selectedNet !== 'Tous') {
                // Case-insensitive substring match for input filter
                const filteredNets = [...new Set(records
                    .filter(r => r.orig_net_name && r.orig_net_name.toLowerCase().includes(selectedNet
                        .toLowerCase()))
                    .map(r => r.orig_net_name))];

                filteredNets.forEach((net, idx) => {
                    const netData = periods.map(p => {
                        let filtered = records.filter(r => r.period === p && r.orig_net_name ===
                            net);
                        if (selectedCarrier !== 'Tous') {
                            filtered = filtered.filter(r => r.carrier_name === selectedCarrier);
                        }
                        return filtered.reduce((acc, r) => acc + parseFloat(r.value ?? 0), 0);
                    });

                    // Map color by network name only (independent of selected operator)
                    const color = colorForName(net);
                    datasets.push({
                        label: net,
                        data: netData,
                        borderWidth: 2,
                        fill: false,
                        borderColor: color,
                        backgroundColor: color,
                        tension: 0.2,
                        pointRadius: 2
                    });
                });
            }

            // Create a single chart instance that contains both the aggregated curve and per-network curves
            // Make the aggregated curve more prominent
            if (datasets.length > 0) {
                datasets[0].borderWidth = 4;
                datasets[0].pointRadius = 4;
            }

            const chartInstance = new Chart(ctxValeurs, {
                type: 'line',
                data: {
                    labels: periods,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Populate chartControls with checkboxes for per-network datasets (skip aggregated dataset at index 0)
            const controlsContainer = document.getElementById('chartControls');
            if (datasets.length > 1 && controlsContainer) {
                datasets.slice(1).forEach((ds, idx) => {
                    const controlId = `net-toggle-${idx}`;
                    const wrapper = document.createElement('div');
                    wrapper.className = 'form-check form-check-inline';

                    const input = document.createElement('input');
                    input.className = 'form-check-input';
                    input.type = 'checkbox';
                    input.id = controlId;
                    input.checked = true;

                    const label = document.createElement('label');
                    label.className = 'form-check-label small';
                    label.htmlFor = controlId;
                    label.style.color = ds.borderColor;
                    // truncate long labels and add full text as tooltip
                    const fullLabel = ds.label || '';
                    label.title = fullLabel;
                    label.innerText = fullLabel.length > 24 ? fullLabel.slice(0, 24) + '…' : fullLabel;

                    input.addEventListener('change', function() {
                        // dataset index in chartInstance is offset by +1 because we sliced datasets
                        const datasetIndex = idx + 1;
                        const meta = chartInstance.getDatasetMeta(datasetIndex);
                        chartInstance.data.datasets[datasetIndex].hidden = !this.checked;
                        chartInstance.update();
                    });

                    wrapper.appendChild(input);
                    wrapper.appendChild(label);
                    controlsContainer.appendChild(wrapper);
                });
                // Master toggle behavior: attach after creating all per-network inputs
                const toggleAll = document.getElementById('toggleAllNets');
                const perNetInputs = Array.from(controlsContainer.querySelectorAll('input.form-check-input'));

                // Hide master toggle if there are no per-network inputs
                if (perNetInputs.length === 0 && toggleAll) {
                    toggleAll.closest('.form-check').style.display = 'none';
                }

                function updateMasterCheckbox() {
                    const checkedCount = perNetInputs.filter(i => i.checked).length;
                    if (checkedCount === 0) {
                        toggleAll.checked = false;
                        toggleAll.indeterminate = false;
                    } else if (checkedCount === perNetInputs.length) {
                        toggleAll.checked = true;
                        toggleAll.indeterminate = false;
                    } else {
                        toggleAll.checked = false;
                        toggleAll.indeterminate = true;
                    }
                }

                // initialize master state
                updateMasterCheckbox();

                // when master changes, toggle all per-network
                toggleAll.addEventListener('change', function() {
                    perNetInputs.forEach((input, idx) => {
                        input.checked = this.checked;
                        // trigger change event for each to update chart
                        input.dispatchEvent(new Event('change'));
                    });
                    toggleAll.indeterminate = false;
                });

                // when any per-network changes, update master state
                perNetInputs.forEach(inp => {
                    inp.addEventListener('change', updateMasterCheckbox);
                });
            }
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

            // --- Table sorting / Top N utilities (for Total column) ---
            function parseNumberFromCell(text) {
                if (!text) return 0;
                // remove spaces and non-digit chars except minus and dot
                const cleaned = text.toString().replace(/[^0-9\-.,]/g, '').replace(/\./g, '').replace(/,/g, '.');
                const n = parseFloat(cleaned);
                return isNaN(n) ? 0 : n;
            }

            function sortTableByTotal(tableId, ascending = false) {
                const table = document.getElementById(tableId);
                if (!table) return;
                const tbody = table.tBodies[0];
                if (!tbody) return;
                const rows = Array.from(tbody.querySelectorAll('tr'));
                // assume Total is last column
                rows.sort((a, b) => {
                    const aText = a.cells[a.cells.length - 1].innerText || '';
                    const bText = b.cells[b.cells.length - 1].innerText || '';
                    const aNum = parseNumberFromCell(aText);
                    const bNum = parseNumberFromCell(bText);
                    return ascending ? aNum - bNum : bNum - aNum;
                });
                // re-append rows in sorted order
                rows.forEach(r => tbody.appendChild(r));
            }

            function applyTopN(tableId, n) {
                const table = document.getElementById(tableId);
                if (!table) return;
                const tbody = table.tBodies[0];
                if (!tbody) return;
                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.forEach((r, idx) => {
                    if (n === 'all') r.style.display = '';
                    else {
                        const limit = parseInt(n, 10) || 0;
                        r.style.display = idx < limit ? '' : 'none';
                    }
                });
            }

            // Wire controls for nets table
            (function() {
                const sortBtn = document.getElementById('sortTotalBtn_nets');
                const topSelect = document.getElementById('topNSelect_nets');
                if (sortBtn) {
                    let asc = false;
                    sortBtn.addEventListener('click', function() {
                        asc = !asc;
                        sortTableByTotal('pivotTableNet', asc);
                        // after sorting, re-apply topN if selected
                        const v = topSelect ? topSelect.value : 'all';
                        applyTopN('pivotTableNet', v);
                        // update chart datasets to reflect current TopN
                        const topNames = getTopNamesFromTable('pivotTableNet', v);
                        updateChartForTopNames(chartInstance, topNames, /*keepAggregatedLabel*/
                            curveLabel);
                    });
                }
                if (topSelect) {
                    topSelect.addEventListener('change', function() {
                        applyTopN('pivotTableNet', this.value);
                        const topNames = getTopNamesFromTable('pivotTableNet', this.value);
                        updateChartForTopNames(chartInstance, topNames, /*keepAggregatedLabel*/
                            curveLabel);
                    });
                }
            })();

            // --- Helpers to sync chart with Top N selection ---
            function getTopNamesFromTable(tableId, n) {
                const table = document.getElementById(tableId);
                if (!table) return [];
                const tbody = table.tBodies[0];
                if (!tbody) return [];
                const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
                if (n === 'all') return rows.map(r => (r.cells[0].innerText || '').trim());
                const limit = parseInt(n, 10) || 0;
                return rows.slice(0, limit).map(r => (r.cells[0].innerText || '').trim());
            }

            function _normalizeLabel(s) {
                return (s || '').toString().replace(/\s+/g, ' ').trim().toLowerCase();
            }

            function updateChartForTopNames(chart, topNames, keepAggregatedLabel) {
                if (!chart) return;
                const normalizedTop = new Set((topNames || []).map(n => _normalizeLabel(n)));
                const controls = document.getElementById('chartControls');
                const inputs = controls ? Array.from(controls.querySelectorAll('input.form-check-input')) : [];

                chart.data.datasets.forEach((ds, idx) => {
                    // keep aggregated dataset visible if requested
                    if (typeof keepAggregatedLabel !== 'undefined' && _normalizeLabel(ds.label) ===
                        _normalizeLabel(keepAggregatedLabel)) {
                        chart.data.datasets[idx].hidden = false;
                        return;
                    }
                    const dsNorm = _normalizeLabel(ds.label);
                    const shouldShow = normalizedTop.has(dsNorm);
                    chart.data.datasets[idx].hidden = !shouldShow;
                    // sync checkbox if any
                    if (controls) {
                        inputs.forEach(inp => {
                            const lbl = controls.querySelector(`label[for="${inp.id}"]`);
                            if (!lbl) return;
                            const text = _normalizeLabel(lbl.title || lbl.innerText || '');
                            if (text === dsNorm) inp.checked = shouldShow;
                        });
                    }
                });
                chart.update();
            }
        });
    </script>
    <script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>
    @include('partials.date_sync')
</body>

</html>
