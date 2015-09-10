<?php



		
$gallery_id = "benchmark";

$server = "http://h2467741.stratoserver.net/benchmark/recognize/";

$servername = "localhost";
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

$sql = "select * from recognize_results";
		$myresult = $conn->query($sql);


while($row = $myresult->fetch_assoc()) {		

$image_path = $server.$row['image'];

$time_start = microtime(true);

$ch = curl_init();

$data = array('api_key' => nameface_ApiKey, 
				              'api_secret' => nameface_ApiSecret, 
				                 'jobs' => 'face_search_aggressive',
				              
				                  'urls' => $image_path,
				                  'name_space' => nameface_NameSpace,
				                  'user_id' => nameface_UserID);
				curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$result = curl_exec($ch);

			


$array_result = json_decode($result, true);
$orbeus_result = 0;

 #print_r($array_result['face_detection']);
// print_r("-------------------");

$time_end = microtime(true);
$howlong = $time_end - $time_start;

$end = 0;

for ($i=0; $i < count($array_result['face_detection']); $i++) { 


	$matches = $array_result['face_detection'][$i]['matches'];
	
	#print_r($matches);

	for ($k=0; $k < count($matches); $k++) { 
		# code...

		if($end == 0) {
			
			if($matches[$k]['tag'] == 'p'.$row['personID']) {
						$orbeus_result =$matches[$k]['score'];
						$end = 1;
			}
		}

	}


}



$sql = "update recognize_results set orbeus = ".$orbeus_result.", orbeus_time = ".$howlong." where ID = ".$row['ID'];
			
			$conn->query($sql);



}



?>