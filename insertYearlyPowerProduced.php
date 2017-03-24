<?php
include('staticContent.php');
include('config.php');
$city_name = 'delhi';
$longitude = 77;
$latitude = 28;
echo $time = date('2017-01-01 00:00:00');
echo $time = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($time)));
$now = date("Y-m-d H:i:s");
for($i=0; $i < 8760 ; $i++ ){
	echo 'Hour '. $i. ' Time '. $time;
	$sqlInsert = "INSERT INTO `solar_output_standards`( `city_name`, `hour_count`, `datetime_for`, `longitude`, `latitude`,`power_generated`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES ('$city_name','$i','$time','$longitude','$latitude',$delhi[$i],1,'$now',1,'$now')";
	/* Create table doesn't return a resultset */
	$link = dbConnection();
	if (mysqli_query($link, $sqlInsert) === TRUE) {
	    printf("Record successfully created.\n");
	}
	$time = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($time)));
}
?>
