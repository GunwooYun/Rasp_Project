<!DOCTYPE html>
<html>
<head>
	<meta charset = "UTF-8">
	<meta http-equiv="refresh" content = "1">
	<style type = "text/css">
		.spec{
			text-align:center;
		}
		.con{
			text-align:left;
		}
		</style>
</head>

<body>
	<h1 align = "center">My Database</h1>
	<div class = "spec">
		# <b>The sensor value description</b><br>
		# 1 ~ 99 humi <br>
		# 1 ~ 99 temp <br>
		# 0 | 1 flame <br>
		# 0 ~ 100 cds <br>
	</div>

	<table border = '1' style = "width = 30%" align = "center">
	<tr align = "center">
		<th>ID</th>
		<th>DATE</th>
		<th>TIME</th>
		<th>TEMPERATURE</th>
		<th>MOISTURE</th>
		<th>FLAME</th>
		<th>CDS</th>
	</tr>
	<?php
		$conn = mysqli_connect("localhost", "root", "kcci");
		mysqli_select_db($conn, "homeDB");
		$result = mysqli_query($conn, "select * from room_state order by ID desc limit 5");

		while($row = mysqli_fetch_array($result)){
			echo "<tr align = center>";
			echo '<td>'.$row['ID'].'</td>';
			echo '<td>'.$row['DATE'].'</td>';
			echo '<td>'.$row['TIME'].'</td>';
			echo '<td>'.$row['TEMP'].'</td>';
			echo '<td>'.$row['HUMI'].'</td>';
			echo '<td>'.$row['FLAME'].'</td>';
			echo '<td>'.$row['CDS'].'</td>';
			echo "</tr>";

			mysqli_close($conn);
		}
	?>
	</table>
</body>

</html>
