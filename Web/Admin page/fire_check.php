<meta http-equiv="refresh" content="1">
<?php 
$link = mysqli_connect("localhost","root","336339340","fire_detection");
if(!$link){
  echo mysqli_connect_error();
  exit();
}
 mysqli_set_charset($link,'utf8');
$sql = "SELECT *  FROM `data` order by datetime DESC LIMIT 1 ";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
if($row['cen'] >=30 || $row['flame']==0){

echo "fire!!!";

      	 $query = "insert into detector (d_alarm, d_id) values (1, ".$row['device_id'].");";

       $result2 = mysqli_query($link,$query)
       or die( mysqli_error($link) );


}




?>
