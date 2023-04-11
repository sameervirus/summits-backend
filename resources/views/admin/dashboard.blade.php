@extends('admin.admin')

@section('title', 'Dashboard')

@section('cssFiles')


@endsection

@section('content')
<style type="text/css">
    .chart canvas {
        position: relative;
    }
    .chart {
        width: 100%;
        height: auto;
    }
</style>
<div class="right_col" role="main">
     <div class="row">
        <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="dashboard_graph x_panel">
            <div class="x_title with-border">
              <h3 class="box-title">Sales by Time Period</h3>
            </div>
            <div class="x_content">
            <ul class="nav nav-tabs" id="salesTabs">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#dailySales">Daily</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#monthlySales">Monthly</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#weeklySales">Weekly</a>
              </li>
            </ul>

            <div class="tab-content">
            <div class="tab-pane active" id="dailySales">
                <canvas id="dailySalesChart" style="max-height:250px"></canvas>
              </div>
              <div class="tab-pane" id="monthlySales">
                <canvas id="monthlySalesChart" style="max-height:250px"></canvas>
              </div>
              <div class="tab-pane" id="weeklySales">
                <canvas id="weeklySalesChart" style="max-height:250px"></canvas>
              </div>
              
            </div>
            </div>
            <!-- /.x_content -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="dashboard_graph x_panel">
            <div class="x_title with-border">
              <h3 class="box-title">Sales</h3>


            </div>
            <div class="x_content">
              <canvas id="product-chart" style="height:250px"></canvas>
            </div>
            <!-- /.x_content -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="dashboard_graph x_panel">
            <div class="x_title with-border">
              <h3 class="box-title">Sales</h3>


            </div>
            <div class="x_content">
              <div class="chart">
                <canvas id="sales-chart" style="height:278px"></canvas>
              </div>
            </div>
            <!-- /.x_content -->
          </div>
          <!-- /.box -->

          <!-- BAR CHART -->
          <div class="dashboard_graph x_panel">
            <div class="x_title with-border">
              <h3 class="box-title">Visitor and Page View</h3>


            </div>
            <div class="x_content">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
            <!-- /.x_content -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->
</div>
@endsection

@section('jsFiles')

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script>
		const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    const weeklySalesCtx = document.getElementById('weeklySalesChart').getContext('2d');
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');

    const monthData = {!! json_encode($salesReport['monthlySales']) !!};
    const labels = monthData.map(item => item.month);
    const salesData = monthData.map(item => item.sales);

    const weekData = {!! json_encode($salesReport['weeklySales']) !!};
    const weeklabels = weekData.map(item => item.week);
    const weeksalesData = weekData.map(item => item.sales);

    const dailySales = {!! json_encode($salesReport['dailySales']) !!};
    const dailylabels = dailySales.map(item => item.day);
    const dailysalesData = dailySales.map(item => item.sales);

    // Display monthly sales data in a bar chart using Chart.js
    const monthlySalesChart = new Chart(monthlySalesCtx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Sales',
          data: salesData,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const weeklySalesChart = new Chart(weeklySalesCtx, {
      type: 'bar',
      data: {
        labels: weeklabels,
        datasets: [{
          label: 'Sales',
          data: weeksalesData,
          backgroundColor: 'rgba(154, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const dailySalesChart = new Chart(dailySalesCtx, {
      type: 'bar',
      data: {
        labels: dailylabels,
        datasets: [{
          label: 'Sales',
          data: dailysalesData,
          backgroundColor: 'rgba(154, 162, 35, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
      
	</script>

  <script>
    var ticksStyle = {
      fontColor: '#495057',
      fontStyle: 'bold'
    }

    var mode      = 'index'
    var intersect = true

    var $salesChart = $('#sales-chart')
    var salesChart  = new Chart($salesChart, {
      type   : 'bar',
      data   : {
        labels  : {!! json_encode($compareMonths['labels']) !!},
        datasets: [
          {
            label: 'This Year',
            backgroundColor: '#007bff',
            borderColor    : '#007bff',
            data           : {!! json_encode($compareMonths['thisYear']) !!}
          },
          {
            label: 'Last Year',
            backgroundColor: '#ced4da',
            borderColor    : '#ced4da',
            data           : {!! json_encode($compareMonths['lastYear']) !!}
          }
        ]
      },
      options: {
        maintainAspectRatio: false,
        tooltips           : {
          mode     : mode,
          intersect: intersect
        },
        hover              : {
          mode     : mode,
          intersect: intersect
        },
        legend             : {
          display: false
        },
        scales             : {
          yAxes: [{
            // display: false,
            gridLines: {
              display      : true,
              lineWidth    : '4px',
              color        : 'rgba(0, 0, 0, .2)',
              zeroLineColor: 'transparent'
            },
            ticks    : $.extend({
              beginAtZero: true,

              // Include a dollar sign in the ticks
              callback: function (value, index, values) {
                if (value >= 1000) {
                  value /= 1000
                  value += 'k'
                }
                return '$' + value
              }
            }, ticksStyle)
          }],
          xAxes: [{
            display  : true,
            gridLines: {
              display: false
            },
            ticks    : ticksStyle
          }]
        }
      }
    })
  </script>
@endsection
