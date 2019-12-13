<?php 
   
   function send_notification ($tokens, $message)
   {
      $url = 'https://fcm.googleapis.com/fcm/send';
      $fields = array(
          'registration_ids' => $tokens,
          'data' => $message
         );

      $headers = array(
         'Authorization:key = '.'AAAATyFrtsE:APA91bFXf6yaP-6_RqOiRGb5SC5G-Z0N67X6gHctqUnIE2huX_bXCQmijcjlRK6nDAYP1jttWCDjIph7zPAT0s0zw0QoyVHtnVDxvxNG9h0bfaYTxY_Nl_71WVmUuR7dfUBP0jQcCggQK9p5p8S2Y29TuuUTudO9Bw',
         'Content-Type: application/json'
         );

      $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);        
      
     
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
   }
   


   //데이터베이스에 접속해서 토큰들을 가져와서 FCM에 발신요청
   include_once 'config.php';
   $conn = mysqli_connect("127.0.0.1", "root", "336339340", "fire_detection");

   $sql = "SELECT token FROM token";

   $result = mysqli_query($conn,$sql);
   $tokens = array();

   if(mysqli_num_rows($result) > 0 ){

      while ($row = mysqli_fetch_assoc($result)) {
         $tokens[] = $row["Token"];
      }
   }



   mysqli_close($conn);
   $alarm=$_POST['alarm'];
if($alarm == null){
echo "no";
}else {echo $alarm;}

   if($alarm=="1"){
        $myMessage = "fire alarm"; //폼에서 입력한 메세지를 받음
    
       
       $message = array("message" => $myMessage);
       $message_status = send_notification($tokens, $message);
       echo $message_status;
    }



 ?>
