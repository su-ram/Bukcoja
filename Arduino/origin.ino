#include <IP6Address.h>
#include <Phpoc.h>
#include <PhpocClient.h>
#include <PhpocDateTime.h>
#include <PhpocEmail.h>
#include <PhpocServer.h>


 /*
  * (평상시)
  * 무드등, 서서히 색 바뀌기
  * (화재시)
  * 무드등, 급격히 빨간색 깜빡 delay(50)
  * 햄토리 침대, 강한 진동
  */

#define RED 3
#define BLUE 5
#define GREEN 6
#define VIBRATION 53
char server_name[] = "211.253.26.22";
unsigned long previousMillis;
unsigned long currentMillis;
const long interval = 500;
boolean vibrationState;
PhpocClient client;
int fire = 1; //화재여부 test시 1
 
void setup() {
  // put your setup code here, to run once:

  pinMode(RED, OUTPUT);
  pinMode(BLUE, OUTPUT);
  pinMode(GREEN, OUTPUT);
  pinMode(VIBRATION, OUTPUT);

  previousMillis = millis();
  vibrationState = false;



  Serial.begin(9600);
   while(!Serial)
     ;
  Serial.println("Sending GET request to web server");
    
  Phpoc.begin(PF_LOG_SPI | PF_LOG_NET);
 // Serial.println(client.available());

  if(client.connect(server_name, 80))
  {
    Serial.println("Connected to server");
   // Serial.println(client.available());

    client.println("GET /gagu.php HTTP/1.1");
    client.print("Host: ");
    client.println(server_name);
    client.println("Connection: keep-alive");
    client.println();
    
  }
  else
    Serial.println("connection failed");

}

void loop() {
  // put your main code here, to run repeatedly:

  currentMillis = millis();

 //  Serial.println(client.available());
   if(client.available())
  {
    //String s = client.readStringUntil('\n');
    char c = client.read();
    Serial.print(c);

    
  }if(!client.connected())
  {
    Serial.println("disconnected");
    client.stop();
    while(1)
      ;
  }

  if(fire==1){
    
    digitalWrite(RED, HIGH);
    delay(50);
    digitalWrite(RED, LOW);
    delay(50);
    
    /*
    analogWrite(VIBRATION, 200);
    delay(500);
    analogWrite(VIBRATION, 0);
    delay(500);
    */
    /*
    digitalWrite(VIBRATION, HIGH);
    delay(500);
    digitalWrite(VIBRATION, LOW);
    delay(500);
    */

    if(currentMillis - previousMillis >= interval){
      previousMillis = currentMillis;
      vibrationState = !vibrationState;

      digitalWrite(VIBRATION, vibrationState);
    }
    
  }
  else{
    //red > green
    for(int i=0; i<256; i++){
      analogWrite(RED, 255-i);
      analogWrite(GREEN, i);
      analogWrite(BLUE, 0);
      delay(10);
    }
    //green > blue
    for(int i=0; i<256; i++){
      analogWrite(RED, 0);
      analogWrite(GREEN, 255-i);
      analogWrite(BLUE, i);
      delay(10);
    }
    //blue > red
    for(int i=0; i<256; i++){
      analogWrite(RED, i);
      analogWrite(GREEN, 0);
      analogWrite(BLUE, 255-i);
      delay(10);
    }
  }
  

}
