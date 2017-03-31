<?php
error_reporting(E_ERROR | E_PARSE | E_NOTICE);
include('staticContent.php');
include('config.php');

$inputDate = trim($argv[1]) . ' 00:00:00';
$yearStartDate = trim($argv[2].'-01-01 00:00:00');
echo 'Started for ';
echo $dateForSample = strtotime($inputDate);
echo ' From ';
echo $dateStartFrom = strtotime($yearStartDate);
$hoursToLowProduction = array(12,13,15);
echo ' Diff ';
echo $dateDiff = ($dateStartFrom - $dateForSample);
//echo $dateDiff;
$dateDiffHours = abs($dateDiff/3600);
if($dateDiffHours >= 0){
	for($i=0; $i<24; $i++){
		if($i<7 || $i>19){
			$powerProduced = 0;
			$timestamp = strtotime($inputDate) + ((60*60)*($i+1));
		} else {
			if(in_array($i, $hoursToLowProduction)){
				$percent_diff = rand ( 50,79 );
				echo $powerProduced =(($bang[$dateDiffHours+$i]* $percent_diff)/100);
				echo $timestamp = strtotime($inputDate) + ((60*60)*($i+1));
			} else {
				$powerProduced =(($bang[$dateDiffHours+$i]* 120)/100);
				$timestamp = strtotime($inputDate) + ((60*60)*($i+1));
			}
		}
		echo "\nHour(i)= ".$i. " Power=". $powerProduced . " timestamp=". $timestamp;
		$is_dump_success = store_date_influx($powerProduced, $timestamp,FLUX_DB_URL);
	}
} else {
	echo "Error: From date is greater than sample date";
}

function store_date_influx($powerProduced, $timestamp,$url){
 	$url = $url . 'write?db=oorjan';
	$input = 'solar_device_performance,deviceId=3,output=' . $powerProduced . ' inputFrom="script" '.$timestamp;
	$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => -1,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $input,
        CURLOPT_HTTPHEADER => array("cache-control: no-cache","content-type: text/plain"),
    ));
    $result = curl_exec($curl);
    $result = json_decode($result); //print_r($result); exit;
    $error = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($httpCode == 200 && $result->key ){
        var_dump($result);
    }
}
?>
