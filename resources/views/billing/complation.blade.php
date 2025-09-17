<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Completion Stat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background: linear-gradient(135deg, #007bff, #17a2b8);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.6rem 1rem;
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
            max-height: 75vh;
            overflow-y: auto;
            overflow-x: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #e9ecef;
            z-index: 10;
        }

        tbody td:first-child,
        thead th:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 11;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                📊 Analyse – Completion Stat
            </div>

            <div class="card-body">
                {{-- ✅ Filtres --}}
                <form method="GET" action="{{ route('complation') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label fw-semibold">Date début :</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ $startDate }}">
                    </div>

                    <div class="col-md-3">
                        <label for="end_date" class="form-label fw-semibold">Date fin :</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ $endDate }}">
                    </div>

                    <div class="col-md-3">
                        <label for="call_type" class="form-label fw-semibold">Type d’appel :</label>
                        <select id="call_type" name="call_type" class="form-select">
                            <option value="">-- Tous --</option>
                            <option value="Outgoing" {{ $callType == 'Outgoing' ? 'selected' : '' }}>Outgoing</option>
                            <option value="Incoming" {{ $callType == 'Incoming' ? 'selected' : '' }}>Incoming</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </div>
                </form>


                {{-- ✅ Tableau --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                @if ($stats->isNotEmpty())
                                    @foreach ($stats->first() as $col => $val)
                                        <th>{{ $col }}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stats as $row)
                                <tr>
                                    @foreach ($row as $val)
                                        <td>{{ $val }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted">Aucune donnée trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
