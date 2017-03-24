<?php

define('FLUX_DB_URL', '');
function dbConnection(){
    $dbhost = '';
    $dbuser = '';
    $dbpass = '';
    $link = mysqli_connect($dbhost, $dbuser, $dbpass,'');
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
   return $link;
}

// https://developer.nrel.gov/api/pvwatts/v5.json?api_key=DEMO_KEY&lat=19&lon=73&system_capacity=10&azimuth=180&tilt=19&array_type=1&module_type=1&losses=10&dataset=IN&timeframe=hourly

// https://developer.nrel.gov/api/pvwatts/v5.json?api_key=DEMO_KEY&lat=13&lon=78&system_capacity=5&azimuth=180&tilt=19&array_type=1&module_type=1&losses=10&dataset=IN&timeframe=hourly

// https://developer.nrel.gov/api/pvwatts/v5.json?api_key=DEMO_KEY&lat=28&lon=77&system_capacity=13&azimuth=180&tilt=19&array_type=1&module_type=1&losses=10&dataset=IN&timeframe=hourly

// grant all on oorjan_db.* to 'oorjan_user'@'localhost' identified by 'admin123';
?>
