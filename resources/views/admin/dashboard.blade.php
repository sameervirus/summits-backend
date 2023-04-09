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
              <div class="chart">
                <canvas id="sales-chart" style="height:250px"></canvas>
              </div>
            </div>
            <!-- /.x_content -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="dashboard_graph x_panel">
            <div class="x_title with-border">
              <h3 class="box-title">Visitor Devices</h3>


            </div>
            <div class="x_content">
              <canvas id="pieChart" style="height:250px"></canvas>
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
              <h3 class="box-title">Country</h3>


            </div>
            <div class="x_content">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
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
		$(function () {
  'use strict'

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
      labels  : [
        @foreach($salesReport['labels'] as $month)
          "{{$month}}",
        @endforeach
      ],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : [
            @foreach($salesReport['currentYearSales'] as $currentYearSales)
              {{$currentYearSales}},
            @endforeach
          ]
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : [
            @foreach($salesReport['previousYearSales'] as $previousYearSales)
              {{$previousYearSales}},
            @endforeach
          ]
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

  var $visitorsChart = $('#visitors-chart')
  var visitorsChart  = new Chart($visitorsChart, {
    data   : {
      labels  : ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
      datasets: [{
        type                : 'line',
        data                : [100, 120, 170, 167, 180, 177, 160],
        backgroundColor     : 'transparent',
        borderColor         : '#007bff',
        pointBorderColor    : '#007bff',
        pointBackgroundColor: '#007bff',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          type                : 'line',
          data                : [60, 80, 70, 67, 80, 77, 100],
          backgroundColor     : 'tansparent',
          borderColor         : '#ced4da',
          pointBorderColor    : '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
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
            beginAtZero : true,
            suggestedMax: 200
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
})
	</script>
@endsection
