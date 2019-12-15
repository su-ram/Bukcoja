<?php
function send_notification ($tokens, $message)
{
  $url = 'https://fcm.googleapis.com/fcm/send';
  $fields = array(
    'registration_ids' => $tokens,
    'data' => $message
  );

  $headers = array(
   'Authorization:key = '.'AAAATyFrtsE:APA91bFXf6yaP-6_RqOiRGb5SC5G-Z0N67X6gHctqUnIE2huX_bXCQmijcjlRK6nDAYP1jttWCDjIph7zPAT0s0zw0QoyVHtnVDxvxNG9h0bfaYTxY_Nl_71WVmUuR7dfUBP0jQcCggQK9p5p8S2Y29TuuUTudO9Bw',
   'Content-Type: application/json'
 );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
  $result = curl_exec($ch);


  if ($result === FALSE) {
   die('Curl failed: ' . curl_error($ch));
 }
 curl_close($ch);
 return $result;
}
error_reporting(E_ALL);
ini_set("display_errors",1);

$link = mysqli_connect("localhost","root","336339340","fire_detection");

if(!$link){
  echo mysqli_connect_error();
  exit();
}else {
  mysqli_set_charset($link, "utf8");
}

$return_array=array();
$sql2 = "SELECT * FROM data ORDER BY datetime DESC LIMIT 1";
$result2 = mysqli_query($link, $sql2);
$row2 = mysqli_fetch_array($result2);

$row_array['deviceid']=$row2['device_id'];
$row_array['rowid']=$row2['rowid'];
$row_array['datetime']=$row2['datetime'];
$row_array['hum']=$row2['hum'];
$row_array['cen']=$row2['cen'];
$row_array['co']=$row2['co'];
$row_array['flame']=$row2['flame'];
$flames[]=$row2['flame'];

array_push($return_array,$row_array);

echo json_encode($return_array,JSON_UNESCAPED_UNICODE)."\n";

if(($flames[0]==0 && $flames[0]!=null)){

  echo $flames[1];
  include_once 'config.php';
  $conn = mysqli_connect("127.0.0.1", "root", "336339340", "fire_detection");
  $sql = "INSERT INTO detector(d_alarm) VALUES (1)";
  mysqli_query($conn, $sql);

  $sql = "SELECT token FROM token";

  $result = mysqli_query($conn,$sql);
  $tokens = array();

  if(mysqli_num_rows($result) > 0 ){

    while ($row = mysqli_fetch_assoc($result)) {
     $tokens[] = $row["token"];
   }
 }

 $sql = "INSERT INTO gagu(fire) VALUES(1)";
 mysqli_query($conn, $sql);


 mysqli_close($conn);
$myMessage = "fire alarm"; //폼에서 입력한 메세지를 받음

$message = array("message" => $myMessage);
$message_status = send_notification($tokens, $message);
echo $message_status;
}
else
  echo "not send Message";
mysqli_close($link);
?>

