


<?php

//ThingPlugLogin 토큰 얻어서 디비에 저장하기.

$conn = mysqli_connect("127.0.0.1", "root", "336339340", "fire_detection");


include('rmccue-Requests-4055bc4/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
    //'X-Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VySWQiOiI3Zjc3ODBhMC1kOGY3LTExZTgtYmQyNy0xNzNjODYxYzNlNWMiLCJ1c2VyTmFtZSI6ImJ1a2NvamEiLCJjb21tb24iOnsidG9rZW5UeXBlIjoiQVQiLCJpc3N1ZXIiOiJhbm9ueW1vdXMiLCJpc3N1ZWRBdCI6MTU0MDc5OTY2NTMwNywiZXhwaXJhdGlvbiI6MTU0MDgwNjg2NTMwN319.nf3upOA7SSaxT4VgcsL6Ov2P7dkYDNgwF84ehSM_Tb-2QJ4nA2i92g8dnTccpLgQyCPY8Vz_rxlGbqUHhoc5Hg'
);
$data = '{ 
	"username": "bukcoja", 
	"password": "cha336339!"
}

';
$response = Requests::post('https://test.sktiot.com:9443/api/v1/login', $headers, $data);
$token = json_decode($response->{'body'});
$access = $token->{'accessToken'};

$sql = "insert into ThingPlugLogin(token) values('".$access."')";
$result = mysqli_query($conn, $sql);





?>

