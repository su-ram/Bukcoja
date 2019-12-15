<?php

$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();
$inputData="";

if($method == 'POST') {
 $inputData = file_get_contents('php://input');
 $json_data=json_decode($inputData);
 //$json_data = json_decode('{"device":"fireAlarmSuram2",  "temp":25.0,  "hum":30.0,  "co":1.0,  "person":0.0,  "flame":0.0}');

$temp = $json_data->{'temp'};
$co = $json_data->{'co'};
$hum = $json_data->{'hum'};
$person = $json_data->{'person'};
$flame = $json_data->{'flame'};

}

/*
error_log($inputData);
error_log($json_data->{'device'});
error_log($json_data->{'temp'});
error_log($json_data->{'hum'});
*/

$dbconn = mysqli_connect("127.0.0.1", "root", "336339340");
mysqli_select_db($dbconn, "fire_detection");
$insertSQL = "INSERT INTO data(cen,co,hum,person,person) VALUES('".$temp."','".$co."', 
'".$hum"','".$person"', '".$flame"');";
$result = mysqli_query($dbconn, $insertSQL);
if($result){error_log("Insert Success.");}
else{error_log("Insert Failed.");}
?>
