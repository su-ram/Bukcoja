<?php
header('Refresh:1');
// 디비 접근
$link = mysqli_connect("localhost","root","336339340","fire_detection");

// 디비 쿼리 실행
$sql = "SELECT * FROM data order by datetime DESC LIMIT 1";
$sqltoken = "select * from ThingPlugLogin order by datetime desc limit 1";
// 결과 반환
$result =mysqli_query($link, $sql);
$data = mysqli_fetch_array($result);

// 조건 검사
$device_id = $data['device_id'];
$fire = $data['fire'];

// 화재 발생
if($fire == 1){
error_log($device_id);
if($device_id == 1){

include('rmccue-Requests-4055bc4/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'accept' => 'application/json',
    'content-type' => 'application/json',
    'X-Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VySWQiOiI3Zjc3ODBhMC1kOGY3LTExZTgtYmQyNy0xNzNjODYxYzNlNWMiLCJ1c2VyTmFtZSI6ImJ1a2NvamEiLCJjb21tb24iOnsidG9rZW5UeXBlIjoiQVQiLCJpc3N1ZXIiOiJhbm9ueW1vdXMiLCJpc3N1ZWRBdCI6MTU0MTM5NTcwNDM3MCwiZXhwaXJhdGlvbiI6MTU0MTQwMjkwNDM3MH19.9XI-Gemcx6imUOIbw1Wx3YYPmr5xDHUtu01cyzjm8YTWgXoaUuPL1gEmlE6KvroO5qOurkq2zgMVraGXyaTZJQ'
);
$data = '
{ 
  "rpcReq": 
  { 
  	"jsonrpc": "2.0",
    "method": "tp_user", 
    "params": [ { "fire": "on" } ], 
    "id": 10 
  }, 
  "rpcMode": "oneway"
}
';


$response = Requests::post('https://test.sktiot.com:9443/api/v1/dev/bcjfire/newfireAlarm/rpc', $headers, $data);
}



else if($device_id==3){ 

include('rmccue-Requests-4055bc4/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'accept' => 'application/json',
    'content-type' => 'application/json',
    'X-Authorization' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJ1c2VySWQiOiI3Zjc3ODBhMC1kOGY3LTExZTgtYmQyNy0xNzNjODYxYzNlNWMiLCJ1c2VyTmFtZSI6ImJ1a2NvamEiLCJjb21tb24iOnsidG9rZW5UeXBlIjoiQVQiLCJpc3N1ZXIiOiJhbm9ueW1vdXMiLCJpc3N1ZWRBdCI6MTU0MTM5NTcwNDM3MCwiZXhwaXJhdGlvbiI6MTU0MTQwMjkwNDM3MH19.9XI-Gemcx6imUOIbw1Wx3YYPmr5xDHUtu01cyzjm8YTWgXoaUuPL1gEmlE6KvroO5qOurkq2zgMVraGXyaTZJQ'
);
$data = '
{ 
  "rpcReq": 
  { 
  	"jsonrpc": "2.0",
    "method": "tp_user", 
    "params": [ { "fire": "on" } ], 
    "id": 10 
  }, 
  "rpcMode": "oneway"
}

';


$response = Requests::post('https://test.sktiot.com:9443/api/v1/dev/bcjfire/fireAlarmSuram2/rpc', $headers, $data);
}
}



?>