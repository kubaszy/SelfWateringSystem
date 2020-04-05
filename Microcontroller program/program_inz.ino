
#include "DHT.h"
#include <SPI.h>
#include <Ethernet.h>


byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xEF }; //Setting MAC Address

#define DHTPIN 2

#define DHTTYPE DHT22

IPAddress ip(192,168,0,191); 
EthernetClient client; 
EthernetClient client2; 


//Read DHT 22
DHT dht(DHTPIN,DHTTYPE);
float humidityData;
float temperatureData;

//Receive watering plan
String rcv="";
char server[] = "www.serwer1971874.home.pl";

//Read SoilWatch10
int humiditySoil[] = {-100,-100,-100};

//Read intensityUV
int intensityUV[]  = {-100,-100,-100};

//Read water level
int empty_reservoir = 0;

//connection status
bool status = false ; 

void setup() {
  Serial.begin(9600);
  dht.begin();
  if (Ethernet.begin(mac) == 0) {
  Serial.println("Failed to configure Ethernet using DHCP");
  Ethernet.begin(mac, ip);
  }

  pinMode(22, OUTPUT);

  //Pin pump 
  pinMode(30, OUTPUT); 
  pinMode(31, OUTPUT); 
  digitalWrite(30, HIGH);
  digitalWrite(31, HIGH);

  //pin electro ster
   pinMode(49, OUTPUT);
   digitalWrite(49, HIGH);
   pinMode(40, OUTPUT);
   digitalWrite(40, HIGH);
   pinMode(53, OUTPUT);
   digitalWrite(53, HIGH);
   
  
  delay(3000);
}
//------------------------------------------------------------------------------


/* Infinite Loop */
void loop(){
  Sending_To_DB_0_Status_pump(1);
  Sending_To_DB_0_Status_pump(2);
  Sending_To_DB_0_Status_pump(3);
  status = false;
  Read_DHT22();
  Read_SoilWatch10(3); 
  Read_ML8511(3);
  Read_water_level();

  Sending_To_DB_Sensors(); 
  
  
  delay(5000);
  if (status==true) receive_watering_plan();
  Serial.println(empty_reservoir);
  delay(5000); // interval
}


  void Read_DHT22() {
    humidityData = dht.readHumidity();
    temperatureData = dht.readTemperature(); 
    if (isnan(temperatureData) || isnan(humidityData)) {
      Serial.println("Failed to read from DHT");
      temperatureData=-100;
      humidityData=-100;
    } else {
      Serial.print("Humidity: "); 
      Serial.print(humidityData);
      Serial.print(" %\t");
      Serial.print("Temperature: "); 
      Serial.print(temperatureData);
     Serial.println(" *C");
      }
    }

  void Sending_To_DB_Sensors()   //CONNECTING WITH MYSQL AND SEND TO DB DATA FROM SENSORS
 {

    String data="humidity="+String(humidityData, 0)+"&temperature="+String(temperatureData, 0)+"&soil1="+String(humiditySoil[0])+"&soil2="+String(humiditySoil[1])+"&soil3="+String(humiditySoil[2])+"&intensityUV1="+String(intensityUV[0])+"&intensityUV2="+String(intensityUV[1])+"&intensityUV3="+String(intensityUV[2])+"&empty="+String(empty_reservoir);
    if (client.connect(server, 80)) {
    client.println("POST /dht.php HTTP/1.1");
    client.println("Host:  www.serwer1971874.home.pl");
    //client.println("User-Agent: Arduino/1.0");
    //client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(data.length());
    client.println();
    client.print(data);
    status = true ;
  } else {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }
 // client.stop(); 
 }


   void Sending_To_DB_0_Status_pump(int numberOfPlants)   //CONNECTING WITH MYSQL AND SEND TO DB DATA FROM SENSORS
 {
  
    String data="numberOfPlants="+String(numberOfPlants);
    if (client.connect(server, 80)) {
    client.println("POST /statuspump0.php HTTP/1.1");
    client.println("Host:  www.serwer1971874.home.pl");
    //client.println("User-Agent: Arduino/1.0");
    //client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(data.length());
    client.println();
    client.print(data);
  } else {
    // if you didn't get a connection to the server:
    Serial.println("connection failed");
  }
 // client.stop(); 
 }


 void receive_watering_plan()
{
  rcv="";
  if (client2.connect(server, 80)) 
  {
    Serial.println("Connection established 1");
    client2.print(String("GET ") + "/readdb2.php/" + " HTTP/1.1\r\n" + "Host: " + server + "\r\n" + "Connection: close\r\n\r\n"); //GET request for server response.
    unsigned long timeout = millis();
    while (client2.available() == 0) 
    {
      if (millis() - timeout > 25000) //If nothing is available on server for 25 seconds, close the connection.
      { 
        return;
      }
    }
    while(client2.available())
    {
      String line = client2.readStringUntil('\r'); //Read the server response line by line..
      rcv+=line; //And store it in rcv.
    }

    String s2=rcv.substring((rcv.indexOf('[')),rcv.indexOf(']')); // Extract the line returned by JSON object.
    byte plant1 = byte(s2[2])-48;
    byte plant2 = byte(s2[6])-48;
    byte plant3 = byte(s2[10])-48;
    Serial.println (plant1);
    Serial.println (plant2);
    Serial.println (plant3);
    Serial.println (s2);
    if (plant1 == 1  && empty_reservoir == 0) {
        digitalWrite(53, LOW
        );
        delay(300);
         digitalWrite(30, LOW);
         delay(5000);
         digitalWrite(30, HIGH);
         delay(300);
         digitalWrite(53, HIGH);
         delay(500);
         Sending_To_DB_0_Status_pump(1);
         delay(500);
         plant1=0;
      }
      if (plant2 == 1  && empty_reservoir == 0) {
        digitalWrite(40, LOW);
        delay(300);
         digitalWrite(30, LOW);
         delay(5000);
         digitalWrite(30, HIGH);
         delay(300);
         digitalWrite(40, HIGH);
         delay(500);
         Sending_To_DB_0_Status_pump(2);
         delay(500);
         plant2=0; 
      }
      if (plant3 == 1  && empty_reservoir == 0) {
        digitalWrite(49, LOW);
        delay(300);
         digitalWrite(30, LOW);
         delay(5000);
         digitalWrite(30, HIGH);
         delay(300);
         digitalWrite(49, HIGH);
         delay(500);
         Sending_To_DB_0_Status_pump(3);
         delay(500);
         plant3=0;
      }
    
    client2.stop(); // Close the connection.
  }
  else
  {
    Serial.println("Connection failed 1");
  }
  //Serial.println("Received string: ");
  //Serial.println(rcv); //Display the server response.
  //Serial.println("END");
  


  
  //Serial.println(s2);

  
}

