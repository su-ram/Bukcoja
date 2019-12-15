 #include "Arduino.h"
#include "Timer.h"
#include <skt_lte_m1.h>
#include <SPI.h>
#include "DHT.h"
#include <NS_Rainbow.h>
#define DHTTYPE DHT11

/* ThingPlug 접속 정보 */
#define SKTP_HOST_IP    "211.234.246.112"
#define SKTP_PORT       1883
#define SKTP_DEV_TOKEN "a046cd30dc2711e8b1dc"   // 수정 필요 fireAlarmSuram2 화재경보기 1일때 
//#define SKTP_DEV_TOKEN "db691820df2211e8b1dc"     //화재경보기2일 떄 
#define SKTP_SERVICE_ID "bcjfire"             // 수정 필요
#define SKTP_DEV_ID "fireAlarmSuram2"                    // 수정 필요 화재경보기 1일 때 
//#define SKTP_DEV_ID "newfireAlarm"      //화재경보기2일때
#define PHONE_NUMBER    "01052375729"            // 수정 필요 

/*대피 LED 정보*/
#define LED_EVA_C 10
#define LED_EVA_D 11
//#define LED_EVA_E 12  //화재경보기2일 떄는 주석
#define LED_EVA_CELL 8
NS_Rainbow ns_stick_C = NS_Rainbow(LED_EVA_CELL,LED_EVA_C);
NS_Rainbow ns_stick_D = NS_Rainbow(LED_EVA_CELL, LED_EVA_D);
//NS_Rainbow ns_stick_E = NS_Rainbow(LED_EVA_CELL, LED_EVA_E);  //화재경보기2일 떄는 주석
/* 데이터 전송 주기 */
#define SEND_PERIOD 1000

/* CDS 핀 설정 */
#define DHT_PIN  8  
#define CO_PIN  5 
#define FLAME_PIN  9 
#define MOTION_PIN  6  
DHT dht(DHT_PIN,DHTTYPE); 
/* 화재 관련 변수*/
boolean fireAlarm=false;
/*LED 켜는 함수*/
unsigned long previousMillis;
unsigned long currentMillis;
const long interval = 1000;
boolean gLedState;
int gLed = 2;
int yLed = 3;
int rLed = 4;
void LED(int getLed, int getLedDelay);
void sendSensorData(void);
void rxdataHandling(void);
String rxdata;
/*부저 관련 */
int buzzer = 7;
const int bYellowFrequency = 262;
const int bYellowDuration = 500;
const int bRedFrequency = 900;
const int bRedDuration = 300;


/* 센싱 데이터 구조체 */
typedef struct
{
  int temp;
  int hum;
  int co;
  int motion;
  int flame;
} sensorData;
int dht11DataTemp;
int dht11DataHum;
sensorData curr_sensor_data;
unsigned long lastSendTime = 0;         // last time you connected to the server, in milliseconds
char server_name[] = "211.253.26.22";
int cdsValue = 0;

int tp_try = 0;

unsigned long fireTime=0;  //대피 LED 지속 시간 주는
int flag=0;    //초기 두번 fireAlarm=true 되는 것을 막는
//The setup function is called once at startup of the sketch
void setup()
{
  Serial.begin(115200);
  Serial3.begin(115200);

  Serial.println("Start Program");
  Serial.println("Cat.M1 Booting...");

  //delay(7000);
  Serial.println("Cat.M1 Booting end");

 /*LED 및 부저 핀번호 초기화*/
   pinMode(gLed, OUTPUT);
   pinMode(yLed, OUTPUT);
   pinMode(rLed, OUTPUT);

  /* LTE 모듈 초기화 */
  while (true)
  {
    if (LTE.init(&Serial3) == true) {
      break;
    }
    else
    {
      Serial.println("Fail to initialize LTE Module. Please Check your H/W Status");
    }
    delay(1000);
  }

  /* 망 접속 */
  while (true)
  {
    if (LTE.begin() == true) {
      break;
    }
    else
    {
      Serial.println("Fail to attach the LTE Network");
    }
    delay(1000);
  }

  Serial.println("You're connected to the network");
  Serial.println(LTE.getIMEI());
  Serial.println(LTE.getSwVersion());
  sensor_setup();

  /*대피 LED 초기 설정*/
   ns_stick_C.begin();
  ns_stick_D.begin();
  //ns_stick_E.begin();

   
}
void sensor_setup(){
  
   pinMode(CO_PIN, INPUT);
   pinMode(FLAME_PIN, INPUT);
   pinMode(MOTION_PIN, INPUT);
   dht.begin();
   previousMillis = millis();
   //gLedState = false;

   //Serial.begin(9600);
   /*
   while(!Serial)
     ;
     */

}
// The loop function is called in an endless loop
void loop()
{
  /* 접속 시도 */
  if ( LTE.getStatusSKTP() == false && tp_try < 2)
  {
    Serial.println("Starting connection to SKT ThingPlug server");
    LTE.connectSKTP((char*)SKTP_HOST_IP, SKTP_PORT, (char*)SKTP_DEV_TOKEN, (char*)SKTP_SERVICE_ID, (char*)SKTP_DEV_ID);
    tp_try = tp_try + 1; 
    Serial.println(digitalRead(FLAME_PIN));
  }

  /* 접속 성공 */
  else
  {  if(flag==0||flag==1){
      fireAlarm=false;
      flag++;
      curr_sensor_data.flame=1;}
    if ( millis() - lastSendTime > SEND_PERIOD) // 주기 데이터
    {
      curr_sensor_data.temp = dht.readTemperature();
      curr_sensor_data.hum =  dht.readHumidity();
      curr_sensor_data.flame=digitalRead(FLAME_PIN);
      curr_sensor_data.co = digitalRead(CO_PIN);                                                                                                                                                                                                    
      curr_sensor_data.motion = digitalRead(MOTION_PIN);
      sendSensorData();
      lastSendTime = millis();
      //fireAlarm=false;

      
    }
  
  checkFire();
  rxdataHandling(); //잡시 중단 메소드..... suram
  if(fireAlarm){
  exitLED();}

  if(millis()-fireTime > 10000){  
    //Serial.println("Eeeeeee");       //대피 LED 켜진지 10초되면 꺼지게. 
   fireAlarm=false;
   }
}
}

