<?php

/******** FLUX DB URL *********/
define('FLUX_DB_URL', '');

/********DB Connection Initialization***********/
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
?>