void Read_SoilWatch10(byte numberOfPlants) {
  int analogInPin[] = {A0, A1, A2};           // Analog input pin that the sensor output is attached to (white wire)
  int minADC = 0;                       // replace with min ADC value read in air
  int maxADC = 740;                     // replace with max ADC value read fully submerged in water 
  int moistureValue;
  byte mappedValue;

  if (numberOfPlants==3) {
    for (int i=0;i<3;i++) {
      moistureValue = analogRead(analogInPin[i]);
      mappedValue = map(moistureValue,minADC,maxADC, 0, 100); 
      Serial.print("Moisture soil value = " );
      Serial.println(mappedValue);
      humiditySoil[i]=mappedValue; 
      }
    }
  else if (numberOfPlants==2) {
    for (int i=0;i<2;i++) {
      moistureValue = analogRead(analogInPin[i]);
      mappedValue = map(moistureValue,minADC,maxADC, 0, 100); 
      Serial.print("Moisture soil value = " );
      Serial.println(mappedValue);
      humiditySoil[i]=mappedValue; 
      }
    }
  else if (numberOfPlants==1) {
    moistureValue = analogRead(analogInPin[0]);
    mappedValue = map(moistureValue,minADC,maxADC, 0, 100); 
    Serial.print("Moisture soil    value = " );
    Serial.println(mappedValue);
    humiditySoil[0]=mappedValue; 
    }
    Serial.println("");
 }

void Read_ML8511(byte numberOfPlants) {

  int analogInPin[] = {A15, A14, A13};           // Analog input pin that the sensor output is attached to (white wire)
  int moistureValue=0;
  
  if (numberOfPlants==3) {
    for (int i=0;i<3;i++) {
      moistureValue = analogRead(analogInPin[i]);
      Serial.print("Moisture sun value = " );
      Serial.println(moistureValue);
      intensityUV[i]=moistureValue; 
      }
    }
  else if (numberOfPlants==2) {
    for (int i=0;i<2;i++) {
      moistureValue = analogRead(analogInPin[i]);
      Serial.print("Moisture sun value = " );
      Serial.println(moistureValue);
      intensityUV[i]=moistureValue; 
      }
    }
  else if (numberOfPlants==1) {
    moistureValue = analogRead(analogInPin[0]);
    Serial.print("Moisture sun value = " );
    Serial.println(moistureValue);
    intensityUV[0]=moistureValue; 
    }
    
  Serial.println("");
 }

 void Read_water_level() {
    byte val = digitalRead(22); 
    Serial.println ("Water_level:");
    Serial.print(val);
    Serial.println ("");
    if (val == LOW) {
      empty_reservoir = 0;
    }
    if (val == HIGH) {
      empty_reservoir = 1;
    }
  }
