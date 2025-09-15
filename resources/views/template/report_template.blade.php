<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - YAS APP</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css"/>

   <style>
    body { background: #f9f9f9; }

    .content-wrapper {
        width: 100%;
        padding: 10px 20px; /* un peu de marge interne */
    }

    /* Navbar */
    .nav-tabs {
        border-bottom: 2px solid #ddd;
        margin-bottom: 15px;
    }
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #555;
        font-weight: 500;
        margin-right: 15px;
    }
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #28a745;
        color: #28a745;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #16346d;
        color: #16346d;
        font-weight: bold;
    }

    /* Tableau en pleine largeur */
    .table-responsive {
        width: 100% !important;
    }
    table.dataTable {
        width: 100% !important;
    }
    .dataTables_wrapper {
        width: 100% !important;
    }

    /* Style léger */
    .card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
    .table th, .table td { vertical-align: middle; text-align: center; }
    .dt-buttons { margin-bottom: 10px; }
</style>


    @yield('styles')
</head>
<body>
    <div class="content-wrapper">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('networkkpi') ? 'active' : '' }}" href="{{ url('networkkpi') }}">
                    Network KPI
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('partnerkpi') ? 'active' : '' }}" href="{{ url('partnerkpi') }}">
                    Partner KPI
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('billingstat') ? 'active' : '' }}" href="{{ url('billingstat') }}">
                    Billing Stat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('trend') ? 'active' : '' }}" href="{{ url('trend') }}">
                    Trend
                </a>
            </li>
        </ul>

        <h4 class="mb-4">@yield('title')</h4>
        @yield('content')
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.html5.min.js') }}"></script>

    @yield('scripts')
</body>
</html>
