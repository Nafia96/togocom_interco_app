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
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }
        .card-header {
            background: linear-gradient(135deg, #ffcf33, #007bff);
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
            background: #ecef4d !important;
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
        /* Harmonized pivot header title color */
        .pivot-header-title {
            color: #007bff !important;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-table me-2"></i>
                <span class="pivot-header-title">Pivot – Facturation par réseau destination et opérateur</span>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @php
                    $qs = [];
                    foreach (['month','filter','start_date','end_date','carrier_name','orig_net_name'] as $k) {
                        if (request($k) !== null && request($k) !== '') $qs[$k] = request($k);
                    }
                @endphp
                <a href="{{ route('billingp', $qs) }}" class="btn btn-sm btn-light text-primary">Partenaire</a>
                <a href="{{ route('billingPivotCountryCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Pays</a>
                <a href="{{ route('billingPivotNetCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Network</a>
                <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">
                    Mode Progression
                </button>
            </div>
            @php
                $isOutbound = in_array(strtolower($filter ?? 'entrant'), ['charge', 'sortant']);
            @endphp
        </div>
        <!-- Breadcrumb Filtres -->
        <nav aria-label="breadcrumb" class="px-3 pt-2">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><strong>Mois :</strong> {{ $month ?? '-' }}</li>
                <li class="breadcrumb-item"><strong>Type :</strong>
                    @switch($filter)
                        @case('revenu') Revenu @break
                        @case('charge') Charge @break
                        @case('sortant') Volume sortant @break
                        @default Volume entrant
                    @endswitch
                </li>
                <li class="breadcrumb-item"><strong>Opérateur :</strong> {{ $carrier ? $carrier : 'Tous' }}</li>
                <li class="breadcrumb-item"><strong>{{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }} :</strong> {{ request('orig_net_name') ? request('orig_net_name') : 'Tous' }}</li>
                <li class="breadcrumb-item"><strong>Date début :</strong> {{ $startDate ?? '-' }}</li>
                <li class="breadcrumb-item"><strong>Date fin :</strong> {{ $endDate ?? '-' }}</li>
            </ol>
        </nav>
        <div class="card-body">
            {{-- Filtres --}}
            <form method="GET" action="{{ route('billingPivotNetCarrier') }}" class="row g-2 mb-4 align-items-end flex-nowrap">
                <div class="col-auto">
                    <label for="month" class="form-label fw-semibold mb-1">Mois :</label>
                    <input type="month" id="month" name="month" class="form-control form-control-sm" value="{{ $month }}">
                </div>
                <div class="col-auto">
                    <label for="filter" class="form-label fw-semibold mb-1">Type :</label>
                    <select id="filter" name="filter" class="form-select form-control-sm">
                        <option value="entrant" {{ $filter == 'entrant' ? 'selected' : '' }}>Volume entrant</option>
                        <option value="sortant" {{ $filter == 'sortant' ? 'selected' : '' }}>Volume sortant</option>
                        <option value="revenu" {{ $filter == 'revenu' ? 'selected' : '' }}>Revenu</option>
                        <option value="charge" {{ $filter == 'charge' ? 'selected' : '' }}>Charge</option>
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
                    <label for="orig_net_name" class="form-label fw-semibold mb-1">Réseau origine :</label>
                    <input type="text" id="orig_net_name" name="orig_net_name" class="form-control form-control-sm" value="{{ request('orig_net_name') }}" placeholder="Filtrer réseau...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success btn-sm w-100">Filtrer</button>
                </div>
            </form>

            {{-- Tableau Valeurs --}}
            <div id="tableValeurs" class="table-responsive">
                <div class="d-flex justify-content-end mb-2 gap-2 align-items-center">
                    <div class="input-group input-group-sm" style="width:180px;">
                        <button class="btn btn-outline-secondary" type="button" id="sortTotalBtn_nets" title="Trier par Total">Trier Total ▲▼</button>
                        <select id="topNSelect_nets" class="form-select">
                            <option value="all">Tous</option>
                            <option value="5">Top 5</option>
                            <option value="10">Top 10</option>
                        </select>
                    </div>
                </div>
                <table id="pivotTableNet" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="table-success text-dark">{{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }}</th>
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
                            <th class="table-success text-dark">{{ $isOutbound ? 'Réseau destination' : 'Réseau origine' }}</th>
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
        '#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf',
        '#393b79','#637939','#8c6d31','#843c39','#7b4173','#3182bd','#31a354','#756bb1','#636363','#de9ed6',
        '#393b79','#5254a3','#6b6ecf','#9c9ede','#637939','#8ca252','#b5cf6b','#cedb9c','#8c6d31','#bd9e39',
        '#e7ba52','#e7cb94','#7b4173','#a55194','#ce6dbd','#de9ed6','#9c9ede','#393b79','#1f77b4','#aec7e8'
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
        try { localStorage.setItem('pivot_color_map_v1', JSON.stringify(_colorMap)); } catch (e) { /* ignore */ }
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
            .filter(r => r.orig_net_name && r.orig_net_name.toLowerCase().includes(selectedNet.toLowerCase()))
            .map(r => r.orig_net_name))];

        filteredNets.forEach((net, idx) => {
            const netData = periods.map(p => {
                let filtered = records.filter(r => r.period === p && r.orig_net_name === net);
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
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
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
                updateChartForTopNames(chartInstance, topNames, /*keepAggregatedLabel*/ curveLabel);
            });
        }
        if (topSelect) {
            topSelect.addEventListener('change', function() {
                applyTopN('pivotTableNet', this.value);
                const topNames = getTopNamesFromTable('pivotTableNet', this.value);
                updateChartForTopNames(chartInstance, topNames, /*keepAggregatedLabel*/ curveLabel);
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
            if (typeof keepAggregatedLabel !== 'undefined' && _normalizeLabel(ds.label) === _normalizeLabel(keepAggregatedLabel)) {
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
</body>
</html>
