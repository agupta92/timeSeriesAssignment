<?php
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
include('staticContent.php');

// if(!isset($_GET)){
//    $message="Argument Not received";
//    returnCustomError($message);
// }
// $solar_device_id = $_GET['solar_id'];
// $input_date = $_GET['date'];
var_dump(date("d-m-Y", strtotime("01-02-2017")));
echo $startTime = strtotime(date("d-m-Y", strtotime("01-01-2017")));
echo " ";
echo $dateForSample = $startTime;
echo " ";
echo $dateStartFrom = strtotime(date('Y-01-01', $startTime));
echo " ";
echo $dateDiff = ($dateStartFrom - $dateForSample);
//echo $dateDiff;
echo " ";
echo $dateDiffHours = abs($dateDiff/3600);
$defaulted_Device = array();
for($i=0; $i<24 ; $i++){
	$minPowerProduced = $mumbai[$dateDiffHours+$i];
	$timestamp = $dateForSample + ((60*60)*($i+1));
	echo "Hour(i)= ".$i. " Power=". $minPowerProduced . " timestamp=". $timestamp."\n";
	$generatedPower = getPowerConsumed(1,$timestamp);
	if(!$generatePower){
		continue;
	}
	$output_produced_percent = (($generatedPower/$minPowerProduced)*100);
	if( $output_produced_percent < 80){
		$defaulted_Device[] = array('time'=> $timestamp, 'minOutput' => $minPowerProduced, 'actualOutput'=> $generatedPower, 'percentDiff'=>$output_produced_percent);
	}
}
if(count($defaulted_Device) > 0 ){
	$message = "Successful";
	returnSuccess($message,$defaulted_Device);
} else {
	$message = "Device generated Out above threshold";
	returnSuccess($message);
}

function getPowerConsumed($deviceId = '1', $timestamp = 1485950400, $url ='http://localhost:8086/query?'){
	$query = http_build_query([
         'db' => 'oorjan',
         'q' => "SELECT * FROM solar_device_performance where deviceId='".$deviceId."' AND time=". $timestamp
        ]);
	$url = $url . $query;
    $curl = curl_init();
	curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => -1,
    CURLOPT_TIMEOUT => 300,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array("cache-control: no-cache","content-type: text/plain"),
    ));
    $result = curl_exec($curl);
    $result = json_decode($result);
    $error = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
   var_dump($result);
	 if($httpCode == 200 ){
        if(isset($result->results[0]->series)){
		return $result->results[0]->series[0]->values[0][3];
	}else {
		return false;
	    }
	}
}

function returnSuccess($message, $data=array(), $meta = null) {
    $result = json_encode(array( "m" => $message, "s" => 1,"meta" => $meta, "d" => $data));
    returnJSON($result);
}

function returnCustomError($message) {
    $result = json_encode(array(  "m" => $message, "s" => 0, "d" => array()));
    returnJSON($result);
}

function returnJSON($result){
    header("Content-type: application/json");
    echo $result;
    exit;
}
?>
