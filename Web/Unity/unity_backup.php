<meta http-equiv="refresh" content="1">
<?php 
$val="";
$link = mysqli_connect("localhost","root","336339340","fire_detection");
if(!$link){
  echo mysqli_connect_error();
echo $val;
  exit();
}
$sql = "SELECT *  FROM `data` order by datetime DESC LIMIT 1 ";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
if($row['cen'] >=30 || $row['flame']==0){
echo $row['device_id'];
//echo "3";
echo "<br>";
//echo "1";

echo $row['person'];
}
else{
echo $val;
}
?>
