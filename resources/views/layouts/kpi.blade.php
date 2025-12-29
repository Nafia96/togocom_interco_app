<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Interco KPI</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color:#f8f9fa; }
        th, td { white-space: nowrap; }
        .kpi-bad { color:#dc3545; font-weight:600; }
        .kpi-warn { color:#fd7e14; font-weight:600; }
    </style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">Interco KPI – Partner / Day</span>
</nav>

<main class="container-fluid py-4">
    @yield('content')
</main>

</body>
</html>
