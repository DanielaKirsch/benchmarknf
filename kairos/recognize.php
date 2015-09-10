<?php

//* * * * include the wrapper class
include('kairos.php');

$Kairos  = new Kairos();

		
$gallery_id = "benchmarkv2";

$server = "http://h2467741.stratoserver.net/benchmark/recognize/";


$servername = "localhost";
$username = "";
$password = "";

		
$conn = mysqli_connect($servername, $username, $password, "benchmarknf");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



$sql = "select * from recognize_results";
		$myresult = $conn->query($sql);


while($row = $myresult->fetch_assoc()) {		

$image_path = $server.$row['image'];

$time_start = microtime(true);

$response = $Kairos->recognizeImageWithPath($image_path, $gallery_id);

$array_result = json_decode($response, true);
$kairos_result = 0;

// print_r($array_result);
// print_r("-------------------");

$time_end = microtime(true);
$howlong = $time_end - $time_start;

if(isset($array_result['images'][0]['transaction']['status'])) {
	#print_r($array_result['images'][0]['transaction']['status']);

	if($array_result['images'][0]['transaction']['status'] == 'failure') {
		$kairos_result = 0;
	}

	else {

		#print_r($array_result);

		$kairos_result = 0;
		$end = 0;

		$candidates = $array_result['images'][0]['candidates'];

		for ($i=0; $i < count($candidates); $i++) { 
			
			if($end == 0) {
				foreach ($candidates[$i] as $key => $value) {
					
					if($key == 'p'.$row['personID']) {
						$kairos_result = $value;
						$end = 1;
					}

				}
			}


		}

	}
}


$sql = "update recognize_results set kairos = ".$kairos_result.", kairos_time = ".$howlong." where ID = ".$row['ID'];
			
			$conn->query($sql);



}



?>