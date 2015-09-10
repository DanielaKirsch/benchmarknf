<?php

$servername = "localhost";
$username = "";
$password = "";

		
$conn = mysqli_connect($servername, $username, $password, "benchmarknf");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// orbeus_faces_correct == 2 means correct!!

define('nameface_NameSpace', 'nameface');
define('nameface_UserID', 'demo');


define('nameface_ApiKey', '');
define('nameface_ApiSecret', '');



		$sql = "select * from nameface_image WHERE orbeus_faces_processed = 0";
		$myresult = $conn->query($sql);

		
		$server = "http://h2467741.stratoserver.net/benchmark/trainimages/";
	

		while($row = $myresult->fetch_assoc()) {

				$ch = curl_init();

				$url = $server."person".$row['person_ID']."/".$row['image'];

			
				

				$data = array('api_key' => nameface_ApiKey, 
			              'api_secret' => nameface_ApiSecret, 
			                 # 'job_list' => 'face_add_['.$name.']',
			              'job_list' => 'face_add',
			                  'urls' => $url,
			                  'name_space' => nameface_NameSpace,
			                  'user_id' => nameface_UserID);
			curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result = curl_exec($ch);


			curl_close($ch);




			$array_result = json_decode($result, true);


			$number_of_faces = count($array_result['face_detection']);

			$faces_correct = 1;

	if(($number_of_faces == $row['faces']) || ($number_of_faces > 1 && $number_of_faces > $row['faces'])) {
				$faces_correct = 2;
			}

			$sql = "update nameface_image set orbeus_faces_correct = ".$faces_correct.", orbeus_faces_processed = 1 where ID = ".$row['ID'];
			
			$conn->query($sql);



				

		}		
?>