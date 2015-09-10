<?php

//* * * * include the wrapper class
include('kairos.php');





$Kairos  = new Kairos();

$servername = "localhost";
$username = "";
$password = "";
		
$conn = mysqli_connect($servername, $username, $password, "benchmarknf");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



$sql = "select * from nameface_image WHERE kairos = 0";
		$myresult = $conn->query($sql);

		$server = "http://h2467741.stratoserver.net/benchmark/trainimages/";
	
		

		while($row = $myresult->fetch_assoc()) {

			 $url = $server."person".$row['person_ID']."/".$row['image'];

			 #$url = 'http://cdni.condenast.co.uk/720x1080/g_j/gwyneth-paltrow_glamour_24mar14_pa_b_720x1080.jpg';
			 $personname = 'p'.$row['person_ID'];
			 $rowid = $row['ID'];

			 $gallery_id = 'benchmarkv2';
				$subject_id = $personname;
				$image_path = $url;
				$response = $Kairos->enrollImageWithPath($image_path, $gallery_id, $subject_id);

				$array_result = json_decode($response, true);

				#print_r($array_result);

				$number_of_faces = 0;

				$faces_correct = 1;

				if(isset($array_result['Errors'])) {
					#print_r('no faces');

					$number_of_faces = 0;
				}

				if(isset($array_result['images'])) {
					#print_r(count($array_result['images']));
					$number_of_faces = count($array_result['images']);
				}



			if(($number_of_faces == $row['faces']) || ($number_of_faces > 1 && $number_of_faces > $row['faces'])) {
				$faces_correct = 2;
			}

			$sql = "update nameface_image set kairos = 1, kairos_faces_correct = ".$faces_correct.", kairos_faces_processed = 1 where ID = ".$row['ID'];
			
			$conn->query($sql);



		} 







?>