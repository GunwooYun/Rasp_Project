#include <MsTimer2.h>
#include <DFRobot_DHT11.h>

DFRobot_DHT11 DHT;
#define DHT11_PIN 8

int led = 13;
int flame = A5;
int state = 0;
int cds = A0;
int flame_state;

boolean sensor_read = 0;
char msg[15];
void setup() {
  // put your setup code here, to run once:
  MsTimer2::set(1000, test);
  MsTimer2::start();

  pinMode(led, OUTPUT);
  pinMode(flame, INPUT);
  pinMode(cds, INPUT);
  Serial.begin(9600);
}

void loop() {
  // put your main code here, to run repeatedly:
  cds = analogRead(A0)-250;
  state = analogRead(A5);


  digitalWrite(led, LOW);

  if(state > 0)
  {
    Serial.println("ON");
    digitalWrite(led, HIGH);
    delay(100);
    flame_state = 1;
    
  }
  else
  {
    Serial.println("OFF");
    digitalWrite(led, LOW);
    delay(100);
    flame_state = 0;
  }
  
  if(sensor_read)
  {
    char temp, humi;
    DHT.read(DHT11_PIN);
    temp = DHT.temperature;
    humi = DHT.humidity;
    
    sprintf(msg, "%d:%d:%d:%dL", humi, temp, flame_state, cds);
    //Serial.write(msg);
    Serial.println(msg);
    Serial.println(state);
    
  }
  sensor_read = 0;
}

void test()
{
  sensor_read = !sensor_read;
}
