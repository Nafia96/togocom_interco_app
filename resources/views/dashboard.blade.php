@extends('template.principal_tamplate')

@section('title', 'Accueil')

@section('content')

    <section class="section">
        <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <p style="white-space: nowrap; font-weight:bold; "><span class="font-15"> UTLISATEURS
                                            </span></p>
                                        <h2 class="mb-3 font-18">{{ $sum_of_user }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="assets/img/banner/1.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <p style="white-space: nowrap; font-weight:bold; "><span class="font-15">
                                                OPERATEURS</span></p>
                                        <h2 class=" font-18">{{ $sum_of_ope }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="assets/img/banner/2.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">OPERATEURS EN AFRIQUE</h5>
                                        <h2 class=" font-18">{{ $sum_of_ope_afrique }}</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="assets/img/banner/3.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <p style="white-space: nowrap; font-weight:bold; "><span class="font-15"> FACTURES
                                            </span></p>
                                        <h2 class="font-18">{{ $sum_of_invoice }}</h2>
                                        <p class="mb-0"><span class="col-green">{{ $sum_of_invoice_month }}</span> mois en
                                            cours
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        <img src="assets/img/banner/4.png" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>REPORTING NETTING DES INTERCONNEXIONS INTERNATIONALES {{ date('Y') }} </h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse">


                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor1"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">CHARGES (FCFA)</th>
                                                <th style="color: aliceblue">REVENUS (FCFA)</th>
                                                <th style="color: aliceblue">NETTING (FCFA)</th>
                                                <th style="color: aliceblue">NETTING (EUR)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>{{ periodePrint($result->year . '-' . $result->month) }}</td>
                                                    <td>{{ number_format($result->total_debt, 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($result->total_receivable, 2, ',', ' ') }}</td>
                                                    <td>{{ number_format($result->total_receivable - $result->total_debt, 2, ',', ' ') }}
                                                    </td>
                                                    <td>{{ number_format(($result->total_receivable - $result->total_debt) / 655.957, 2, ',', ' ') }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                            @if (isset($curent_year_result))
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>{{ number_format($curent_year_result->total_year_debt, 2, ',', ' ') }}
                                                    </td>
                                                    <td>{{ number_format($curent_year_result->total_year_receivable, 2, ',', ' ') }}
                                                    </td>
                                                    <td>{{ number_format($curent_year_result->total_year_receivable - $curent_year_result->total_year_debt, 2, ',', ' ') }}
                                                    </td>
                                                    <td>{{ number_format(($curent_year_result->total_year_receivable - $curent_year_result->total_year_debt) / 655.957, 2, ',', ' ') }}
                                                    </td>

                                                </tr>
                                            @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="recent-report__chart">
                                    <div id="chart99"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        @php
            $formattedData = [];
            foreach ($results as $data) {
                $formattedData[] = (int) $data->total_receivable - $data->total_debt;
            }

            $dateData = [];
            foreach ($results as $data) {
                $dateData[] = periodePrint($data->year . '-' . $data->month);
            }

            //dd($dateData);

        @endphp



        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>REPORTING RECOUVREMENT DES INTERCONNEXIONS INTERNATIONALES {{ date('Y') }} </h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse1" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse1">


                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor2"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">MONTANT (FCFA)</th>
                                                <th style="color: aliceblue">MONTANT (EUROS)</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recouvrement_results as $recouvrement_result)
                                                <tr>
                                                    <td>{{ periodePrint($recouvrement_result->year . '-' . $recouvrement_result->month) }}
                                                    </td>
                                                    <td>{{ number_format($recouvrement_result->total_incoming_payement, 2, ',', ' ') }}
                                                    </td>

                                                    <td>{{ number_format($recouvrement_result->total_incoming_payement / 655.957, 2, ',', ' ') }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                            @if (isset($curent_year_recouvrement))
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>{{ number_format($curent_year_recouvrement->total_year_incoming_payement, 2, ',', ' ') }}
                                                    </td>

                                                    <td>{{ number_format($curent_year_recouvrement->total_year_incoming_payement / 655.957, 2, ',', ' ') }}
                                                    </td>

                                                </tr>
                                            @endif


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="recent-report__chart">
                                    <div id="chart90"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>



        @php
            $coveringData = [];
            foreach ($recouvrement_results as $data) {
                $coveringData[] = (int) $data->total_incoming_payement;
            }

            $coveringDate = [];
            foreach ($recouvrement_results as $data) {
                $coveringDate[] = periodePrint($data->year . '-' . $data->month);
            }

            //dd($dateData);

        @endphp



        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>REPORTING REVENUS ET CHARGES (FCFA) DES INTERCONNEXIONS INTERNATIONALES {{ date('Y') - 1 }} VS
                        {{ date('Y') }} </h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse2">


                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor3"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">REVENUS {{ date('Y') }}</th>
                                                <th style="color: aliceblue">REVENUS {{ date('Y') - 1 }}</th>
                                                <th style="color: aliceblue">YoY</th>
                                                <th style="color: aliceblue">Ytd vs. {{ date('Y') - 1 }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_last = 0;
                                                $all_of_curent = 0;
                                            @endphp
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>{{ periodePrint($result->year . '-' . $result->month) }}</td>
                                                    <td>{{ number_format($result->total_receivable, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_curent += $result->total_receivable;
                                                    @endphp
                                                    @foreach ($year_befors as $year_befor)
                                                        @php
                                                            $all_of_last += $year_befor->total_receivable;
                                                        @endphp
                                                        @if ($result->month == $year_befor->month)
                                                            @if ($year_befor->total_receivable == null)
                                                                <td>{{ number_format(0, 2, ',', ' ') }}</td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}%
                                                                </td>
                                                                <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                            @else
                                                                <td>{{ number_format($year_befor->total_receivable, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format((($result->total_receivable - $year_befor->total_receivable) / $year_befor->total_receivable) * 100, 2, ',', ' ') }}%
                                                                </td>
                                                                @if (isset($curent_year_result))
                                                                    <td>{{ number_format((($all_of_curent - $all_of_last) / $all_of_last) * 100, 2, ',', ' ') }}
                                                                        %</td>
                                                                @else
                                                                    <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach


                                                </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor4"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">CHARGE {{ date('Y') }}</th>
                                                <th style="color: aliceblue">CHARGE {{ date('Y') - 1 }}</th>
                                                <th style="color: aliceblue">YoY</th>
                                                <th style="color: aliceblue">Ytd vs. {{ date('Y') - 1 }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_last = 0;
                                                $all_of_curent = 0;
                                            @endphp
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td>{{ periodePrint($result->year . '-' . $result->month) }}</td>
                                                    <td>{{ number_format($result->total_debt, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_curent += $result->total_debt;
                                                    @endphp
                                                    @foreach ($year_befors as $year_befor)
                                                        @php
                                                            $all_of_last += $year_befor->total_debt;
                                                        @endphp
                                                        @if ($result->month == $year_befor->month)
                                                            @if ($year_befor->total_debt == null)
                                                                <td>{{ number_format(0, 2, ',', ' ') }}</td>
                                                                <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                                <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                            @else
                                                                <td>{{ number_format($year_befor->total_debt, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format((($result->total_debt - $year_befor->total_debt) / $year_befor->total_debt) * 100, 2, ',', ' ') }}%
                                                                </td>
                                                                @if (isset($curent_year_result))
                                                                    <td>{{ number_format((($all_of_curent - $all_of_last) / $all_of_last) * 100, 2, ',', ' ') }}
                                                                        %</td>
                                                                @else
                                                                    <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach


                                                </tr>
                                            @endforeach



                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                            <div class="recent-report__chart">
                                <div id="chart33"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        @php
            $RevenuData = [];
            foreach ($results as $data) {
                $RevenuData[] = (int) $data->total_receivable;
            }

            $ChargeData = [];
            foreach ($results as $data) {
                $ChargeData[] = (int) $data->total_debt;
            }

            $RevenuBeforData = [];
            foreach ($year_befors as $data) {
                $RevenuBeforData[] = (int) $data->total_receivable;
            }

            $ChargeBeforData = [];
            foreach ($year_befors as $data) {
                $ChargeBeforData[] = (int) $data->total_debt;
            }

            $rcDate = [];
            foreach ($year_befors as $data) {
                $rcDate[] = periodePrint($data->year + 1 . '-' . $data->month);
            }

            // dd($rcDate);
            $mois = [
                1 => 'JANVIER',
                2 => 'FEVRIER',
                3 => 'MARS',
                4 => 'AVRIL',
                5 => 'MAI',
                6 => 'JUIN',
                7 => 'JUILLET',
                8 => 'AOUT',
                9 => 'SEPTEMBRE',
                10 => 'OCTOBRE',
                11 => 'NOVEMBRE',
                12 => 'DECEMBRE',
            ];

        @endphp

        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>VOLUMES ENTRANTS ET SORTANTS DES INTERCONNEXIONS INTERNATIONALES {{ date('Y') - 1 }} VS
                        {{ date('Y') }}</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse3" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse3">


                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor5"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">ENTRANT {{ date('Y') }}</th>
                                                <th style="color: aliceblue">ENTRANT {{ date('Y') - 1 }}</th>
                                                <th style="color: aliceblue">YoY</th>
                                                <th style="color: aliceblue">Ytd vs. {{ date('Y') - 1 }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_last = 0;
                                                $all_of_curent = 0;
                                            @endphp
                                            @foreach ($volumEntrant_results as $result)
                                                <tr>
                                                    <td>{{ periodePrint($result->year . '-' . $result->month) }}</td>
                                                    <td>{{ number_format($result->volume, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_curent += $result->volume;
                                                    @endphp
                                                    @foreach ($volumEntrantBefor_results as $year_befor)
                                                        @if ($result->month == $year_befor->month)
                                                            @php
                                                                $all_of_last += $year_befor->volume;
                                                            @endphp
                                                            @if ($year_befor->volume == null)
                                                                <td>{{ number_format(0, 2, ',', ' ') }}</td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}%
                                                                </td>
                                                                <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                            @else
                                                                <td>{{ number_format($year_befor->volume, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format((($result->volume - $year_befor->volume) / $year_befor->volume) * 100, 2, ',', ' ') }}%
                                                                </td>
                                                                @if (isset($year_befor))
                                                                    <td>{{ number_format((($all_of_curent - $all_of_last) / $all_of_last) * 100, 2, ',', ' ') }}
                                                                        %</td>
                                                                @else
                                                                    <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor6"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">MOIS</th>
                                                <th style="color: aliceblue">SORTANT {{ date('Y') }}</th>
                                                <th style="color: aliceblue">SORTANT {{ date('Y') - 1 }}</th>
                                                <th style="color: aliceblue">YoY</th>
                                                <th style="color: aliceblue">Ytd vs. {{ date('Y') - 1 }}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_last = 0;
                                                $all_of_curent = 0;
                                            @endphp
                                            @foreach ($volumSortant_results as $result)
                                                <tr>
                                                    <td>{{ periodePrint($result->year . '-' . $result->month) }}</td>
                                                    <td>{{ number_format($result->volume, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_curent += $result->volume;
                                                    @endphp
                                                    @foreach ($volumSortantBefor_results as $year_befor)
                                                        @if ($result->month == $year_befor->month)
                                                            @php
                                                                $all_of_last += $year_befor->volume;
                                                            @endphp
                                                            @if ($year_befor->volume == null)
                                                                <td>{{ number_format(0, 2, ',', ' ') }}</td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}%
                                                                </td>
                                                                <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                            @else
                                                                <td>{{ number_format($year_befor->volume, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format((($result->volume - $year_befor->volume) / $year_befor->volume) * 100, 2, ',', ' ') }}%
                                                                </td>

                                                                @if (isset($year_befor))
                                                                    <td>{{ number_format((($all_of_curent - $all_of_last) / $all_of_last) * 100, 2, ',', ' ') }}
                                                                        %</td>
                                                                @else
                                                                    <td>{{ number_format(0, 2, ',', ' ') }}%</td>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach


                                                </tr>
                                            @endforeach



                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-12">
                            <div class="recent-report__chart">
                                <div id="chart333"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        @php
            $EntrantData = [];
            foreach ($volumEntrant_results as $data) {
                $EntrantData[] = (int) $data->volume;
            }

            $SortantData = [];
            foreach ($volumSortant_results as $data) {
                $SortantData[] = (int) $data->volume;
            }

            $EntrantBeforData = [];
            foreach ($volumEntrantBefor_results as $data) {
                $EntrantBeforData[] = (int) $data->volume;
            }

            $SortantBeforData = [];
            foreach ($volumSortantBefor_results as $data) {
                $SortantBeforData[] = (int) $data->volume;
            }

            $vDate = [];
            foreach ($volumEntrantBefor_results as $data) {
                $vDate[] = periodePrint($data->year + 1 . '-' . $data->month);
            }

        @endphp



        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>COMPARAISON REVENUS (FCFA) PAR OPERATEUR </h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse4" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-plus"></i></a>
                    </div>
                </div>
                <div class="collapse hide" id="mycard-collapse4">


                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor7"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">OPERATEUR AFRIQUE</th>
                                                <th style="color: aliceblue">
                                                  </th>
                                                <th style="color: aliceblue">
                                                    </th>
                                                <th style="color: aliceblue">VARIATION GCGA</th>
                                                <th style="color: aliceblue">%VARIATION</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_current = 0;
                                                $all_of_prvious = 0;
                                                $all_gcca = 0;
                                            @endphp
                                            @foreach ($comparaison_month_befor_afq as $befor)
                                                <tr>
                                                    <td style="width:10%">{{ $befor->operator_name }}</td>
                                                    <td style="width:5%">
                                                        {{ number_format($befor->total_receivable, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_prvious += $befor->total_receivable;
                                                    @endphp
                                                    @foreach ($comparaison_current_month_afq as $result)
                                                        @if ($result->operator_name == $befor->operator_name)
                                                            @php
                                                                $all_of_current += $result->total_receivable;

                                                                $all_gcca +=
                                                                    $result->total_receivable -
                                                                    $befor->total_receivable;
                                                            @endphp
                                                            @if ($befor->total_receivable == null)
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                            @else
                                                                <td style="width:5%">
                                                                    {{ number_format($result->total_receivable, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format($result->total_receivable - $befor->total_receivable, 2, ',', ' ') }}
                                                                </td>
                                                                @if ($result->total_receivable - $befor->total_receivable == null)
                                                                    <td style="width:2%; color:#03a04f;">
                                                                        <span><i
                                                                                class="
                                                                            fas fa-arrow-up text-green">
                                                                            </i> </span>
                                                                        {{ number_format(0, 2, ',', ' ') }}%
                                                                    </td>
                                                                @else
                                                                    @if ((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100 > 0)
                                                                        <td style="width:2%; color:#03a04f;">
                                                                            <span><i
                                                                                    class="
                                                                                fas fa-arrow-up text-green">
                                                                                </i> </span>

                                                                            {{ number_format((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100, 0, ',', '.') }}%
                                                                        </td>
                                                                    @else
                                                                        <td style="width:2%; color:#ec1f28">
                                                                            <span><i class="fas fa-arrow-down text-red">
                                                                                </i> </span>

                                                                            {{ number_format((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100, 0, ',', '.') }}%
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                            @if (isset($comparaison_current_month_afq) || isset($comparaison_month_befor_afq))
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>{{ number_format($all_of_prvious, 2, ',', ' ') }} </td>
                                                    <td>{{ number_format($all_of_current, 2, ',', ' ') }} </td>
                                                    <td>{{ number_format($all_gcca, 2, ',', ' ') }} </td>
                                                    @if ($all_of_prvious > 0)

                                                        @if (($all_gcca / $all_of_prvious) * 100 < 0)
                                                            <td style="width:2%;">
                                                                <span><i class="fas fa-arrow-down "> </i>
                                                                </span>

                                                                {{ number_format(($all_gcca / $all_of_prvious) * 100, 0, ',', '.') }}%
                                                            </td>
                                                        @else
                                                            <td style="width:2%;">
                                                                <span><i
                                                                        class="
                                                                fas fa-arrow-up text-green">
                                                                    </i> </span>
                                                                {{ number_format(($all_gcca / $all_of_prvious) * 100, 0, ',', '.') }}
                                                                %
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endif




                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover revenu" id="tableExpor8"
                                        style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th style="color: aliceblue">OPERATEUR HORS AFRIQUE</th>
                                                <th style="color: aliceblue">
                                                   </th>
                                                <th style="color: aliceblue">
                                                    
                                                </th>
                                                <th style="color: aliceblue">VARIATION GCGA</th>
                                                <th style="color: aliceblue">%VARIATION</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $all_of_current = 0;
                                                $all_of_prvious = 0;
                                                $all_gcca = 0;
                                            @endphp
                                            @foreach ($comparaison_month_befor as $befor)
                                                <tr>
                                                    <td style="width:10%">{{ $befor->operator_name }}</td>
                                                    <td style="width:5%">
                                                        {{ number_format($befor->total_receivable, 2, ',', ' ') }}</td>
                                                    @php
                                                        $all_of_prvious += $befor->total_receivable;
                                                    @endphp
                                                    @foreach ($comparaison_current_month as $result)
                                                        @if ($result->operator_name == $befor->operator_name)
                                                            @php
                                                                $all_of_current += $result->total_receivable;

                                                                $all_gcca +=
                                                                    $result->total_receivable -
                                                                    $befor->total_receivable;
                                                            @endphp
                                                            @if ($befor->total_receivable == null)
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">{{ number_format(0, 2, ',', ' ') }}
                                                                </td>
                                                            @else
                                                                <td style="width:5%">
                                                                    {{ number_format($result->total_receivable, 2, ',', ' ') }}
                                                                </td>
                                                                <td style="width:5%">
                                                                    {{ number_format($result->total_receivable - $befor->total_receivable, 2, ',', ' ') }}
                                                                </td>
                                                                @if ($result->total_receivable - $befor->total_receivable == null)
                                                                    <td style="width:2%; color:#03a04f;">
                                                                        <span><i
                                                                                class="
                                                                            fas fa-arrow-up text-green">
                                                                            </i> </span>
                                                                        {{ number_format(0, 2, ',', ' ') }}%
                                                                    </td>
                                                                @else
                                                                    @if ((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100 > 0)
                                                                        <td style="width:2%; color:#03a04f;">
                                                                            <span><i
                                                                                    class="
                                                                                fas fa-arrow-up text-green">
                                                                                </i> </span>

                                                                            {{ number_format((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100, 0, ',', '.') }}%
                                                                        </td>
                                                                    @else
                                                                        <td style="width:2%; color:#ec1f28">
                                                                            <span><i class="fas fa-arrow-down text-red">
                                                                                </i> </span>

                                                                            {{ number_format((($result->total_receivable - $befor->total_receivable) / $befor->total_receivable) * 100, 0, ',', '.') }}%
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach

                                                </tr>
                                            @endforeach
                                            @if (isset($comparaison_current_month) || isset($comparaison_month_befor))
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <td>{{ number_format($all_of_prvious, 2, ',', ' ') }} </td>
                                                    <td>{{ number_format($all_of_current, 2, ',', ' ') }} </td>
                                                    <td>{{ number_format($all_gcca, 2, ',', ' ') }} </td>

                                                    @if ($all_of_prvious > 0)
                                                        @if (($all_gcca / $all_of_prvious) * 100 < 0)
                                                            <td style="width:2%;">
                                                                <span><i class="fas fa-arrow-down "> </i>
                                                                </span>

                                                                {{ number_format(($all_gcca / $all_of_prvious) * 100, 0, ',', '.') }}%
                                                            </td>
                                                        @else
                                                            <td style="width:2%;">
                                                                <span><i
                                                                        class="
                                                                fas fa-arrow-up text-green">
                                                                    </i> </span>
                                                                {{ number_format(($all_gcca / $all_of_prvious) * 100, 0, ',', '.') }}
                                                                %
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endif




                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>REVENUS OPERATEURS CEDEAO
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="recent-report__chart">
                                            <div style="  width: 100%;
                                            height: 250px;"
                                                id="chartdiv2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>REVENUS OPERATEURS HORS AFRIQUE </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="recent-report__chart">
                                            <div style="  width: 100%;
                                            height: 250px;"
                                                id="chartdiv"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        @php

            $inAfrqData = '';
            foreach ($comparaison_month_befor_afq as $data) {
                $inAfrqData .= ' { value: ' . $data->total_receivable . ", category: '" . $data->operator_name . "' },";
            }

            $horsAfrqData = '';
            foreach ($comparaison_month_befor as $data) {
                $horsAfrqData .=
                    ' { value: ' . $data->total_receivable . ", category: '" . $data->operator_name . "' },";
            }

            // dd($horsAfrqData);

        @endphp
    </section>
    <div class="settingSidebar">
        <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
        </a>
        <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
                <div class="setting-panel-header">Setting Panel
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Select Layout</h6>
                    <div class="selectgroup layout-color w-50">
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="1"
                                class="selectgroup-input-radio select-layout" checked>
                            <span class="selectgroup-button">Light</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="2"
                                class="selectgroup-input-radio select-layout">
                            <span class="selectgroup-button">Dark</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Sidebar Color</h6>
                    <div class="selectgroup selectgroup-pills sidebar-color">
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="1"
                                class="selectgroup-input select-sidebar">
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="2"
                                class="selectgroup-input select-sidebar" checked>
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Color Theme</h6>
                    <div class="theme-setting-options">
                        <ul class="choose-theme list-unstyled mb-0">
                            <li title="white" class="active">
                                <div class="white"></div>
                            </li>
                            <li title="cyan">
                                <div class="cyan"></div>
                            </li>
                            <li title="black">
                                <div class="black"></div>
                            </li>
                            <li title="purple">
                                <div class="purple"></div>
                            </li>
                            <li title="orange">
                                <div class="orange"></div>
                            </li>
                            <li title="green">
                                <div class="green"></div>
                            </li>
                            <li title="red">
                                <div class="red"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="mini_sidebar_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Mini Sidebar</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="sticky_header_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Sticky Header</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                    <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                        <i class="fas fa-undo"></i> Restore Default
                    </a>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('script')

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor1 thead tr .recherche').clone(true).appendTo('#tableExpor1 thead').addClass("rech");
            $('#tableExpor1 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor1').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor2 thead tr .recherche').clone(true).appendTo('#tableExpor2 thead').addClass("rech");
            $('#tableExpor2 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor2').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor3 thead tr .recherche').clone(true).appendTo('#tableExpor3 thead').addClass("rech");
            $('#tableExpor3 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor3').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor4 thead tr .recherche').clone(true).appendTo('#tableExpor4 thead').addClass("rech");
            $('#tableExpor4 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor4').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor5 thead tr .recherche').clone(true).appendTo('#tableExpor5 thead').addClass("rech");
            $('#tableExpor5 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor5').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor6 thead tr .recherche').clone(true).appendTo('#tableExpor6 thead').addClass("rech");
            $('#tableExpor6 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor6').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor7 thead tr .recherche').clone(true).appendTo('#tableExpor7 thead').addClass("rech");
            $('#tableExpor7 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor7').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tableExpor8 thead tr .recherche').clone(true).appendTo('#tableExpor8 thead').addClass("rech");
            $('#tableExpor8 thead .rech ').each(function(i) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Rechercher ' + title +
                    '" />');

                $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                        table
                            .column(i)
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var table = $('#tableExpor8').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                dom: 'Bfrtip',
                info: false,
                ordering: false,
                paging: false,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "language": {
                    "emptyTable": "Aucune donnée disponible dans le tableau",
                    "lengthMenu": "Afficher _MENU_ éléments",
                    "loadingRecords": "Chargement...",
                    "processing": "Traitement...",
                    "zeroRecords": "Aucun élément correspondant trouvé",
                    "paginate": {
                        "first": "Premier",
                        "last": "Dernier",
                        "next": "Suivant",
                        "previous": "Précédent"
                    },
                    "aria": {
                        "sortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        },
                        "1": "1 ligne selectionnée",
                        "_": "%d lignes selectionnées",
                        "cells": {
                            "1": "1 cellule sélectionnée",
                            "_": "%d cellules sélectionnées"
                        },
                        "columns": {
                            "1": "1 colonne sélectionnée",
                            "_": "%d colonnes sélectionnées"
                        }
                    },
                    "autoFill": {
                        "cancel": "Annuler",
                        "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                        "fillHorizontal": "Remplir les cellules horizontalement",
                        "fillVertical": "Remplir les cellules verticalement",
                        "info": "Exemple de remplissage automatique"
                    },
                    "searchBuilder": {
                        "conditions": {
                            "date": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "moment": {
                                "after": "Après le",
                                "before": "Avant le",
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "number": {
                                "between": "Entre",
                                "empty": "Vide",
                                "equals": "Egal à",
                                "gt": "Supérieur à",
                                "gte": "Supérieur ou égal à",
                                "lt": "Inférieur à",
                                "lte": "Inférieur ou égal à",
                                "not": "Différent de",
                                "notBetween": "Pas entre",
                                "notEmpty": "Non vide"
                            },
                            "string": {
                                "contains": "Contient",
                                "empty": "Vide",
                                "endsWith": "Se termine par",
                                "equals": "Egal à",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "startsWith": "Commence par"
                            },
                            "array": {
                                "equals": "Egal à",
                                "empty": "Vide",
                                "contains": "Contient",
                                "not": "Différent de",
                                "notEmpty": "Non vide",
                                "without": "Sans"
                            }
                        },
                        "add": "Ajouter une condition",
                        "button": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "clearAll": "Effacer tout",
                        "condition": "Condition",
                        "data": "Donnée",
                        "deleteTitle": "Supprimer la règle de filtrage",
                        "logicAnd": "Et",
                        "logicOr": "Ou",
                        "title": {
                            "0": "Recherche avancée",
                            "_": "Recherche avancée (%d)"
                        },
                        "value": "Valeur"
                    },
                    "searchPanes": {
                        "clearMessage": "Effacer tout",
                        "count": "{total}",
                        "title": "Filtres actifs - %d",
                        "collapse": {
                            "0": "Volet de recherche",
                            "_": "Volet de recherche (%d)"
                        },
                        "countFiltered": "{shown} ({total})",
                        "emptyPanes": "Pas de volet de recherche",
                        "loadMessage": "Chargement du volet de recherche..."
                    },
                    "buttons": {
                        "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                        "collection": "Collection",
                        "colvis": "Visibilité colonnes",
                        "colvisRestore": "Rétablir visibilité",

                        "copySuccess": {
                            "1": "1 ligne copiée dans le presse-papier",
                            "_": "%ds lignes copiées dans le presse-papier"
                        },
                        "copyTitle": "Copier dans le presse-papier",
                        "csv": "CSV",
                        "excel": "Excel",
                        "pageLength": {
                            "-1": "Afficher toutes les lignes",
                            "1": "Afficher 1 ligne",
                            "_": "Afficher %d lignes"
                        },
                        "pdf": "PDF",

                    },
                    "decimal": ",",
                    "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                    "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                    "infoFiltered": "(filtrés de _MAX_ éléments au total)",
                    "infoThousands": ".",
                    "search": "Rechercher:",
                    "searchPlaceholder": "...",
                    "thousands": "."
                }
            });
        });
    </script>





    <script>
        function chart99() {
            <?php
            $php_array = $formattedData;
            $month_array = $dateData;
            ?>
            var js_array = [<?php echo '"' . implode('","', $php_array) . '"'; ?>];
            var month_array = [<?php echo '"' . implode('","', $month_array) . '"'; ?>];

            var options = {
                chart: {
                    height: 700,
                    type: 'line',
                    shadow: {
                        enabled: false,
                        color: '#bbb',
                        top: 3,
                        left: 2,
                        blur: 3,
                        opacity: 1
                    },
                },
                stroke: {
                    width: 7,
                    curve: 'smooth'
                },
                series: [{
                    name: 'Netting',
                    data: js_array
                }],
                xaxis: {
                    type: 'string',
                    categories: month_array,
                    labels: {
                        style: {
                            colors: '#03a04f',
                        }
                    }
                },
                title: {
                    text: 'Netting en Fcfa',
                    align: 'left',
                    style: {
                        fontSize: "16px",
                        color: '#ec1f28'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: ['#FDD835'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                markers: {
                    size: 4,
                    opacity: 0.9,
                    colors: ["#FFA41B"],
                    strokeColor: "#fff",
                    strokeWidth: 2,

                    hover: {
                        size: 7,
                    }
                },
                yaxis: {
                    min: 0,
                    max: 300000000,
                    title: {
                        text: 'Montant Fcfa',
                    },
                    labels: {
                        style: {
                            color: '#03a04f',
                        }
                    }
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#chart99"),
                options
            );

            chart.render();
        }

        function chart90() {
            <?php
            $php_array = $coveringData;
            $month_array = $coveringDate;
            ?>
            var js_array = [<?php echo '"' . implode('","', $php_array) . '"'; ?>];
            var month_array = [<?php echo '"' . implode('","', $month_array) . '"'; ?>];

            var options = {
                chart: {
                    height: 700,
                    type: 'line',
                    shadow: {
                        enabled: false,
                        color: '#bbb',
                        top: 3,
                        left: 2,
                        blur: 3,
                        opacity: 1
                    },
                },
                stroke: {
                    width: 7,
                    curve: 'smooth'
                },
                series: [{
                    name: 'Recouvrement',
                    data: js_array
                }],
                xaxis: {
                    type: 'string',
                    categories: month_array,
                    labels: {
                        style: {
                            colors: '#ec1f28',
                        }
                    }
                },
                title: {
                    text: 'Montant en Fcfa',
                    align: 'left',
                    style: {
                        fontSize: "16px",
                        color: '#ec1f28'
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        gradientToColors: ['#FDD835'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                markers: {
                    size: 4,
                    opacity: 0.9,
                    colors: ["#FFA41B"],
                    strokeColor: "#fff",
                    strokeWidth: 2,

                    hover: {
                        size: 7,
                    }
                },
                yaxis: {
                    min: 0,
                    max: 600000000,
                    title: {
                        text: 'Montant Fcfa',
                    },
                    labels: {
                        style: {
                            color: '#ec1f28',
                        }
                    }
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#chart90"),
                options
            );

            chart.render();
        }

        function chart33() {
            const d = new Date()
            const current_year = d.getFullYear();
            <?php
            $RevenuDataJs = $RevenuData;
            $ChargeDataJS = $ChargeData;
            $RevenuBeforDataJs = $RevenuBeforData;
            $ChargeBeforDataJs = $ChargeBeforData;
            $month_array = $rcDate;
            ?>
            var revenuData = [<?php echo '"' . implode('","', $RevenuDataJs) . '"'; ?>];
            var chargeData = [<?php echo '"' . implode('","', $ChargeDataJS) . '"'; ?>];
            var revenuBeforData = [<?php echo '"' . implode('","', $RevenuBeforDataJs) . '"'; ?>];
            var chargeBeforData = [<?php echo '"' . implode('","', $ChargeBeforDataJs) . '"'; ?>];
            var month_array = [<?php echo '"' . implode('","', $month_array) . '"'; ?>];

            var options = {
                chart: {
                    height: 700,
                    type: 'line',
                    shadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 1
                    }

                },
                colors: ['#77B6EA', '#545454', '#ec1f28', '#fcca29'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                        name: "Revenu - ".concat(current_year),
                        data: revenuData
                    },
                    {
                        name: "Revenu - ".concat(current_year - 1),
                        data: revenuBeforData
                    },
                    {
                        name: "Charge - ".concat(current_year),
                        data: chargeData
                    },
                    {
                        name: "Charge - ".concat(current_year - 1),
                        data: chargeBeforData
                    }
                ],
                title: {
                    text: 'Revenus et Charges '.concat(current_year - 1, ' vs ', current_year),
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {

                    size: 15

                },
                xaxis: {
                    categories: month_array,
                    title: {
                        text: 'Mois'
                    },
                    labels: {
                        style: {
                            colors: '#ec1f28',
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Montant en Fcfa'
                    },
                    labels: {
                        style: {
                            color: '#ec1f28',
                        }
                    },
                    min: 0,
                    max: 450000000
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#chart33"),
                options
            );

            chart.render();
        }

        function chart333() {
            const d = new Date()
            const current_year = d.getFullYear();
            <?php
            $EntrantDataJs = $EntrantData;
            $SortantDataJS = $SortantData;
            $EntrantBeforDataJs = $EntrantBeforData;
            $SortantBeforDataJs = $SortantBeforData;
            $month_array = $vDate;
            ?>
            var entrantData = [<?php echo '"' . implode('","', $EntrantDataJs) . '"'; ?>];
            var sortantData = [<?php echo '"' . implode('","', $SortantDataJS) . '"'; ?>];
            var entrantBeforData = [<?php echo '"' . implode('","', $EntrantBeforDataJs) . '"'; ?>];
            var sortantBeforData = [<?php echo '"' . implode('","', $SortantBeforDataJs) . '"'; ?>];
            var month_array = [<?php echo '"' . implode('","', $month_array) . '"'; ?>];

            var options = {
                chart: {
                    height: 700,
                    type: 'line',
                    shadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 1
                    }

                },
                colors: ['#77B6EA', '#545454', '#ec1f28', '#fcca29'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                        name: "Volume entrant - ".concat(current_year),
                        data: entrantData
                    },
                    {
                        name: "Volume entrant - ".concat(current_year - 1),
                        data: entrantBeforData
                    },
                    {
                        name: "Volume sortant - ".concat(current_year),
                        data: sortantData
                    },
                    {
                        name: "Volume sortant - ".concat(current_year - 1),
                        data: sortantBeforData
                    }
                ],
                title: {
                    text: 'Volume entrant et sortant '.concat(current_year - 1, ' vs ', current_year),
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {

                    size: 15

                },
                xaxis: {
                    categories: month_array,
                    title: {
                        text: 'Mois'
                    },
                    labels: {
                        style: {
                            colors: '#ec1f28',
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Volume'
                    },
                    labels: {
                        style: {
                            color: '#ec1f28',
                        }
                    },
                    min: 0,
                    max: 4500000
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            }

            var chart = new ApexCharts(
                document.querySelector("#chart333"),
                options
            );

            chart.render();
        }
    </script>




    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <!-- Chart code -->
    <script>
        am5.ready(function() {

            // Create root element
            // https://www.amcharts.com/docs/v5/getting-started/#Root_element
            var root = am5.Root.new("chartdiv");
            var root2 = am5.Root.new("chartdiv2");


            // Set themes
            // https://www.amcharts.com/docs/v5/concepts/themes/
            root.setThemes([
                am5themes_Animated.new(root)
            ]);
            root2.setThemes([
                am5themes_Animated.new(root2)
            ]);

            // Create chart
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout
            }));

            var chart2 = root2.container.children.push(am5percent.PieChart.new(root2, {
                layout: root2.verticalLayout
            }));

            // Create series
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category"
            }));

            var series2 = chart2.series.push(am5percent.PieSeries.new(root2, {
                valueField: "value",
                categoryField: "category"
            }));

            // Set data
            // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
            series.data.setAll([
                <?php echo $horsAfrqData; ?>

            ]);

            series2.data.setAll([
                <?php echo $inAfrqData; ?>

            ]);


            // Play initial series animation
            // https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
            series.appear(1000, 100);
            series2.appear(1000, 100);

        }); // end am5.ready()
    </script>




@stop
