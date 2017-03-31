<?php
include('staticContent.php');
include('config.php');
$city_name = 'bangalore';
$longitude = 78;
$latitude = 13;
echo $time = date('2017-01-01 00:00:00');
echo $time = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($time)));
$now = date("Y-m-d H:i:s");
$out = json_encode($bang);
$link = dbConnection();
echo $sqlInsert = "INSERT INTO `installation_details`(`city_name`, `longitude`, `latitude`, `standard_output`, `created_by`, `created_on`, `updated_by`, `updated_on`) VALUES ('$city_name','$longitude','$latitude','$out',1,'$now',1,'$now')";
if (mysqli_query($link, $sqlInsert) === TRUE) {
	    printf("Record successfully created.\n");
	}
?>
