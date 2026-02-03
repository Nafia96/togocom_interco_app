@extends('template.principal_tamplate2')

@section('title', 'lunchpade')

@section('content')

    <div id="app">
        <section class="section">
            <div class="container ">
                <div class="mb-5 col-12 text-center m-auto">
                    <a href="{{ route('home') }}"><img style="height: 120px; width: 140px;" alt="image"
                            src="{{ asset('assets/img/logo.png') }}" class="rounded author-box-picture"></a>
                    <div class="clearfix"></div>
                </div>
                <div class="row container-fluid mt-3 mb-5">
                    <div class="mt-3 col-12 text-center m-auto">
                        <h4 style="color:#16346d;">INTERCO APP LAUNCHPAD</h4>
                    </div>
                </div>
                <div class="row ">

                    <a class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ url('/interco_details') }}">

                        <div>
                            <div class="card" style="background-color: #133272;">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">

                                                    <p class="mb-0"><span class="col-orange"></span> Interconnexions
                                                    </p>
                                                    <h5 class="font-15" style="color:#16346d;"> INTERNATIONALES</h5>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <img src="{{ asset('assets/img/banner/4.png')}}" alt="">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </a>



                    <a class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12" href="{{ url('/roaming') }}">
                        <div class="card">
                            <div class="card-statistic-4">
                                <div class="align-items-center justify-content-between">
                                    <div class="row ">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                            <div class="card-content">
                                                <h5 class="font-15" style="color:#16346d;">ROAMING  </h5>
                                                <p class="mb-0">Services</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                            <div class="banner-img">
                                                <img src="{{ asset('assets/img/banner/2.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>



            </div>
        </section>
    </div>
@endsection
