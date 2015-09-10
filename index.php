<html>
<head>
	<title>Results</title>

	<style type="text/css">

	table, th, td {
   border: 1px solid black;
   font-size: 18px;
	}

	p   {
		font-size: 18px;
	}

	img {
		max-width: 300px;
	}

	</style>
</head>
<body>

	<?php

	$servername = "localhost";
$username = "";
$password = "";

			
	$conn = mysqli_connect($servername, $username, $password, "benchmarknf");
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT COUNT( * ) as num FROM  nameface_image";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$totalimages = $row['num'];
	}

	$sql = "SELECT COUNT( * ) as num FROM nameface_image where orbeus_faces_correct =2";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$orbeus_correct = $row['num'];
	}

	$sql = "SELECT COUNT( * ) as num FROM nameface_image where kairos_faces_correct =2";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$kairos_correct = $row['num'];
	}



	?>


	<h1>Benchmark Results</h1>

	<h2>1. Found Faces</h2>

	<p>How many faces are in the photo?</p>



	<table>
		<tr>
			<th></th>
			<th>Orbeus</th>
			<th>Kairos</th>
		</tr>

		<tr>
			<td>Correct number of faces</td>
			<td><?php echo $orbeus_correct; ?></td>
			<td><?php echo $kairos_correct; ?></td>

		</tr>

		<tr>
			<td>Incorrect number of faces</td>
			<td><?php echo $totalimages-$orbeus_correct; ?></td>
			<td><?php echo $totalimages-$kairos_correct; ?></td>

		</tr>

		<tr>
			<td>Percentage Correct</td>
			<td><?php echo 100/$totalimages*$orbeus_correct; ?></td>
			<td><?php echo 100/$totalimages*$kairos_correct; ?></td>

		</tr>

	</table>


	<h2>2. Recognition</h2>

	<p>How many faces it recognized.</p>

	<?php


	$sql = "SELECT COUNT( * ) as num FROM recognize_results";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$totaltests = $row['num'];
	}

	$sql = "SELECT COUNT( * ) as num FROM recognize_results where orbeus > 0";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$orbeus_recognized = $row['num'];
	}


	$sql = "SELECT COUNT( * ) as num FROM recognize_results where kairos > 0";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$kairos_recognized = $row['num'];
	}



	?>

	<table>
		<tr>
			<th></th>
			<th>Orbeus</th>
			<th>Kairos</th>
		</tr>

		<tr>
			<td>Correctly recognized</td>
			<td><?php echo $orbeus_recognized; ?></td>
			<td><?php echo $kairos_recognized; ?></td>

		</tr>

		<tr>
			<td>Incorrectly recognized</td>
			<td><?php echo $totaltests-$orbeus_recognized; ?></td>
			<td><?php echo $totaltests-$kairos_recognized; ?></td>

		</tr>

		<tr>
			<td>Percentage Correct</td>
			<td><?php echo 100/$totaltests*$orbeus_recognized; ?></td>
			<td><?php echo 100/$totaltests*$kairos_recognized; ?></td>

		</tr>


	</table>





	<h2>3. Speed</h2>

	<p>Total response time facial recognition</p>


	<?php



	$sql = "SELECT sum( orbeus_time ) as num FROM recognize_results";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$orbeus_sum = $row['num'];
	}

	$sql = "SELECT sum( kairos_time ) as num FROM recognize_results";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$kairos_sum = $row['num'];
	}

	?>

	<table>
		<tr>
			<th></th>
			<th>Orbeus</th>
			<th>Kairos</th>
		</tr>

		<tr>
			<td>Total time (sum)</td>
			<td><?php echo $orbeus_sum; ?></td>
			<td><?php echo $kairos_sum; ?></td>

		</tr>

		<tr>
			<td>Average response time</td>
			<td><?php echo $orbeus_sum / $totaltests; ?></td>
			<td><?php echo $kairos_sum / $totaltests; ?></td>

		</tr>
	</table>


	<h2>4. Detailed list of recognition results</h2>

	<p>0 means it did not recognize the person in the photo correctly.</p>
	<p>Everything >0 is the percentage of how confident the system is.</p>

	<table>
		<tr>
			<th></th>
			<th>Orbeus</th>
			<th>Kairos</th>
		</tr>

		<?php



	$sql = "SELECT * FROM recognize_results";
	$myresult = $conn->query($sql);
	while($row = $myresult->fetch_assoc()) {
		$orbeus_sum = $row['num'];

		echo "<tr>";

		echo "<td><img src='http://h2467741.stratoserver.net/benchmark/recognize/".$row['image']."'></td>";
		echo "<td>".$row['orbeus']."</td>";
		echo "<td>".$row['kairos']."</td>";

		echo "</tr>";
	}

	?>


	</table>


</body>
</html>