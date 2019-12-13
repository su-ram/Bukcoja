<?php

        if(isset($_POST["token"])){

		$token = $_POST["token"];
		//데이터베이스에 접속해서 토큰을 저장
		include_once 'config.php';
		$conn = mysqli_connect('127.0.0.1', 'root', '336339340', 'fire_detection');
		$query = "INSERT INTO token(token) Values ('".$token."') ON DUPLICATE KEY UPDATE token = '".$token."'; ";
		mysqli_query($conn, $query);

		mysqli_close($conn);
	}
?>

