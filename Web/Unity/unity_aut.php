<meta http-equiv="refresh" content="1">
<?php 
$val="";
$link = mysqli_connect("localhost","root","336339340","fire_detection");
if(!$link){
  echo mysqli_connect_error();
echo $val;
  exit();
}
$sql = "SELECT *  FROM `data` where device_id=1 order by datetime DESC LIMIT 1 ";
$sql2 = "SELECT *  FROM `data` where device_id=3 order by datetime DESC LIMIT 1 ";

$result = mysqli_query($link, $sql);
$result2=mysqli_query($link, $sql2);

$row = mysqli_fetch_array($result);
$row2=mysqli_fetch_array($result2);

if($row['cen'] >=30 || $row['flame']==0){
echo $row['device_id'];
echo $row['person'];
}
else if($row2['cen'] >=30 || $row2['flame']==0){
echo $row2['device_id'];
echo $row2['person'];
}
else{
echo "";
}
?>
