<?php

$$servername = "localhost";
$username = "";
$password = "";

		
$conn = mysqli_connect($servername, $username, $password, "benchmarknf");
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



define('nameface_NameSpace', 'benchmark');
define('nameface_UserID', 'benchmark');

define('nameface_ApiKey', '');
define('nameface_ApiSecret', '');



		$sql = "select * from nameface_image WHERE orbeus = 0 AND faces = 1";
		$myresult = $conn->query($sql);

		$server = "http://h2467741.stratoserver.net/benchmark/trainimages/";
	

		while($row = $myresult->fetch_assoc()) {

				$ch = curl_init();

				$url = $server."person".$row['person_ID']."/".$row['image'];

				$personname = 'p'.$row['person_ID'];
				

				$data = array('api_key' => nameface_ApiKey, 
				              'api_secret' => nameface_ApiSecret, 
				                 'job_list' => 'face_add_['.$personname.']',
				              
				                  'urls' => $url,
				                  'name_space' => nameface_NameSpace,
				                  'user_id' => nameface_UserID);
				curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$result = curl_exec($ch);

				$mydata = json_decode($result, true);

				if($mydata['usage']['status'] == 'Succeed.') {

					$sql = "update nameface_image set orbeus= 1 Where ID = ".$row['ID'];
		 			
					$conn->query($sql);

				

					$ch = curl_init();
					

					$data = array('api_key' => nameface_ApiKey, 
					              'api_secret' => nameface_ApiSecret, 
					                 'jobs' => 'face_train_sync',
					                  'name_space' => nameface_NameSpace,
					                  'user_id' => nameface_UserID,
					                  'tags' => $personname);
					curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					$result1 = curl_exec($ch);

				}

				

		}		
?>