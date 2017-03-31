<?php
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
include('config.php');

$connection = new AMQPConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD);
$channel = $connection->channel();
$sqlGetUserIds = "select user_public_id from user_records";
$link = dbConnection();
if ($result = mysqli_query($link, $sqlGetUserIds)) {
    $final_result = mysqli_fetch_all($result);
    mysqli_free_result($final_result);
} else {
	returnCustomError("No user ID found");
}
foreach ($final_result as $key => $value) {
	echo 'key '.$key;
	echo 'Value '.$value;
	$msg = new AMQPMessage('1',array('delivery_mode' => 2));
	$channel->basic_publish($msg, "", "user_alert_queue");
}
$channel->close();
$connection->close();

?>
