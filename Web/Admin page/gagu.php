<?php
//header('Refresh:1');
$link = mysqli_connect("localhost","root","336339340","fire_detection");
mysqli_set_charset($link, "utf8");
$sql = "SELECT fire FROM data WHERE device_id=1 ORDER BY datetime DESC LIMIT 1";
$result=mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fire = $row['fire'];
//echo $fire;
$sql = "SELECT fire FROM data WHERE device_id=3 ORDER BY datetime DESC LIMIT 1";
$result=mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fire2 = $row['fire'];

if($fire==1 || $fire2==1){
    echo 1;
}
else
    echo 0;
?>
