<?php
        $token=$_POST['token'];
        $place=$_POST['place'];

        $conn = mysqli_connect('127.0.0.1' , 'root' , '336339340','fire_detection');
        if(mysqli_connect_errno()){
                 echo "MySQL 연결 오류 : ".mysqli_connect_error();
             }

        mysqli_set_charset($conn,'utf8');
        $query = "DELETE FROM member WHERE token='".$token."' AND place='".$place."'";
        $result = mysqli_query($conn,$query);
      
        echo(mysqli_affected_rows($result));
//     echo($result); 
        echo($token.", ".$place);
        mysqli_close($conn);
?>
