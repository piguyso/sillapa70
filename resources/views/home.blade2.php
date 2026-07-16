@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">
            @if(Session::has('massage'))
              <div class="alert alert-success">{{Session::get('massage')}}</div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-Response">
                      <thead>
                        <tr>
                          <th>ข้อมูลการจัดซื้อจัดจ้าง</th>

                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                              <div id="chart1" align="left"></div>
                          </td>
                          <td>
                              <div id="chart2" align="left"></div>
                          </td>
                        <tr>
                          <tr>

                          <tr>
                      </tbody>
                    </table>





                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script = "java script">

    var analytics = <?php //echo $data ?>;
    google.charts.load('current',{packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart1);
    google.charts.setOnLoadCallback(drawChart2);
    function drawChart1() {
        // Create the data table.
        var data = google.visualization.arrayToDataTable(analytics);
              // Set chart options
        var options = {
                    //  'title':'เปรียบเทียบข้อมูลรายการจัดซื้อ/จัดจ้าง',
                      'width':500,
                      'height':500,
                      'pieHole':0.3,
                      'is3D':true,
                    };
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart1'));
        chart.draw(data, options);
        }

        function drawChart2() {
            // Create the data table.
            var data = google.visualization.arrayToDataTable(analytics);
                  // Set chart options
            var options = {
                          //'title':'เปรียบเทียบข้อมูลรายการจัดซื้อ/จัดจ้าง',
                          'width':500,
                          'height':500,
                          'pieHole':0.3,
                          'is3D':true,
                        };
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart2'));
            chart.draw(data, options);
            }
</script>
