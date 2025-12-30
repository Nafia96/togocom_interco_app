<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing KPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: linear-gradient(to bottom, #ffe066 0%, #ffcf33 70%, #ffcc00 100%);
            color: #004aad;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.75rem 1.25rem;
            border-bottom: 3px solid #004aad;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .kpi-box h6 {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .kpi-box .value {
            font-size: 2rem;
            font-weight: bold;
        }

        .table {
            font-size: 0.9rem;
        }

        .btn-filter {
            background: linear-gradient(180deg, #0056d2 0%, #004aad 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            padding: 6px 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }

        .btn-filter:hover {
            background: linear-gradient(180deg, #0069ff 0%, #0056d2 100%);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .chart-container {
            height: 400px;
            margin-bottom: 2rem;
        }

        thead th {
            background: #e9ecef;
            font-weight: 600;
            text-align: center;
        }

        .text-success {
            color: #198754 !important;
        }

        .badge-revenue {
            background-color: #28a745;
        }

        .badge-charge {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-bar me-2"></i>
                    Tableau de Bord KPI - Facturation
                </div>
                <a href="{{ route('kpi.pivot', request()->all()) }}" class="btn btn-sm btn-warning ms-3 px-3" style="font-weight:700;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow me-1" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10.293 3.293a1 1 0 0 1 1.414 0L15 6.586V4a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.586L11.707 6.707a1 1 0 0 1 0-1.414l-1.414-1.414zM5 9l2-2 3 3 4-4 1 1-5 5-3-3-2 2-1-1z"/></svg>
                    KPI
                </a>
            </div>

            <div class="card-body">
                <!-- Filtres -->
                <form method="GET" action="{{ route('billingKp') }}" class="row g-2 mb-4 align-items-end">
                    <div class="col-auto">
                        <label for="start_date" class="form-label fw-semibold">Date début :</label>
                        <input type="date" id="start_date" name="start_date" class="form-control form-control-sm"
                            value="{{ $start }}">
                    </div>
                    <div class="col-auto">
                        <label for="end_date" class="form-label fw-semibold">Date fin :</label>
                        <input type="date" id="end_date" name="end_date" class="form-control form-control-sm"
                            value="{{ $end }}">
                    </div>
                    <div class="col-auto">
                        <label for="direction" class="form-label fw-semibold">Direction :</label>
                        <select id="direction" name="direction" class="form-select form-control-sm">
                            <option value="">Toutes</option>
                            <option value="revenue" {{ $direction === 'revenue' ? 'selected' : '' }}>Revenue
                            </option>
                            <option value="charge" {{ $direction === 'charge' ? 'selected' : '' }}>Charge</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label for="carrier_name" class="form-label fw-semibold">Opérateur :</label>
                        <select id="carrier_name" name="carrier_name" class="form-select form-control-sm">
                            <option value="">Tous</option>
                            @foreach ($allCarriers as $c)
                                <option value="{{ $c }}" {{ $carrier === $c ? 'selected' : '' }}>{{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-filter">Filtrer</button>
                    </div>
                </form>

                <!-- KPIs Globaux -->
                <h5 class="mt-4 mb-3">📊 KPIs Globaux</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <h6>Total Minutes</h6>
                            <div class="value">{{ number_format($globalKpis['total_minutes'], 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <h6>Total Montant CFA</h6>
                            <div class="value">{{ number_format($globalKpis['total_amount'], 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <h6>Nombre d'Enregistrements</h6>
                            <div class="value">{{ number_format($globalKpis['record_count'], 0) }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-box">
                            <h6>Opérateurs Uniques</h6>
                            <div class="value">{{ $globalKpis['unique_carriers'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Graphique journalier -->
                <h5 class="mt-5 mb-3">📈 Évolution Journalière</h5>
                <div class="chart-container">
                    <canvas id="dailyChart"></canvas>
                </div>

                <!-- Tableau journalier -->
                <h5 class="mt-5 mb-3">📋 Détails Journaliers</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Direction</th>
                                <th class="text-end">Total Minutes</th>
                                <th class="text-end">Total Montant</th>
                                <th class="text-center">Opérateurs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dailyKpis as $kpi)
                                <tr>
                                    <td class="text-center">{{ $kpi->day }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $kpi->direction === 'revenue' ? 'badge-revenue' : 'badge-charge' }}">
                                            {{ ucfirst($kpi->direction) }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($kpi->total_minutes, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($kpi->total_amount, 0, ',', ' ') }}</td>
                                    <td class="text-center">{{ $kpi->carrier_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune donnée disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tableau par opérateur -->
                <h5 class="mt-5 mb-3">🏢 Détails par Opérateur</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Opérateur</th>
                                <th class="text-center">Direction</th>
                                <th class="text-end">Total Minutes</th>
                                <th class="text-end">Total Montant</th>
                                <th class="text-center">Enregistrements</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($carrierKpis as $kpi)
                                <tr>
                                    <td><strong>{{ $kpi->carrier_name }}</strong></td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $kpi->direction === 'revenue' ? 'badge-revenue' : 'badge-charge' }}">
                                            {{ ucfirst($kpi->direction) }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ number_format($kpi->total_minutes, 0, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($kpi->total_amount, 0, ',', ' ') }}</td>
                                    <td class="text-center">{{ number_format($kpi->record_count, 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune donnée disponible</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Graphique journalier
        const dailyKpis = @json($dailyKpis);
        const days = [...new Set(dailyKpis.map(k => k.day))].sort().reverse();

        const revenueData = days.map(day => {
            const revenue = dailyKpis.find(k => k.day === day && k.direction === 'revenue');
            return revenue ? parseFloat(revenue.total_amount) : 0;
        });

        const chargeData = days.map(day => {
            const charge = dailyKpis.find(k => k.day === day && k.direction === 'charge');
            return charge ? parseFloat(charge.total_amount) : 0;
        });

        const ctx = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [
                    {
                        label: 'Revenue',
                        data: revenueData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Charge',
                        data: chargeData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Montant CFA'
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://kit.fontawesome.com/a2d9d6a62e.js" crossorigin="anonymous"></script>
</body>
</html>
