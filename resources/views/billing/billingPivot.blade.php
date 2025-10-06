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
            background: linear-gradient(135deg, #ffcf33, #007bff);
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
                    <span class="pivot-header-title">Analyse Pivot – Facturation par opérateur</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php
                        $qs = [];
                        foreach (['month','filter','start_date','end_date'] as $k) {
                            if (request($k) !== null && request($k) !== '') $qs[$k] = request($k);
                        }
                    @endphp
                    <a href="{{ route('billingp', $qs) }}" class="btn btn-sm btn-light text-primary">Partenaire</a>
                    <a href="{{ route('billingPivotCountryCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Pays</a>
                    <a href="{{ route('billingPivotNetCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Network</a>
                    <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">Mode Progression</button>
                </div>
            </div>
            <!-- Breadcrumb Filtres -->
            <nav aria-label="breadcrumb" class="px-3 pt-2">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><strong>Mois :</strong> {{ $month ?? '-' }}</li>
                    <li class="breadcrumb-item"><strong>Type :</strong> {{
                        $filter == 'revenu' ? 'Revenu' : ($filter == 'charge' ? 'Charge' : ($filter == 'sortant' ? 'Volume sortant' : 'Volume entrant'))
                    }}</li>
                    <li class="breadcrumb-item"><strong>Date début :</strong> {{ $startDate ?? '-' }}</li>
                    <li class="breadcrumb-item"><strong>Date fin :</strong> {{ $endDate ?? '-' }}</li>
                </ol>
            </nav>
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
                    <div class="d-flex justify-content-end mb-2 gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width:180px;">
                            <button class="btn btn-outline-secondary" type="button" id="sortTotalBtn_ops" title="Trier par Total">Trier Total ▲▼</button>
                            <select id="topNSelect_ops" class="form-select">
                                <option value="all">Tous</option>
                                <option value="5">Top 5</option>
                                <option value="10">Top 10</option>
                            </select>
                        </div>
                    </div>
                    <table id="pivotTableOps" class="table table-bordered table-hover table-striped align-middle">
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
                                    <td>
                                        <a href="{{ route('billingPivotCountryCarrier', array_merge(request()->except('page'), ['carrier_name' => $operator])) }}" class="text-decoration-underline text-success">
                                            {{ $operator }}
                                        </a>
                                    </td>
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
                    <table class="table table-bordered table-hover table-striped align-middle">
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
                        <div id="chartControls" class="mb-2 d-flex flex-wrap gap-2 align-items-center">
                            <div class="form-check form-check-inline me-2">
                                <input class="form-check-input" type="checkbox" id="toggleAllNets" checked>
                                <label class="form-check-label small" for="toggleAllNets">Toggle tous</label>
                            </div>
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

            // === Graphe Évolution journalière par opérateur (amélioré) ===
            const ctxValeurs = document.getElementById("chartValeurs").getContext("2d");

            // Use a central palette if provided, otherwise fall back to the default
            window.PIVOT_PALETTE = window.PIVOT_PALETTE || [
                '#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf',
                '#393b79','#637939','#8c6d31','#843c39','#7b4173','#3182bd','#31a354','#756bb1','#636363','#de9ed6',
                '#393b79','#5254a3','#6b6ecf','#9c9ede','#637939','#8ca252','#b5cf6b','#cedb9c','#8c6d31','#bd9e39',
                '#e7ba52','#e7cb94','#7b4173','#a55194','#ce6dbd','#de9ed6','#9c9ede','#393b79','#1f77b4','#aec7e8'
            ];
            const PALETTE = window.PIVOT_PALETTE;
            function hashStringToIndex(s) { let h = 2166136261 >>> 0; for (let i = 0; i < s.length; i++) { h ^= s.charCodeAt(i); h += (h << 1) + (h << 4) + (h << 7) + (h << 8) + (h << 24); } return Math.abs(h) % PALETTE.length; }

            // restore color assignments from localStorage when available
            const _colorMap = (function loadColorMap(){ try { const raw = localStorage.getItem('pivot_color_map_v1'); if (!raw) return {}; const p = JSON.parse(raw); if (typeof p === 'object' && p !== null) return p; } catch(e){} return {}; })();
            function persistColorMap(){ try { localStorage.setItem('pivot_color_map_v1', JSON.stringify(_colorMap)); } catch(e){} }
            function colorForName(name) { if (!name) return '#999999'; if (_colorMap[name]) return _colorMap[name]; const baseIndex = hashStringToIndex(name.toString()); for (let probe = 0; probe < PALETTE.length; probe++) { const idx = (baseIndex + probe) % PALETTE.length; const candidate = PALETTE[idx]; const usedBy = Object.keys(_colorMap).find(k => _colorMap[k] === candidate); if (!usedBy) { _colorMap[name] = candidate; persistColorMap(); return candidate; } } const fallbackHue = (baseIndex * 23) % 360; const fallback = `hsl(${fallbackHue},65%,45%)`; _colorMap[name] = fallback; persistColorMap(); return fallback; }

            // Build datasets from operators
            const opNames = Object.keys(operators);
            const datasets = opNames.map((op, idx) => {
                const data = days.map(d => { const found = operators[op].find(r => r.day == d); return found ? found.total : 0; });
                // Map color by operator name only
                const color = colorForName(op);
                return { label: op, data: data, fill: false, borderWidth: 2, borderColor: color, backgroundColor: color, tension: 0.2 };
            });

            // Make first dataset (if any) slightly thicker as a highlight (optional)
            if (datasets.length > 0) { datasets[0].borderWidth = 3; }

            const chartInstance = new Chart(ctxValeurs, {
                type: 'line',
                data: { labels: days, datasets: datasets },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // add checkboxes for each operator under chartControls
            const controlsContainer = document.getElementById('chartControls');
            if (datasets.length > 0 && controlsContainer) {
                datasets.forEach((ds, idx) => {
                    const controlId = `op-toggle-${idx}`;
                    const wrapper = document.createElement('div'); wrapper.className = 'form-check form-check-inline';
                    const input = document.createElement('input'); input.className = 'form-check-input'; input.type = 'checkbox'; input.id = controlId; input.checked = true;
                    const label = document.createElement('label'); label.className = 'form-check-label small'; label.htmlFor = controlId; label.style.color = ds.borderColor;
                    const fullLabel = ds.label || '';
                    label.title = fullLabel;
                    label.innerText = fullLabel.length > 24 ? fullLabel.slice(0,24) + '…' : fullLabel;
                    input.addEventListener('change', function() { chartInstance.data.datasets[idx].hidden = !this.checked; chartInstance.update(); });
                    wrapper.appendChild(input); wrapper.appendChild(label); controlsContainer.appendChild(wrapper);
                });
                const toggleAll = document.getElementById('toggleAllNets');
                const perOpInputs = Array.from(controlsContainer.querySelectorAll('input.form-check-input'));

                // Hide master toggle if there are no per-operator inputs
                if (perOpInputs.length === 0 && toggleAll) {
                    toggleAll.closest('.form-check').style.display = 'none';
                }

                function updateMaster() { const checkedCount = perOpInputs.filter(i => i.checked).length; if (checkedCount === 0) { toggleAll.checked = false; toggleAll.indeterminate = false; } else if (checkedCount === perOpInputs.length) { toggleAll.checked = true; toggleAll.indeterminate = false; } else { toggleAll.checked = false; toggleAll.indeterminate = true; } }
                updateMaster();
                toggleAll.addEventListener('change', function() { perOpInputs.forEach((inp, i) => { inp.checked = this.checked; inp.dispatchEvent(new Event('change')); }); toggleAll.indeterminate = false; });
                perOpInputs.forEach(inp => inp.addEventListener('change', updateMaster));
            }

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

            // --- Table sorting / Top N utilities (for Total column) ---
            function parseNumberFromCell(text) {
                if (!text) return 0;
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
                rows.sort((a, b) => {
                    const aText = a.cells[a.cells.length - 1].innerText || '';
                    const bText = b.cells[b.cells.length - 1].innerText || '';
                    const aNum = parseNumberFromCell(aText);
                    const bNum = parseNumberFromCell(bText);
                    return ascending ? aNum - bNum : bNum - aNum;
                });
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

            // Wire controls for operators table
            (function() {
                const sortBtn = document.getElementById('sortTotalBtn_ops');
                const topSelect = document.getElementById('topNSelect_ops');
                if (sortBtn) {
                    let asc = false;
                    sortBtn.addEventListener('click', function() {
                        asc = !asc;
                        sortTableByTotal('pivotTableOps', asc);
                        const v = topSelect ? topSelect.value : 'all';
                        applyTopN('pivotTableOps', v);
                        const topNames = getTopNamesFromTable('pivotTableOps', v);
                        updateChartForTopNames(chartInstance, topNames);
                    });
                }
                if (topSelect) {
                    topSelect.addEventListener('change', function() {
                        applyTopN('pivotTableOps', this.value);
                            const topNames = getTopNamesFromTable('pivotTableOps', this.value);
                            updateChartForTopNames(chartInstance, topNames);
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
                        if (typeof keepAggregatedLabel !== 'undefined' && _normalizeLabel(ds.label) === _normalizeLabel(keepAggregatedLabel)) {
                            chart.data.datasets[idx].hidden = false;
                            return;
                        }
                        const dsNorm = _normalizeLabel(ds.label);
                        const shouldShow = normalizedTop.has(dsNorm);
                        chart.data.datasets[idx].hidden = !shouldShow;
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
