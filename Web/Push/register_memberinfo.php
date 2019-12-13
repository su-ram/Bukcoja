<?php
        $token=$_POST['token'];
        $place=$_POST['place'];
        $latitude=$_POST['latitude'];
        $longitude=$_POST['longitude'];
        
        $flatitude=floatval($latitude);
	$flongitude=floatval($longitude);
        $dbc = mysqli_connect('127.0.0.1' , 'root' , '336339340')
         or die( mysqli_connect_error() );
      
         mysqli_set_charset($dbc,'utf8');  
 
        mysqli_select_db($dbc, 'fire_detection');
        $query = "insert into member (token, name, place, lat, lon) values ('".$token."', 'bookcoja', '".$place."', ".$flatitude.", ".$flongitude.");";

        $result = mysqli_query($dbc,$query)
         or die( mysqli_error($dbc) );

        mysqli_close($dbc);
?>
