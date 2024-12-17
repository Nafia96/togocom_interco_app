<!DOCTYPE html>
<html lang="fr">


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>YAS
        - @yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href={{ asset('assets/css/app.min.css') }}>
    <!-- Template CSS -->
    <link rel="stylesheet" href={{ asset('assets/css/style.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/components.css') }}>
    <!-- Custom style CSS -->
    <link rel="stylesheet" href={{ asset('assets/css/custom.css') }}>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('assets/img/logo.png') }}' />
</head>

<body
    style="background-image: url({{ asset('assets/img/app_back.jpg') }});background-size: 100%;background-repeat: no-repeat;margin: 0;padding: 0">
    <div class="loader"></div>

    <div class=" col-12 text-center m-auto">
        <a href="{{ route('home') }}"><img style="height: 100px; width: 100px;" alt="image"
                src="{{ asset('assets/img/logo.png') }}" class="rounded author-box-picture"></a>
        <div class="clearfix"></div>
    </div>

    @if (Session::has('error'))
        <script type="text/javascript" src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
        <script type="text/javascript">
            ;
            swal("{{ session('error') }}", "Merci", "error");
        </script>
    @endif
    @if (Session::has('success'))
        <script type="text/javascript" src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
        <script type="text/javascript">
            ;
            swal("{{ session('success') }}", "Merci", "success");
        </script>
    @endif

    @yield('content')


    <div class="simple-footer" style="color: #fbd305;font-weight: bold; ">
        Copyright &copy; YAS {{ date('Y') }}
    </div>

    <script src={{ asset('assets/js/app.min.js') }}></script>
    <!-- JS Libraies -->
    <script src={{ asset('assets/bundles/apexcharts/apexcharts.min.js') }}></script>
    <!-- Page Specific JS File -->
    <script src={{ asset('assets/js/page/index.js') }}></script>
    <!-- Template JS File -->
    <script src={{ asset('assets/js/scripts.js') }}></script>
    <!-- Custom JS File -->
    <script src={{ asset('assets/js/custom.js') }}></script>
</body>


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

</html>
