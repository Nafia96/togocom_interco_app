<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pivot Facturation - Vue Annuelle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
            font-size: 0.85rem;
        }

        .table-heading-country {
            background: linear-gradient(90deg, #4caf50 0%, #81c784 100%) !important;
            color: white !important;
            font-weight: bold !important;
            text-transform: uppercase;
            text-align: center;
            font-size: 1.1em !important;
            letter-spacing: 0.5px !important;
            border: 2px solid #388e3c !important;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: linear-gradient(to bottom, #ffe066 0%, #ffcf33 70%, #ffcc00 100%);
            color: #004aad;
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

        .chart-container {
            height: 260px;
            margin-bottom: 1rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #e3fcec !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff !important;
        }

        #topNSelect_ops {
            font-size: 0.85rem;
            font-weight: 500;
            color: #004aad;
            border: 1.5px solid #004aad;
            border-radius: 10px;
            background-color: #fff8cc;
            padding: 4px 10px;
            transition: all 0.25s ease;
        }

        #topNSelect_ops:focus,
        #topNSelect_ops:hover {
            background-color: #004aad;
            color: #fff;
            border-color: #004aad;
            font-weight: 600;
            box-shadow: 0 0 6px rgba(0, 74, 173, 0.4);
        }
    </style>

</head>

