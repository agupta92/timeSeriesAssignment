<?php
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
include('staticContent.php');
include('config.php');

if(!isset($_GET)){
   $message="Argument Not received";
   returnCustomError($message);
}
$solar_device_id = $_GET['solar_id'];
$input_date = $_GET['date'];

$sqlGetUserCity = "select user_city from user_records where user_public_id =". $solar_device_id;
$link = dbConnection();
if ($result = mysqli_query($link, $sqlGetUserCity)) {
    $final_result = mysqli_fetch_all($result,MYSQLI_ASSOC);
	/* free result set */
    mysqli_free_result($final_result);
}
$user_city = $final_result[0]['user_city'];
$startTime = strtotime(date("d-m-Y", strtotime($input_date)));
$dateForSample = $startTime;
$dateStartFrom = strtotime(date('Y-01-01', $startTime));
$dateDiff = ($dateStartFrom - $dateForSample);
$dateDiffHours = abs($dateDiff/3600);
$sqlGetRequiredPower = "select datetime_for,power_generated from solar_output_standards where hour_count between $dateDiffHours AND ($dateDiffHours+23) AND  city_name = 'mumbai'";
/* Select queries return a resultset */
$link = dbConnection();
if ($result = mysqli_query($link, $sqlGetRequiredPower)) {
    printf("Select returned %d rows.\n", mysqli_num_rows($result));
    $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
    /* free result set */
    mysqli_free_result($result);
}
$defaulted_Device = array();
for($i=0; $i<24 ; $i++){
	$minPowerProduced = $row[$i]['power_generated'];//$mumbai[$dateDiffHours+$i];
	$timestamp = $dateForSample + ((60*60)*($i+1));
	//echo "Hour(i)= ".$i. " Power=". $minPowerProduced . " timestamp=". $timestamp."\n";
	$generatedPower = getPowerConsumed($solar_device_id,$timestamp,FLUX_DB_URL);
	if(!$generatedPower){
		continue;
	}
	$output_produced_percent = (($generatedPower/$minPowerProduced)*100);
	if( $output_produced_percent < 80){
		$defaulted_Device[] = array('time'=> date("d-m-Y H:i:s", $timestamp), 'minOutput' => $minPowerProduced, 'actualOutput'=> $generatedPower, 'percentDiff'=>$output_produced_percent);
	}
}
if(count($defaulted_Device) > 0 ){
	$message = "Successful";
	returnSuccess($message,$defaulted_Device);
} else {
	$message = "Output generated is above or equal to threshold power";
	returnSuccess($message);
}

function getPowerConsumed($deviceId = '1', $timestamp, $url){
	$url = $url.'query?';
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
