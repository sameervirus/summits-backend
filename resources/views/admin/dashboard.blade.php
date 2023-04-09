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
                <a class="nav-link active" data-toggle="tab" href="#monthlySales">Monthly</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#weeklySales">Weekly</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#dailySales">Daily</a>
              </li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="monthlySales">
                <canvas id="monthlySalesChart"></canvas>
              </div>
              <div class="tab-pane" id="weeklySales">
                <canvas id="weeklySalesChart"></canvas>
              </div>
              <div class="tab-pane" id="dailySales">
                <canvas id="dailySalesChart"></canvas>
              </div>
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
@endsection
