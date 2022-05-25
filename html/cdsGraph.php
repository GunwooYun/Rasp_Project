<?php
	$conn = mysqli_connect("localhost", "root", "kcci");
	mysqli_set_charset($conn, "utf8");
	mysqli_select_db($conn, "homeDB");
	$result = mysqli_query($conn, "select DATE, TIME, CDS from room_state");
	$flame_result = mysqli_query($conn, "SELECT FLAME FROM room_state ORDER BY ID DESC LIMIT 1");
	$flame = mysqli_fetch_array($flame_result);
	$aaa = 1;

	$data = array(array('te', '조도량'));
	if($result){
		while($row=mysqli_fetch_array($result)){
			array_push($data, array($row['DATE']."\n".$row[1], intval($row[2])));
		}
	}
	$options = array('title'=>'조도량 (단위 : %)', 'width'=>1000, 'height'=>500);
?>

<meta http-equiv="refresh" content = "1">
<script src="//www.google.com/jsapi"></script>
<script>
    let data = <?= json_encode($data) ?>;
    let options = <?= json_encode($options) ?>;
    google.load('visualization', '1.0', {'packages':['corechart']});
    google.setOnLoadCallback(function() {
    let chart = new google.visualization.AreaChart(document.querySelector('#chart_div'));
    chart.draw(google.visualization.arrayToDataTable(data), options);
    });
</script>

<?php
	if($flame['FLAME']){
		$bgColo = "red";
	}
	else{
		$bgColo = "white";
	}
	print("<body bgcolor=\"$bgColo\">\n");
?>
<div id="chart_div"></div>
