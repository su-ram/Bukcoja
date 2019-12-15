

<?php

$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();
$inputData;
echo $method;
if($method == 'POST') {
 $inputData = file_get_contents('php://input');
}

/*
$dbconn = mysqli_connect("127.0.0.1", "root", "336339340");
mysqli_select_db($dbconn, "fire_detection");
$insertSQL = "INSERT INTO data VALUES('".$temp."','".$co."', '1','".$"' );";
mysqli_query($dbconn, $insertSQL);
*/

error_log($inputData);
?>
