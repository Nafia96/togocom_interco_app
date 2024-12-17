@extends('template.principal_tamplate4')

@section('title', 'Roaming')

@section('content')
<div class="row">
    <div class="col-12 col-sm-12 col-lg-12">
      <div class="card ">
        <div class="card-header">
          <h4>SS7 ROAMING TRAFFIC TREND</h4>
          <div class="card-header-action">
            <div class="dropdown">
              <a href="#" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Options</a>
              <div class="dropdown-menu">
                <a href="#" class="dropdown-item has-icon"><i class="fas fa-eye"></i> View</a>
                <a href="#" class="dropdown-item has-icon"><i class="far fa-edit"></i> Edit</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item has-icon text-danger"><i class="far fa-trash-alt"></i>
                  Delete</a>
              </div>
            </div>
            <a href="#" class="btn btn-primary">View All</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-9">
              <div id="chart333"></div>
              <div class="row mb-0">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                        class="col-green"></i>
                      <h5 class="m-b-0">{{ $daily_trend_incoming_MAX }}</h5>
                      <p class="text-muted font-14 m-b-0">Duree maximum</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-down-circle"
                        class="col-orange"></i>
                      <h5 class="m-b-0">{{ ($daily_trend_incoming_MAX - $daily_trend_incoming_MIN)/$daily_trend_incoming_MIN }}</h5>
                      <p class="text-muted font-14 m-b-0">Average Time</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <div class="list-inline text-center">
                    <div class="list-inline-item p-r-30"><i data-feather="arrow-up-circle"
                        class="col-green"></i>
                      <h5 class="mb-0 m-b-0">{{ $daily_trend_incoming_MIN }}</h5>
                      <p class="text-muted font-14 m-b-0">Minimum duration</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="row mt-5">
                <div class="col-7 col-xl-7 mb-3">Total customers</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">8,257</span>
                  <sup class="col-green">+09%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Total Income</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">$9,857</span>
                  <sup class="text-danger">-18%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Project completed</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">28</span>
                  <sup class="col-green">+16%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">Total expense</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">$6,287</span>
                  <sup class="col-green">+09%</sup>
                </div>
                <div class="col-7 col-xl-7 mb-3">New Customers</div>
                <div class="col-5 col-xl-5 mb-3">
                  <span class="text-big">684</span>
                  <sup class="col-green">+22%</sup>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @php
  $EntrantData = $daily_trend_incoming->map(fn($data) => (int) $data->duration_min)->toArray();
  $SortantData = $daily_trend_outgoing->map(fn($data) => (int) $data->duration_min)->toArray();
  $vDate = $daily_trend_outgoing->map(fn($data) => (int) $data->start_hour)->toArray();
  $trendDates = $daily_trend_incoming->map(fn($data) => $data->start_date)->toArray();
@endphp


    </section>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', chart333);

    function chart333() {
        const currentYear = new Date().getFullYear();
        const entrantData = @json($EntrantData);
        const sortantData = @json($SortantData);
        const monthArray = @json($vDate);
        const trendDates = @json($trendDates);

        // Récupérer la première et la dernière date
        const startDate = trendDates.length > 0 ? trendDates[0] : "N/A";
        const endDate = trendDates.length > 0 ? trendDates[trendDates.length - 1] : "N/A";

        const options = {
            chart: {
                height: 300,
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
            colors: ['#77B6EA', '#545454'],
            dataLabels: {
                enabled: true
            },
            stroke: {
                curve: 'smooth'
            },
            series: [
                {
                    name: `Incoming - ${currentYear} (${startDate} à ${endDate})`,
                    data: entrantData
                },
                {
                    name: `Outgoing - ${currentYear} (${startDate} à ${endDate})`,
                    data: sortantData
                }
            ],
            title: {
                text: `Daily trend`,
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                }
            },
            markers: {
                size: 5
            },
            xaxis: {
                categories: monthArray,
                title: {
                    text: 'Heures'
                },
                labels: {
                    style: {
                        colors: '#ec1f28'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Volume'
                },
                labels: {
                    style: {
                        color: '#ec1f28'
                    }
                },
                min: 0,
                max: 10000
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                floating: true,
                offsetY: -5,
                offsetX: -5
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart333"), options);
        chart.render();
    }
</script>

@endsection