void sendSensorData(void)
{
  char payload[500];

  //Serial.println(curr_sensor_data.temp);
  /*sprintf(payload, "{\"temp\":%d,\"hum\":%d,\"co\":%d,\"flame\":%d,\"person\":%d}", 
          curr_sensor_data.temp, curr_sensor_data.hum,curr_sensor_data.co, curr_sensor_data.flame, curr_sensor_data.motion);*/
         sprintf(payload, "{\"temp\":%d,\"hum\":%d,\"co\":%d,\"person\":%d,\"flame\":%d}", 
          curr_sensor_data.temp, curr_sensor_data.hum,curr_sensor_data.co, curr_sensor_data.motion,curr_sensor_data.flame);

  Serial.println(payload);

  LTE.sendDataSKTP(SktLteM1Drv::TELEMETRY, payload, SktLteM1Drv::JSON);

  Serial.println("보냈당");
}

void softwareReset()
{
  asm volatile ("  jmp 0");
}

void checkFire(){
    currentMillis = millis();

  if(curr_sensor_data.flame==0){
    fireTime=millis();
    fireAlarm=true;  //대피LED 켜기 위한 boolean
    Serial.println("fire!!!!!");
    tone(buzzer, bRedFrequency, bRedDuration);
    LED(rLed, 100);
    }
    else if(curr_sensor_data.temp>=30 || curr_sensor_data.co==0){
      //fireAlarm=false;
      Serial.println("cauution!!!");
      tone(buzzer, bYellowFrequency, bYellowDuration);
      LED(yLed, 300);
      }
      else{
        //fireAlarm=false;
        Serial.println("safe!!!!!");
       noTone(buzzer);
    //LED(gLed, 100);

    if(currentMillis - previousMillis >= (interval*2)){
      previousMillis = currentMillis;
      gLedState = !gLedState;
      
      digitalWrite(gLed, gLedState);
        
        }
  }
}

  void LED(int getLed, int getLedDelay){

  int led = getLed;
  int ledDelay = getLedDelay;

  digitalWrite(led, HIGH);
  delay(ledDelay);
  digitalWrite(led, LOW);
  delay(ledDelay);
  
  }

  void rxdataHandling(void)
{ 
  if (Serial3.available())
  {
    rxdata = Serial3.readString();
    //Serial.print(rxdata);
    const char* chr = rxdata.c_str();

    if(strstr(chr,"\"fire\":\"on\"")!= NULL){
      Serial.println("다른 화재경보기에서의 화재 발생.");
      //다른 화재경보기에서 화재 발생시 실행되어야 할 코드.
      }
      
    
  }else {//Serial.println("Serial2 not available.");}
}

void exitLED(){
  
  unsigned int t = 50;
    Serial.println("Wwwwww");
       for(int i=0; i<LED_EVA_CELL; i++){
      ns_stick_C.setColor(i, 0, 255, 0); //화재경보 1일때는 정상인 것
      ns_stick_D.setColor(i, 0, 255, 0);
     // ns_stick_E.setColor(i, 0, 0, 255); //화재경보기1일때는 주석
      ns_stick_C.show();
      ns_stick_D.show();
     // ns_stick_E.show(); //화재경보기1일떄는 주석
      delay(t);
    }
    
  ns_stick_C.clear();
  ns_stick_D.clear();
  //ns_stick_E.clear(); //화재경보기1일떄는 주석
  ns_stick_C.show();
  ns_stick_D.show();
  //ns_stick_E.show();  //화재경보기1일떄는 주석
  //delay(t);
      
    }
  
  
