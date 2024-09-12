<!DOCTYPE html>
<html lang="fr">


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>TOGOCOM APP - @yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/bundles/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/flag-icon-css/css/flag-icon.min.css') }}">


    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrape4/bootstrape4.css') }}" media="all">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-social/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/fullcalendar/fullcalendar.min.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <!-- Date piker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    <!-- Modal style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/prism/prism.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/bundles/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/codemirror/theme/duotone-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/jquery-selectric/selectric.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/bundles/chocolat/dist/css/chocolat.css') }}">



    <!-- Datatable style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/logo.png') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.css" rel="stylesheet">
    <link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />

    <style type="text/css">
        #mapid {
            height: 500px;
            width: 100%;
        }

        .table.revenu tr:last-child {
            background: #03a04f;
            color: #fcca29;
        }
    </style>

</head>

<body>

    @if (Session::has('error'))
        <script type="text/javascript" src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
        <script type="text/javascript">
            ;
            swal("{{ session('error') }}", "Merci", "error");
        </script>
    @endif
    @if (Session::has('flash_message_success'))
        <script type="text/javascript" src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
        <script type="text/javascript">
            ;
            swal("{{ session('flash_message_success') }}", "Merci", "success");
        </script>
    @endif
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar"
                                class="nav-link nav-link-lg
                           collapse-btn"> <i
                                    data-feather="align-justify"></i></a></li>
                        <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a></li>

                        <li style="transform: translate(250px)">
                            @if (session()->has('register_success'))
                                <div class="alert alert-success">
                                    {{ session()->get('register_success') }}
                                </div>
                            @endif
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="/avatar/default.png" class="user-img-radious-style"> <span
                                class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            @if (getUserType()->type_user == 3)
                                <div class="dropdown-title">SUPER ADMIN : {{ getUserAuth()->first_name }}
                                    {{ getUserAuth()->last_name }}</div>
                            @endif

                            @if (getUserType()->type_user == 2)
                                <div class="dropdown-title">ADMIN : {{ getUserAuth()->first_name }}
                                    {{ getUserAuth()->last_name }}</div>
                            @endif

                            @if (getUserType()->type_user == 1)
                                <div class="dropdown-title"> UTILISATEUR : {{ getUserAuth()->first_name }}
                                    {{ getUserAuth()->last_name }}</div>
                            @endif

                            @if (getUserType()->type_user == 0)
                                <div class="dropdown-title">VISITEUR : {{ getUserAuth()->first_name }}
                                    {{ getUserAuth()->last_name }}</div>
                            @endif
                            <!--<a href="/profile" class="dropdown-item has-icon"> <i class="far
          fa-user"></i> Profile
      </a>-->
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('/update_password') }}" class="dropdown-item has-icon text-success"> <i
                                    class="fas fa-user-cog text-blue"></i>
                                Modifier mot de passe
                            </a>
                            <a href="{{ url('/logout') }}" class="dropdown-item has-icon text-danger"> <i
                                    class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}"> <img alt="image"
                                src="{{ asset('assets/img/logo.png') }}" class="header-logo" /> <span
                                class="logo-name">NATIONAL</span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Menu principal</li>
                        <li class="dropdown">

                            <a href="{{ url('/lunchpade') }}" class="nav-link"><i
                                    data-feather="home"></i><span>Launchpad</span></a>


                        </li>
                        <li class="dropdown">

                            <a href="{{ url('/dashboard') }}" class="nav-link"><i
                                    data-feather="monitor"></i><span>Tableau de Bord</span></a>


                        </li>

                        <li class="menu-header">Gestions des mesures</li>
                        <li
                            class="dropdown  {{ Request::is('show_tgt_tgc') ? 'active' : '' }}
                                  {{ Request::is('liste_operator') ? 'active' : '' }}
                                  {{ Request::is('delete_operator_liste') ? 'active' : '' }}
                                  {{ Request::is('ope_dashboard') ? 'active' : '' }}
                                 ">

                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="radio"></i><span>TOGOTELECOM </span></a>
                            <ul class="dropdown-menu">

                                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                    <li class="{{ Request::is('show_tgt_tgc') ? 'active' : '' }}"><a
                                            class="nav-link " href="{{ route('show_tgt_tgc') }}">TGT - TGC National
                                        </a></li>
                                @endif
                                <li class="{{ Request::is('show_tgt_tgc') ? 'active' : '' }}"><a class="nav-link "
                                        href="{{ route('show_tgt_tgc') }}">TGT - TGC International
                                    </a>
                                </li>

                                @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                                    <li class="{{ Request::is('delete_operator_liste') ? 'active' : '' }}"><a
                                            class="nav-link " href="{{ route('delete_operator_liste') }}">MOOV - TGC via TGT
                                    </a>
                                    </li>
                                @endif


                            </ul>
                        </li>


                        <li
                            class="dropdown  {{ Request::is('all_invoice_list') ? 'active' : '' }}
                              {{ Request::is('all_resum_list') ? 'active' : '' }}
                              {{ Request::is('delete_invoice_list') ? 'active' : '' }}
                             ">

                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="radio"></i><span>TOGOCOCEL </span></a>
                            <ul class="dropdown-menu">

                                <li class="{{ Request::is('all_invoice_list') ? 'active' : '' }}"><a
                                        class="nav-link " href="{{ route('all_invoice_list') }}">TGC - TGT National
                                    </a></li>


                                <li class="{{ Request::is('all_resum_list') ? 'active' : '' }}"><a class="nav-link "
                                        href="{{ route('all_resum_list') }}">TGC - TGT International</a>
                                </li>

                                <li class="{{ Request::is('delete_invoice_list') ? 'active' : '' }}"><a
                                        class="nav-link " href="{{ route('delete_invoice_list') }}">TGC - Moov</a>
                                </li>



                            </ul>
                        </li>


                        <li
                            class="dropdown  {{ Request::is('all_operations') ? 'active' : '' }}
                              {{ Request::is('all_cancel_operations') ? 'active' : '' }}

                             ">

                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="radio"></i><span>MOOV</span></a>
                            <ul class="dropdown-menu">

                                <li class="{{ Request::is('all_operations') ? 'active' : '' }}"><a class="nav-link "
                                        href="{{ route('all_operations') }}"> MOOV - TGT International
                                    </a></li>
                                <li class="{{ Request::is('all_cancel_operations') ? 'active' : '' }}"><a
                                        class="nav-link " href="{{ route('all_cancel_operations') }}"> MOOV - TGT
                                        national</a>
                                </li>

                                <li class="{{ Request::is('all_cancel_operations') ? 'active' : '' }}"><a
                                        class="nav-link " href="{{ route('all_cancel_operations') }}"> MOOV - TGC
                                        national</a>
                                </li>



                            </ul>
                        </li>

                        <li class="menu-header">Gestions des comptes</li>



                        @if (getUserType()->type_user == 3 || getUserType()->type_user == 2)
                            <li class="dropdown  {{ Request::is('logs') ? 'active' : '' }}">
                                <a href="{{ route('logs') }}" class="nav-link"><i
                                        data-feather="package"></i><span>Journal des Actions</span></a>
                            </li>
                        @endif

                        @if (getUserType()->type_user == 3)
                            <li style="position: fixed; bottom: 0;"
                                class="dropdown  {{ Request::is('setting') ? 'active' : '' }}">
                                <a href="{{ route('setting') }}" class="nav-link"><i
                                        data-feather="settings"></i><span>Paramètres </span></a>
                            </li>
                        @endif














                    </ul>
                </aside>
            </div>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">

                    <div class="section-body">
                        <!-- add content here -->

                        @yield('breadcrumb')


                        @yield('content')


                    </div>


                </section>

                @include('national.addMesureModal')

                @if (isset($operators))
                    @foreach ($operators as $operator)
                        @include('operator.voirOperatorModal')
                        @include('invoice.invoiceModal')
                        @include('invoice.addInvoiceModal')
                        @include('invoice.settlementModal')
                    @endforeach
                @endif



                @if (isset($operator))
                    @include('invoice.invoiceModal')
                    @include('invoice.addInvoiceModal')
                    @include('invoice.settlementModal')
                @endif



                @if (isset($operations))
                    @foreach ($operations as $operation)
                        @include('invoice.invoice')
                        @include('invoice.conteste_invoiceModal')
                        @include('invoice.update_all_invoiceModal')
                        @include('invoice.cn_Modal')
                    @endforeach


                @endif

                @if (isset($resums))
                    @foreach ($resums as $resum)
                        @include('invoice.update_invoiceModal')
                    @endforeach


                @endif



                @if (isset($agents))
                    @foreach ($agents as $agent)
                        @include('agents.voirAgentModal')
                    @endforeach


                @endif

                @if (isset($clients))
                    @foreach ($clients as $client)
                        @include('client.voirClientModal')
                    @endforeach


                @endif











            </div>
            <footer class="main-footer">
                <div class="simple-footer" style="width: 100%; margin: auto  !important;">
                    <a href="#"
                        style="color: #e90000fd;font-weight: bold; text-align: center !important; margin: auto !important;">
                        Copyright &copy; TOGOCOM {{ date('Y') }}</a>
                </div>
                <div class="footer-right">
                </div>
            </footer>
        </div>
    </div>



    <script src={{ asset('assets/js/app.min.js') }}></script>
    <!-- JS Libraies -->
    <script src={{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}></script>
    <script src={{ asset('assets/js/page/chart-apexcharts.js') }}></script>

    <!-- Page Specific JS File -->
    <script src={{ asset('assets/js/page/index.js') }}></script>
    <!-- Template JS File -->
    <script src={{ asset('assets/js/scripts.js') }}></script>

    <!-- Custom JS File -->
    <script src={{ asset('assets/js/custom.js') }}></script>

    <script src={{ asset('assets/bundles/izitoast/js/iziToast.min.js') }}></script>
    <!-- Page Specific JS File -->
    <script src={{ asset('assets/js/page/toastr.js') }}></script>

    <!-- JS Datatable -->
    <script src="{{ asset('assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/page/datatables.js') }}"></script>

    <script src="{{ asset('assets/bundles/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/bundles/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('assets/bundles/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/page/ckeditor.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('assets/bundles/prism/prism.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.0/slimselect.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

    <script src="{{ asset('assets/bundles/cleave-js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/cleave-js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/forms-advanced-forms.js') }}"></script>

    <script src="{{ asset('assets/bundles/amcharts4/core.js') }}"></script>
    <script src="{{ asset('assets/bundles/amcharts4/charts.js') }}"></script>
    <script src="{{ asset('assets/bundles/amcharts4/animated.js') }}"></script>
    <script src="{{ asset('assets/bundles/amcharts4/worldLow.js') }}"></script>
    <script src="{{ asset('assets/bundles/amcharts4/maps.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/chart-amchart.js') }}"></script>


    <script src="{{ asset('assets/bundles/sweetalert/sweetalert.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('assets/js/page/sweetalert.js') }}"></script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    @yield('script')
</body>


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

</html>
