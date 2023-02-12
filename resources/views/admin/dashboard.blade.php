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
              <h3 class="box-title">Visitor and Page View</h3>


            </div>
            <div class="x_content">
              <div class="chart">
                <canvas id="areaChart" style="height:250px"></canvas>
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

@endsection
