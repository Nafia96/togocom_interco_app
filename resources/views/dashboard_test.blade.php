@extends('template.principal_tamplate')

@section('title', 'Accueil')

@section('content')
<section class="section">
    <div class="section-body">
      <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Bar CHart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart1"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Bar Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart2"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Line Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart3"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Line Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart8"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Line &amp; Column Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart5"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Aria Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart6"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Pie Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart7"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
          <div class="card">
            <div class="card-header">
              <h4>Radar Chart</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart99"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


@endsection

@section('script')

<script>
function chart99() {
  var options = {
      chart: {
          height: 350,
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
          name: 'Likes',
          data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5]
      }],
      xaxis: {
          type: 'datetime',
          categories: ['1/11/2000', '2/11/2000', '3/11/2000', '4/11/2000', '5/11/2000', '6/11/2000', '7/11/2000', '8/11/2000', '9/11/2000', '10/11/2000', '11/11/2000', '12/11/2000', '1/11/2001', '2/11/2001', '3/11/2001', '4/11/2001', '5/11/2001', '6/11/2001'],
          labels: {
              style: {
                  colors: '#9aa0ac',
              }
          }
      },
      title: {
          text: 'Social Media',
          align: 'left',
          style: {
              fontSize: "16px",
              color: '#666'
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
          min: -10,
          max: 40,
          title: {
              text: 'Engagement',
          },
          labels: {
              style: {
                  color: '#9aa0ac',
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
</script>

@stop