<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-table me-2"></i>
                    <span class="pivot-header-title">Analyse Pivot Annuelle – Facturation par opérateur</span>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php
                        $qs = [];
                        foreach (['filter', 'start_date', 'end_date'] as $k) {
                            if (request($k) !== null && request($k) !== '') {
                                $qs[$k] = request($k);
                            }
                        }
                    @endphp
                    <a href="{{ route('billingPivotCountryCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Pays</a>
                    <a href="{{ route('billingPivotNetCarrier', $qs) }}" class="btn btn-sm btn-light text-primary">Network</a>
                    <button id="toggleTableBtn" class="btn btn-sm btn-light text-success toggle-btn">Mode Progression</button>
                </div>
            </div>

            <!-- Breadcrumb Filtres -->
            <nav aria-label="breadcrumb" class="px-3 pt-2">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><strong>Vue :</strong>
                        @php $vt = request('view_type', 'year'); @endphp
                        {{ $vt == 'day' ? 'Journalière' : ($vt == 'month' ? 'Mensuelle' : 'Annuelle') }}
                    </li>
                    <li class="breadcrumb-item"><strong>Type :</strong>
                        {{ $filter == 'revenu' ? 'Revenu' : ($filter == 'charge' ? 'Charge' : ($filter == 'sortant' ? 'Volume sortant' : 'Volume entrant')) }}
                    </li>
                    <li class="breadcrumb-item"><strong>Date début :</strong> {{ $startDate ?? '-' }}</li>
                    <li class="breadcrumb-item"><strong>Date fin :</strong> {{ $endDate ?? '-' }}</li>
                </ol>
            </nav>

            <div class="card-body">
                {{-- Filtres --}}
                <form method="GET" action="{{ route('billingp') }}" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label for="view_type" class="form-label fw-semibold">Vue :</label>
                        <select id="view_type" name="view_type" class="form-select">
                            <option value="day" {{ request('view_type','year') == 'day' ? 'selected' : '' }}>Journalière</option>
                            <option value="month" {{ request('view_type','year') == 'month' ? 'selected' : '' }}>Mensuelle</option>
                            <option value="year" {{ request('view_type','year') == 'year' ? 'selected' : '' }}>Annuelle</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filter" class="form-label fw-semibold">Type :</label>
                        <select id="filter" name="filter" class="form-select">
                            <option value="entrant" {{ $filter == 'entrant' ? 'selected' : '' }}>Volume entrant</option>
                            <option value="sortant" {{ $filter == 'sortant' ? 'selected' : '' }}>Volume sortant
                            </option>
                            <option value="revenu" {{ $filter == 'revenu' ? 'selected' : '' }}>Revenu</option>
                            <option value="charge" {{ $filter == 'charge' ? 'selected' : '' }}>Charge</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">Date début :</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ $startDate ?? '' }}">
                    </div>

                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">Date fin :</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ $endDate ?? '' }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Filtrer</button>
                    </div>
                </form>

                {{-- Tableau Valeurs Annuelles --}}
                <div id="tableValeurs" class="table-responsive">
                    <div class="d-flex justify-content-end mb-2 gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width:180px;">
                            <button class="btn btn-outline-secondary" type="button" id="sortTotalBtn_ops"
                                title="Trier par Total">Trier Total ▲▼</button>
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
                                <th class="table-heading-country">Opérateur</th>
                                @foreach ($years as $year)
                                    <th class="text-center">{{ $year }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operators as $operator => $records)
                                <tr>
                                    <td>{{ $operator }}</td>
                                    @php $sum = 0; @endphp
                                    @foreach ($years as $year)
                                        @php
                                            $val = $records
                                                ->where('year', $year)
                                                ->sum('total');
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
                                @foreach ($years as $year)
                                    <td class="text-end">
                                        {{ $totals[$year] > 0 ? number_format($totals[$year], 0, ',', ' ') : '-' }}
                                    </td>
                                @endforeach
                                <td class="text-end">{{ number_format(array_sum($totals), 0, ',', ' ') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Graphes --}}
                <div class="mt-5" id="chartsValeurs">
                    <h5 class="mb-3">📊 Évolution annuelle par opérateur</h5>
                    <div id="chartControls" class="mb-2 d-flex flex-wrap gap-2 align-items-center">
                        <div class="form-check form-check-inline me-2">
                            <input class="form-check-input" type="checkbox" id="toggleAllOps" checked>
                            <label class="form-check-label small" for="toggleAllOps">Toggle tous</label>
                        </div>
                    </div>
                    <canvas id="chartValeurs"></canvas>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // === Données injectées depuis Laravel ===
            const years = @json($years);
            const totals = @json($totals);
            const operators = @json($operators);

            // === Palette de couleurs ===
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
                let h = 2166136261 >>> 0;
                for (let i = 0; i < s.length; i++) {
                    h ^= s.charCodeAt(i);
                    h += (h << 1) + (h << 4) + (h << 7) + (h << 8) + (h << 24);
                }
                return Math.abs(h) % PALETTE.length;
            }

            const _colorMap = (function loadColorMap() {
                try {
                    const raw = localStorage.getItem('pivot_color_map_v1');
                    if (!raw) return {};
                    const p = JSON.parse(raw);
                    if (typeof p === 'object' && p !== null) return p;
                } catch (e) {}
                return {};
            })();

            function persistColorMap() {
                try {
                    localStorage.setItem('pivot_color_map_v1', JSON.stringify(_colorMap));
                } catch (e) {}
            }

            function colorForName(name) {
                if (!name) return '#999999';
                if (_colorMap[name]) return _colorMap[name];
                const baseIndex = hashStringToIndex(name.toString());
                for (let probe = 0; probe < PALETTE.length; probe++) {
                    const idx = (baseIndex + probe) % PALETTE.length;
                    const candidate = PALETTE[idx];
                    const usedBy = Object.keys(_colorMap).find(k => _colorMap[k] === candidate);
                    if (!usedBy) {
                        _colorMap[name] = candidate;
                        persistColorMap();
                        return candidate;
                    }
                }
                const fallbackHue = (baseIndex * 23) % 360;
                const fallback = `hsl(${fallbackHue},65%,45%)`;
                _colorMap[name] = fallback;
                persistColorMap();
                return fallback;
            }

            // === Graphe Évolution annuelle par opérateur ===
            const ctxValeurs = document.getElementById("chartValeurs").getContext("2d");

            // Construire les datasets pour le graphique
            const opNames = Object.keys(operators);
            const datasets = opNames.map((op, idx) => {
                const data = years.map(y => {
                    const found = operators[op].find(r => r.year === y);
                    return found ? found.total : 0;
                });
                const color = colorForName(op);
                return {
                    label: op,
                    data: data,
                    fill: false,
                    borderWidth: 2,
                    borderColor: color,
                    backgroundColor: color,
                    tension: 0.2
                };
            });

            if (datasets.length > 0) {
                datasets[0].borderWidth = 3;
            }

            const chartInstance = new Chart(ctxValeurs, {
                type: 'line',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
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

            // add checkboxes for each operator
            const controlsContainer = document.getElementById('chartControls');
            if (datasets.length > 0 && controlsContainer) {
                datasets.forEach((ds, idx) => {
                    const controlId = `op-toggle-${idx}`;
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
                    const fullLabel = ds.label || '';
                    label.title = fullLabel;
                    label.innerText = fullLabel.length > 24 ? fullLabel.slice(0, 24) + '…' : fullLabel;
                    input.addEventListener('change', function() {
                        chartInstance.data.datasets[idx].hidden = !this.checked;
                        chartInstance.update();
                    });
                    wrapper.appendChild(input);
                    wrapper.appendChild(label);
                    controlsContainer.appendChild(wrapper);
                });

                const toggleAll = document.getElementById('toggleAllOps');
                const perOpInputs = Array.from(controlsContainer.querySelectorAll('input.form-check-input'));

                if (perOpInputs.length === 0 && toggleAll) {
                    toggleAll.closest('.form-check').style.display = 'none';
                }

                function updateMaster() {
                    const checkedCount = perOpInputs.filter(i => i.checked).length;
                    if (checkedCount === 0) {
                        toggleAll.checked = false;
                        toggleAll.indeterminate = false;
                    } else if (checkedCount === perOpInputs.length) {
                        toggleAll.checked = true;
                        toggleAll.indeterminate = false;
                    } else {
                        toggleAll.checked = false;
                        toggleAll.indeterminate = true;
                    }
                }
                updateMaster();
                toggleAll.addEventListener('change', function() {
                    perOpInputs.forEach((inp, i) => {
                        inp.checked = this.checked;
                        inp.dispatchEvent(new Event('change'));
                    });
                    toggleAll.indeterminate = false;
                });
                perOpInputs.forEach(inp => inp.addEventListener('change', updateMaster));
            }

            // --- Tri et filtre Top N ---
            function parseNumberFromCell(text) {
                if (!text) return 0;
                const cleaned = text.toString().replace(/[^0-9\-.,]/g, '').replace(/\./g, '').replace(/,/g, '.');
                const n = parseFloat(cleaned);
                return isNaN(n) ? 0 : n;
            }

            function getSortedRowsByTotalDesc(tableId) {
                const table = document.getElementById(tableId);
                if (!table) return [];
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                return rows.sort((a, b) => {
                    const aVal = parseFloat(a.dataset.total || a.lastElementChild.textContent) || 0;
                    const bVal = parseFloat(b.dataset.total || b.lastElementChild.textContent) || 0;
                    return bVal - aVal;
                });
            }

            function applyTopNIndependent(tableId, topNValue) {
                const table = document.getElementById(tableId);
                if (!table) return;

                const allRows = Array.from(table.querySelectorAll('tbody tr'));
                allRows.forEach(row => row.style.display = '');

                if (topNValue === 'all') return;

                const topN = parseInt(topNValue, 10);
                const sortedRows = getSortedRowsByTotalDesc(tableId);
                const topRows = sortedRows.slice(0, topN);
                const topSet = new Set(topRows);

                allRows.forEach(row => {
                    if (!topSet.has(row)) row.style.display = 'none';
                });
            }

            const sortBtn = document.getElementById('sortTotalBtn_ops');
            const topSelect = document.getElementById('topNSelect_ops');
            let asc = false;

            if (sortBtn) {
                sortBtn.addEventListener('click', function() {
                    asc = !asc;
                    const table = document.getElementById('pivotTableOps');
                    const tbody = table.tBodies[0];
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    rows.sort((a, b) => {
                        const aText = a.cells[a.cells.length - 1].innerText || '';
                        const bText = b.cells[b.cells.length - 1].innerText || '';
                        const aNum = parseNumberFromCell(aText);
                        const bNum = parseNumberFromCell(bText);
                        return asc ? aNum - bNum : bNum - aNum;
                    });
                    rows.forEach(r => tbody.appendChild(r));
                    const topValue = topSelect ? topSelect.value : 'all';
                    applyTopNIndependent('pivotTableOps', topValue);
                });
            }

            if (topSelect) {
                topSelect.addEventListener('change', function() {
                    applyTopNIndependent('pivotTableOps', this.value);
                });
            }
        });
    </script>

</body>

</html>
