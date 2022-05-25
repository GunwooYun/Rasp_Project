<?php
    $conn = mysqli_connect("localhost", "root", "kcci");
    mysqli_set_charset($conn, "utf8");
    mysqli_select_db($conn, "homeDB");
    $result = mysqli_query($conn, "select DATE, TIME, CDS from room_state");
    $flame_result = mysqli_query($conn, "SELECT FLAME FROM room_state ORDER BY ID DESC LIMIT 1");
    $flame = mysqli_fetch_array($flame_result);
    $humiTemp_result = mysqli_query($conn, "SELECT HUMI, TEMP FROM room_state ORDER BY ID DESC LIMIT 1");
    $humi_temp = mysqli_fetch_array($humiTemp_result);
	$humi = $humi_temp['HUMI'];
	$temp = $humi_temp['TEMP'];

	
    $aaa = 1;

    $data = array(array('te', 'CDS'));
    if($result){
        while($row=mysqli_fetch_array($result)){
            array_push($data, array($row['DATE']."\n".$row[1], intval($row[2])));
        }
    }
    $options = array('title'=>'온도(단위:섭씨)', 'width'=>1000, 'height'=>500);
?>

<html>
  <head>
	<meta http-equiv="refresh" content = "1">
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

	  var humi_val = <?=$humi?>;
	  var temp_val = <?=$temp?>;

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Humi', humi_val],
          ['Temp', temp_val]
        ]);

        var options = {
          width: 800, height: 240,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, humi_val);
          //data.setValue(0, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 1000);

        setInterval(function() {
          data.setValue(1, 1, temp_val);
          //data.setValue(1, 1, 40 + Math.round(60 * Math.random()));
          chart.draw(data, options);
        }, 1000);
		
      }
</script>
</head>
  <body>
  <?php
    if($flame['FLAME']){
        $bgColo = "red";
    }
    else{
        $bgColo = "white";
    }
    print("<body bgcolor=\"$bgColo\">\n");
?>

    <div id="chart_div" style="width: 400px; height: 120px;"></div>

  </body>
</html>
