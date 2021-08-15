#include <NMEAGPS.h>          //parsing the comma seperated values to be more human readable
#include <stdlib.h>           //the lib is needed for the floatToString() helper function
#include <NeoSWSerial.h>      //it is used instead of SoftwareSerial


// Set the settings before uploading
#define DEFAULTROUTE 1                      //This initialize default route for the device
#define DEVICEID 1                          //This initialize ID for the device
#define DEBOUNCE_TICKS 500                  // Route switch debounce time(ms)

//for sending data to a remote webserver
String ipAddress = "sltclasith.000webhostapp.com/Track Me";         //this is the ip address of our server - e.g. "123.123.123.123"
String APN = "dialogbb";                                            //check your Internet provider's website for the APN e.g. "dialogbb"

//SoftwareSerial variables
static const int gpsRX = 3, gpsTX = 7;
static const int simRX = A2, simTX = A1;
static const uint32_t gpsBaud = 9600;
static const uint32_t simBaud = 9600;


//helper variables for waitUntilResponse() function
String response = "";
static long maxResponseTime = 5000;
unsigned long lastTime;
unsigned long lastReq;

int refreshRate = 3;                     //The frequency of http requests (seconds)

//variables for a well-scheduled loop - in which the sendData() gets called every 15 secs (refresh rate)
unsigned long last;
unsigned long current;
unsigned long elapsed;

volatile unsigned long keytick = 0;       // record time of keypress
volatile int Route;                       //current route store in here

//if any error occurs with the gsm module or gps module, the corresponding LED will light up - until they don't get resolved
int GsmErrPin = 9; //error pin
int GpsErrPin = 8; //error pin

int maxNumberOfErrors = 5;               //if there is an error in sendLocation() function after the GPRS Connection is setup - and the number of errors exceeds 3 - the system reboots. (with the help of the reboot pin)
int errorsEncountered = 0;                //number of errors encountered after gprsConnection is set up - if exceeds the max number of errors the system reboots

boolean gprsConnectionSetup = false;
boolean reboot = false;


const int BzzPin = 10;          //Buzzer pin
const int GSMResetPin = 13;     //GSMReset pin
const int RutSwPin = 2;         //Route switch pin
const int  ReqLedPin = 4;       //Request Led pin
const int R2 = A4;              //Route2 pin
const int R3 = A5;              //Route3 pin
const int R4 = 11;              //Route4 pin


//SoftwareSerial instances
NeoSWSerial gpsPort(gpsRX, gpsTX);
NeoSWSerial Sim800l(simRX, simTX);

//GPS instance
NMEAGPS gps;
gps_fix fix;

//a helper function which converts a float to a string with a given precision
String floatToString(float x, byte precision = 2) {
  char tmp[50];
  dtostrf(x, 0, precision, tmp);
  return String(tmp);
}

void (*reset)(void) = 0;

void setup() {
  delay(3000);

  //init
  Serial.begin(9600);
  gpsPort.begin(gpsBaud);
  Sim800l.begin(simBaud);


  digitalWrite(GSMResetPin, HIGH);
  digitalWrite(BzzPin, LOW);
  pinMode(GSMResetPin, OUTPUT);
  pinMode(BzzPin, OUTPUT);

  pinMode(GsmErrPin, OUTPUT);
  pinMode(GpsErrPin, OUTPUT);

  if (digitalRead(R2)) {
    Route = 1;
    digitalWrite(R3, LOW);
    digitalWrite(R4, LOW);
  } else if (digitalRead(R3)) {
    Route = 2;
    digitalWrite(R2, LOW);
    digitalWrite(R4, LOW);
  } else if (digitalRead(R4)) {
    Route = 3;
    digitalWrite(R3, LOW);
    digitalWrite(R2, LOW);
  } else {
    Route = DEFAULTROUTE;                     //this is only point to change route
    digitalWrite(R4, HIGH);
    digitalWrite(R2, LOW);
    digitalWrite(R3, LOW);
  }

  pinMode(R2, OUTPUT);
  pinMode(R3, OUTPUT);
  pinMode(R4, OUTPUT);

  digitalWrite(ReqLedPin, LOW);
  pinMode(ReqLedPin, OUTPUT);
  pinMode(RutSwPin, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(RutSwPin), SetRoute, LOW);      // route switch attach as interrupt
  delay(300);
  digitalWrite(GSMResetPin, LOW);

  Sim800l.write(27); //Clears buffer for safety

  Serial.println(F("Beginning..."));
  delay(15000);                         //Waiting for Sim800L to get signal

  Sim800l.listen();                     //The GSM module and GPS module can't communicate with the arduino board at once - so they need to get focus once we need them
  setupGPRSConnection();                //Enable the internet connection to the SIM card
  Serial.println(F("Connection is setupted"));

  gpsPort.listen();

  last = millis();

}



void loop() {

  current = millis();
  elapsed += current - last;
  last = current;

  while (gps.available(gpsPort)) {
    fix = gps.read();
  }
  if (elapsed >= (refreshRate * 1000)) {
    sendData();
    elapsed -= (refreshRate * 1000);
  }

  if ((gps.statistics.chars < 10)) {
    //no gps detected (maybe wiring)
    Serial.println(F("NO GPS DETECTED OR BEFORE FIRST HTTP REQUEST"));
    delay(3000);
  }

  if (reboot) {
    Serial.println(F("Reseted......"));
    reset();
  }

}

