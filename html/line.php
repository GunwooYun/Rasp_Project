<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

	  var data = new google.visualization.DataTable();
	  data.addColumn('string', 'year');
	  data.addColumn('number', 'moisture');
	  data.addColumn('number', 'temperature');

	  data.addRows([
			  ['2022', 21, 33],
			  ['2022', 23, 35],
			  ['2022', 19, 35]
	  ]);


        var options = {
          title: 'temp| humi | light',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>
