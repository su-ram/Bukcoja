/* arduino web client - GET request for index.html or index.php */

#include <SPI.h>
#include <Phpoc.h>
#define VIBRATION 53
char server_name[] = "211.253.26.22";
PhpocClient client;
char c;
char pre_c='0';
boolean fire=false;
boolean vibrationState=false;
void setup() {
  pinMode(VIBRATION, OUTPUT);
  
  Serial.begin(9600);
  while(!Serial)
    ;

  Serial.println("Sending GET request to web server");
    
  Phpoc.begin(PF_LOG_SPI | PF_LOG_NET);
  //Phpoc.begin();
  
  if(client.connect(server_name, 80))
  {
    Serial.println("Connected to server");
    client.println("GET /gagu.php? HTTP/1.0");
    client.println();
  }
  else
    Serial.println("connection failed in setup()");
    
}

void loop() {
  //Serial.println("loop!");
  
  if(client.connected()){
      if(client.available())
      {
        //Serial.println("Available");
        c = client.read();
        //Serial.print(c);
       }
       else{
         //Serial.println("Not Available");
       }

       if(!client.connected())
       {
          Serial.println(c);
          
          if(c=='1' && pre_c=='0'){
            digitalWrite(VIBRATION, true);
          }
          else if(c=='0' && pre_c=='1'){
            digitalWrite(VIBRATION, false);
          }
          pre_c=c;
          Serial.println("disconnected");
          client.stop();
          delay(3000);

        }
  }
  else if(!client.connected()){
      Serial.println("Not connected!");
      if(client.connect(server_name, 80))
      {
       Serial.println("Connected to server");
       client.println("GET /gagu.php? HTTP/1.0");
       client.println();
      }
      else
       Serial.println("connection failed");
  }

}