void SetRoute() {
  if (millis() - DEBOUNCE_TICKS > keytick) {
    Serial.println(F("In set Route"));
    switch (Route) {
      case 1:
        digitalWrite(R3, HIGH);
        digitalWrite(R2, LOW);
        digitalWrite(R4, LOW);
        Route = 2;
        break;
      case 2:
        digitalWrite(R4, HIGH);
        digitalWrite(R2, LOW);
        digitalWrite(R3, LOW);
        Route = 3;
        break;
      case 3:
        digitalWrite(R2, HIGH);
        digitalWrite(R3, LOW);
        digitalWrite(R4, LOW);
        Route = 1;
        break;
    }
    Serial.println(Route);
    keytick = millis();
  }
}

void sendData() {
  Serial.println(F("Ready to send .."));
  if (fix.valid.location) {
    digitalWrite(GpsErrPin, LOW);
    String lat = floatToString(fix.latitude(), 5);
    String lon = floatToString(fix.longitude(), 5);
    String Speed = String (fix.speed_kph(), 1);
    Serial.print(F("location : "));
    Serial.print(lat);
    Serial.print(F(" <> "));
    Serial.print(lon);
    Serial.print(F(" <> "));
    Serial.print(F("Speed : "));
    Serial.println(Speed);
    sendLocation(lat, lon, Speed);
  } else {
    digitalWrite(GpsErrPin, HIGH);
  }
}

void setupGPRSConnection() {
  Sim800l.println(F("AT+SAPBR=3,1,\"Contype\",\"GPRS\""));    //Connection type: GPRS
  waitUntilResponse("OK");
  Sim800l.println("AT+SAPBR=3,1,\"APN\",\"" + APN + "\"");    //We need to set the APN which our internet provider gives us
  waitUntilResponse("OK");
  Sim800l.println(F("AT+SAPBR=1,1"));                         //Enable the GPRS
  waitUntilResponse("OK");
  Sim800l.println(F("AT+HTTPINIT"));                          //Enabling HTTP mode
  waitUntilResponse("OK");
  gprsConnectionSetup = true;
}



//the function - which is responsible for sending data to the webserver
void sendLocation(String lat, String lon, String Speed) {
  Sim800l.listen();
  //The line below sets the URL we want to connect to
  Sim800l.println("AT+HTTPPARA=\"URL\", \"http://" + ipAddress +  "/Upload.php?id=" + DEVICEID + "&lat=" + lat + "&lon=" + lon + "&route=" + Route + "&speed=" + Speed + "\"");

  // Sim800l.println("AT+HTTPPARA=\"URL\", \"http://postman-echo.com/get?foo1=bar1&foo2=bar2\"");         //for testing purposes

  waitUntilResponse("OK");

  //GO
  Sim800l.println(F("AT+HTTPACTION=0"));
  waitUntilResponse("200");
  Serial.println(F("Location sent"));

  Sim800l.println(F("AT+HTTPREAD"));
  Serial.println(F("Reading response from server #####.."));

  waitUntilResponse("OK");
  if (response.indexOf('N') > 0) {       // check whether ON or OFF key arived
    Request(1);
    Serial.println(F("Request blub on"));
  } else {
    Serial.println(F("Request blub off"));
    Request(0);
  }
  Serial.println(response);
  gpsPort.listen();
}



void Request(int i) {
  if (i == 1) {
    lastReq = millis();
    digitalWrite(ReqLedPin, HIGH);
    Beep();
  } else if (millis() - lastReq > 50000 && i == 0) {
    digitalWrite(ReqLedPin, LOW);
  }
}

void Beep() {
  Serial.print(F("in beep......................"));
  for (int i = 0; i < 2; i++) {
    digitalWrite(BzzPin, HIGH);
    delay(200);
    digitalWrite(BzzPin, LOW);
    delay(200);
    digitalWrite(BzzPin, HIGH);
    delay(400);
    digitalWrite(BzzPin, LOW);
    delay(200);
  }
}


//ERROR handler - exits if error arises or a given time exceeds with no answer - or when everything is OK
void waitUntilResponse(String resp) {
  lastTime = millis();
  response = "";
  String totalResponse = "";
  while (response.indexOf(resp) < 0 && millis() - lastTime < maxResponseTime)
  {
    readResponse();
    totalResponse = totalResponse + response;
    Serial.println(response);
  }
  if (totalResponse.length() <= 0)
  {
    Serial.println(F("NO RESPONSE"));
    digitalWrite(GsmErrPin, HIGH);
    if (gprsConnectionSetup == true) {
      Serial.println("error");
      errorsEncountered++;
    }
  }
  else if (response.indexOf(resp) < 0)
  {
    if (gprsConnectionSetup == true) {
      Serial.println("error");
      errorsEncountered++;
    }
    Serial.println(F("UNEXPECTED RESPONSE"));
    Serial.println(totalResponse);
    digitalWrite(GsmErrPin, HIGH);
  } else {
    Serial.println(F("SUCCESSFUL"));
    digitalWrite(GsmErrPin, LOW);
    errorsEncountered = 0;
  }

  //if there are more errors or equal than previously set ==> reboot!
  if (errorsEncountered >= maxNumberOfErrors) {
    reboot = true;
  }
  response = totalResponse;
}

void readResponse() {
  response = "";
  while (response.length() <= 0 || !response.endsWith("\n"))
  {
    tryToRead();
    if (millis() - lastTime > maxResponseTime)
    {
      return;
    }
  }
}

void tryToRead() {
  while (Sim800l.available()) {
    char c = Sim800l.read();          //gets one byte from serial buffer
    response += c;                    //makes the string readString
  }
}
