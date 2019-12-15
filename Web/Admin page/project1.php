<?php
error_reporting(E_ALL);
ini_set("display_errors",1);
$db = new mysqli('127.0.0.1','root','336339340','fire_detection');
if (mysqli_connect_error()){
echo "ERROR:cannot connect database.";
exit;
}
$id= $_GET["id"];
$cen = $_GET["dht11DataTemp"];
$hum=$_GET["dht11DataHum"];
$co=$_GET["co"];
$flame=$_GET["flameData"];
$motion=$_GET["motion"];
$query = "
INSERT INTO data(device_id,cen, hum, co,flame,person)
VALUES(
'".$id."',
'".$cen."',
'".$hum."',
'".$co."',
'".$flame."',
'".$motion."'
)
";
$result = mysqli_query($db, $query);
if ($result) {
echo $db->affected_rows."data inserted into databases.";
}else{
echo "Failed.";
}
mysqli_close($db);
?>
