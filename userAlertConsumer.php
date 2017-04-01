<?php
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
include('config.php');

$test = new WorkerReceiver;
$test->listen();

class WorkerReceiver
{
    /**
     * Process incoming request to generate pdf invoices and send them through
     * email.
     */
    public function listen()
    {
        $connection = new AMQPConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD);
        $channel = $connection->channel();
        $channel->basic_qos(
            null,   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
            1,      #prefetch count - prefetch window in terms of whole messages
            null    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
            );
        $channel->basic_consume(
            'user_alert_queue',        #queue
            '',                     #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                  #no local - TRUE: the server will not send messages to the connection that published them
            false,                  #no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker, once we're done with a task
            false,                  #exclusive - queues may only be accessed by the current connection
            false,                  #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, 'process') #callback
            );

        while(count($channel->callbacks)) {
            echo " Waiting\n";
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }

    public function process(AMQPMessage $msg)
    {
        $i = 0;
        $result = false;
        while ($i < 3){
            //echo ($msg->body.'  ');
            $body_array = json_decode($msg->body, true);
            $result = $this->analyseSolarOutput($body_array['user_id'], $body_array['date']);
            if($result){
                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
                return true;
            }
            $i++;
        }
        if ($i == 3){
            $connection = new AMQPConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD);
            $channel = $connection->channel();
            $error_msg = new AMQPMessage($msg->body,array('delivery_mode' => 2));
            $channel->basic_publish($error_msg, "", "user_alert_error_queue");
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }
    }

    public function analyseSolarOutput($user_public_id, $date){
    	//Storing Inpuuts
        $solar_device_id = $user_public_id;
        $input_date = $date;
        //Get the Standard output
        $sqlGetUserCity = "select id.standard_output, ur.email_id from user_records ur left join installation_details id on id.installation_id =ur.installation_id where ur.user_public_id =". $solar_device_id;
        $link = dbConnection();
        if ($result = mysqli_query($link, $sqlGetUserCity)) {
            $final_result = mysqli_fetch_all($result,MYSQLI_ASSOC);
            $yearly_standard_output = (json_decode($final_result[0]['standard_output']));
            mysqli_free_result($final_result);
        } else {
            returnCustomError("Customer no found w.r.t Solar ID");
        }
        $user_email = $final_result[0]['email_id'];
        $startTime = strtotime(date("d-m-Y", strtotime($input_date)));
        $dateForSample = $startTime;
        //Generating 1st day 1st Month of input year (2017-01-01 00:00:00) in epoc
        $dateStartFrom = strtotime(date('Y-01-01', $startTime));
        $dateDiff = ($dateStartFrom - $dateForSample);
        //Converting epoc time to hours
        $dateDiffHours = abs($dateDiff/3600);
        mysqli_close($link);
        //Array used to store hours and its values who didnt generated expected output.
        $defaulted_Device = array();
        $count_data_not_found = 0;
        for($i=0; $i<24 ; $i++){
            $minPowerProduced = $yearly_standard_output[$dateDiffHours + $i];//$mumbai[$dateDiffHours+$i];
            $timestamp = $dateForSample + ((60*60)*($i+1));
            $generatedPower = $this->getPowerConsumed($solar_device_id,$timestamp,FLUX_DB_URL);
            //var_dump($generatedPower);
		 if(!$generatedPower){
                $count_data_not_found++;
                continue;
            }
            $output_produced_percent = (($generatedPower/$minPowerProduced)*100);
            if( $output_produced_percent < 80){
                $defaulted_Device[] = array('Date-Time(dd-mm-yy)'=> date("d-m-Y H:i:s", $timestamp), 'Min Output(W)' => $minPowerProduced, 'Actual Output(W)'=> $generatedPower, 'Difference(%)'=>round($output_produced_percent));
            }
        }
        if(count($defaulted_Device) > 0 ){
            return $this->sendAlertEmail($defaulted_Device, $user_email);
        } else if($count_data_not_found == 24){
            $message = "Data not found for $solar_device_id user";
            $this->returnSuccess($message,$defaulted_Device);
            return true;
        } else{
            $message = "Output generated is above or equal to threshold power";
            $this->returnSuccess($message);
            return true;
        }
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
        $this->returnJSON($result);
    }

    //return custom error json
    function returnCustomError($message) {
        $result = json_encode(array(  "m" => $message, "s" => 0, "d" => array()));
        $this->returnJSON($result);
    }

    //return json
    function returnJSON($result){
        header("Content-type: application/json");
        echo $result;
    }

    function build_table($array){
        // start table
        $html = '<html><body><form><table>';
        // header row
        $html .= '<tr>';
        foreach($array[0] as $key=>$value){
                $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
        $html .= '</tr>';
        // data rows
        foreach( $array as $key=>$value){
            $html .= '<tr>';
            foreach($value as $key2=>$value2){
                $html .= '<td style="padding:0px 25px 0px 5px">' . htmlspecialchars($value2) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table></form></body></html>';
        return $html;
    }
    //Mail Sending
    function sendAlertEmail($defaulted_hours,$user_email){
        $txt = $this->build_table($defaulted_hours);
        $to = $user_email;
        $subject = "Solar Alert!";
        $headers = "From: webmaster@example.com" . "\r\n" .
        "CC: agupta.92@gmail.com". "\r\n";
        $headers .= 'MIME-Version: 1.0'. "\r\n";
        $headers .= 'Content-Type: text/html; charset=ISO-8859-1'. "\r\n";
        $result = mail($to,$subject,$txt,$headers);
        return $result;
    }
}
?>
