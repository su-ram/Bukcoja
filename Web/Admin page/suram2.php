<?php

$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();
$inputData="";
$fire=-1;
if($method == 'POST') {
 $inputData = file_get_contents('php://input');
 $json_data=json_decode($inputData);
 //$json_data = json_decode('{"device":"fireAlarmSuram2",  "temp":25.0,  "hum":30.0,  "co":1.0,  "person":0.0,  "flame":0.0}');

$device = $json_data->{'device'};
error_log($device);
$temp = $json_data->{'temp'};
$co = $json_data->{'co'};
$hum = $json_data->{'hum'};
$person = $json_data->{'person'};
$flame = $json_data->{'flame'};

if($flame==0){$fire=1;}
else if($flame==1){$fire=0;}


$result = strcmp($device,"newfireAlarm");
//일치하는 경우 0 불일치 다른 값

//error_log($result);
if($result==0){
$device_id = 3;}//멘토님 엘티이
else{$device_id = 1;}//북코자 엘티이
/*
error_log($inputData);
error_log($json_data->{'device'});
error_log($json_data->{'temp'});
error_log($json_data->{'hum'});
*/

$dbconn = mysqli_connect("127.0.0.1", "root", "336339340", "fire_detection");
$insertSQL = "INSERT INTO data(cen,co,hum,person,flame, fire, device_id) VALUES('".$temp."','".$co."', 
'".$hum."','".$person."', '".$flame."', '".$fire."', '".$device_id."');";


$result = mysqli_query($dbconn, $insertSQL);

if(!$result){error_log("Insert Failed.");}

}

?>
