<?php
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
/*******Including Config file*********/
include('staticContent.php');
include('config.php');

/******Validation check for Inputs******/
if(!isset($_GET)){
   $message="Argument Not received";
   returnCustomError($message);
}
//Storing Inpuuts
$solar_device_id = $_GET['solar_id'];
$input_date = $_GET['date'];

//Get the city name from solar Id
$sqlGetUserCity = "select user_city from user_records where user_public_id =". $solar_device_id;
$link = dbConnection();
if ($result = mysqli_query($link, $sqlGetUserCity)) {
    $final_result = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_free_result($final_result);
} else {
	returnCustomError("Customer no found w.r.t Solar ID");
}
$user_city = $final_result[0]['user_city'];
//Creating date time object for input date
$startTime = strtotime(date("d-m-Y", strtotime($input_date)));
$dateForSample = $startTime;
//Generating 1st day 1st Month of input year (2017-01-01 00:00:00) in epoc
$dateStartFrom = strtotime(date('Y-01-01', $startTime));
$dateDiff = ($dateStartFrom - $dateForSample);
//Converting epoc time to hours
$dateDiffHours = abs($dateDiff/3600);
//Getting Input date 24 hours Power standards from mysql
$sqlGetRequiredPower = "select datetime_for,power_generated from solar_output_standards where hour_count between $dateDiffHours AND ($dateDiffHours+23) AND  city_name = '$user_city'";
/* Select queries return a resultset */
if ($result = mysqli_query($link, $sqlGetRequiredPower)) {
    $row = mysqli_fetch_all($result,MYSQLI_ASSOC);
} else {
	returnCustomError("Data samples not present for $user_city and $dateDiffHours hours");
}
//Closign DB Connection
mysqli_close($link);
//Array used to store hours and its values who didnt generated expected output.
$defaulted_Device = array();
$count_data_not_found = 0;
for($i=0; $i<24 ; $i++){
	$minPowerProduced = $row[$i]['power_generated'];//$mumbai[$dateDiffHours+$i];
	$timestamp = $dateForSample + ((60*60)*($i+1));
	$generatedPower = getPowerConsumed($solar_device_id,$timestamp,FLUX_DB_URL);
	if(!$generatedPower){
		$count_data_not_found++;
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
} else if($count_data_not_found == 24){
	$message = "Data not found for $user_city and $solar_device_id user";
        returnSuccess($message,$defaulted_Device);
} else{
	$message = "Output generated is above or equal to threshold power";
	returnSuccess($message);
}

/*
* This function read query from influx and return the expected output.
* @input device Id, Timestamp, Influx URL
* @output Power generated for that device for that input hour.
*/
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

//return success json
function returnSuccess($message, $data=array(), $meta = null) {
    $result = json_encode(array( "m" => $message, "s" => 1,"meta" => $meta, "d" => $data));
    returnJSON($result);
}

//return custom error json
function returnCustomError($message) {
    $result = json_encode(array(  "m" => $message, "s" => 0, "d" => array()));
    returnJSON($result);
}

//return json
function returnJSON($result){
    header("Content-type: application/json");
    echo $result;
    exit;
}
?>
