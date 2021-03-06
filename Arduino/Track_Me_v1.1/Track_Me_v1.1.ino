#include <ArduinoJson.h>
#include <SoftwareSerial.h>
#include <MemoryFree.h>
#include <NMEAGPS.h>
//#include <TimerOne.h>
// bool ledState=false;


// Set the settings before uploading
#define DEFAULTROUTE 1 //This initialize default route for the device
#define DEVICEID 1 //This initialize ID for the device
static const String URL =  "http://sltctrackme.000webhostapp.com"; //This initialize URL of Web Application
const int Rate = 3000; //The frequency of http requests (mili seconds)
#define DEBOUNCE_TICKS 500 // Route switch debounce time(ms)
const long maxResponseTime = 5000; // Maximum response waiting time before restarting the device
const int maxerrcount = 5; //Maximum error count before restarting the device


//extern volatile unsigned long timer0_overflow_count;
volatile unsigned long keytick = 0; // record time of keypress

SoftwareSerial SIM800(A2, A1); // configure software serial port tx,rx
SoftwareSerial GPS(3, 7);// configure software serial port tx,rx

NMEAGPS gps;
gps_fix fix;

String totalResponse = "";
unsigned long last;
unsigned long lastReq;
int errcount = 0;
volatile int Route;

const int GSMErr = 9; //error pin
const int GPSErr = 8; //error pin

const int Bzz = 10; //Buzzer pin
const int GSMReset = 13; //GSMReset pin
const int RutSw = 2; //GSMReset pin
const int  ReqLed = 4;//Request Led pin
const int R2 = A4; //Route2 pin
const int R3 = A5; //Route3 pin
const int R4 = 11; //Route4 pin


void setup() {
  delay(3000);
  Serial.begin(9600);
  SIM800.begin(9600);
  GPS.begin(9600);

  Serial.println(F("Starting........"));
  digitalWrite(GSMReset, HIGH);
  digitalWrite(Bzz, LOW);
  pinMode(GSMReset, OUTPUT);
  pinMode(Bzz, OUTPUT);


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

  digitalWrite(ReqLed, LOW);
  pinMode(ReqLed, OUTPUT);
  pinMode(RutSw, INPUT_PULLUP);
  attachInterrupt(digitalPinToInterrupt(RutSw), SetRoute, LOW);




  pinMode(GSMErr, OUTPUT);
  pinMode(GPSErr, OUTPUT);

  delay(300);
  digitalWrite(GSMReset, LOW);
  Serial.print(F("1 Free memory : "));
  Serial.println(freeMemory());


  /*
    Timer1.initialize(500000);
    Timer1.detachInterrupt();
    ledState = false;
    digitalWrite(7, LOW);*/


  //digitalWrite(Bzz,HIGH);
  delay(3000);
  // digitalWrite(Bzz,LOW);

  SIM800.listen();

  //Beep();

  InitGSM();


  GPRSConnect();

  CreateRequest();





  Serial.print(F("2 Free momory : "));
  Serial.println(freeMemory());
}

void (*resetFunc)(void) = 0;

void Reset( ) {
  Serial.println(F("*********************Resetted*******************"));
  delay(1000);
  /*digitalWrite(GSMReset, HIGH);
    digitalWrite(Bzz, LOW);
    digitalWrite(ReqLed, LOW);
    delay(300);
    digitalWrite(GSMReset, LOW);
    errcount = 0;
    last = 0;
    lastReq = 0;*/
  resetFunc();
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






  /*digitalWrite(R3,HIGH);
    delay(1000);
    digitalWrite(R3,LOW);*/
}
void loop() {

  if (millis() - last > Rate) {

    Serial.print(F("2 begin Free momory :::::::::: "));
    Serial.println(freeMemory());



    Serial.println(F("location to be sent"));

    if (fix.valid.location) {
      errcount = 0;
      digitalWrite(GPSErr, LOW);
      String lat =  String(fix.latitude(), 5);
      String lon = String(fix.longitude(), 5);
      String Speed = String (fix.speed_kph(), 1);
      Serial.print(F("location : "));
      Serial.print(lat);
      Serial.print(F(" <> "));
      Serial.print(lon);
      Serial.print(F(" <> "));
      Serial.print(F("Speed : "));
      Serial.println(Speed);
      SIM800.listen();




      SendData(lat, lon, Speed);







    } else {
      if (errcount >= maxerrcount + 10) {
        Reset( );


      }
      errcount++;
      Serial.println(F("location invalid"));
      digitalWrite(GPSErr, HIGH);
    }




    last = millis();

  } else {

    GPS.listen();


    while (gps.available(GPS)) {
      fix = gps.read();
    }


  }









  // Serial.print(F("2 Free memory :::::::::::: "));
  //Serial.println(freeMemory());
}


bool CheckResponse( String resp) {

  totalResponse = "";
  unsigned long lasttime = millis() + maxResponseTime;
  //Serial.println( String(millis()) + " vs " + String(lasttime));


  while (totalResponse.indexOf(resp) < 0 && millis() < lasttime && totalResponse.indexOf("ERROR\r\n") < 0)
  {
    delay(100);
    ReadResponse();


  }

  //  Serial.println("11111111==" + String(totalResponse.indexOf(resp)));
  //Serial.println("2222222==" + String(totalResponse.indexOf("ERROR\r\n")));


  if (totalResponse.length() <= 0)
  {
    totalResponse = "NO RESPONSE";
    digitalWrite(GSMErr, HIGH);
    errcount++;


    Serial.print(F("check response Free momory : "));
    Serial.println(freeMemory());


    return false;

  } else if (totalResponse.indexOf(resp) < 0)
  {

    errcount++;


    totalResponse = "UNEXPECTED RESPONSE :  " + totalResponse ;
    digitalWrite(GSMErr, HIGH);

    Serial.print(F("check response Free momory : "));
    Serial.println(freeMemory());


    return false;
  } else {

    digitalWrite(GSMErr, LOW);
    errcount = 0;

    Serial.print(F("check response Free momory : "));
    Serial.println(freeMemory());

    return true;
  }




}



void ReadResponse() {


  while (SIM800.available()) {
    delay(10);

    totalResponse += char (SIM800.read()); //makes the string readString

  }
  //   Serial.print(F("In reading.... Free momory : "));
  // Serial.println(freeMemory());

}




void InitGSM() {
  Serial.println(F("GSMSetup Starting.."));
  SIM800.listen() ;

a:

  SIM800.println(F("AT"));
  delay(500);

  if (!CheckResponse("OK\r\n")) {
    Serial.print(F("GSM Module Not Found -> "));
    Serial.println(totalResponse);

    if (errcount >= maxerrcount) {
      Reset();
      return;
    }
    delay(500);
    goto a;


  }

  Serial.println(F("GSM Module Connected."));

  totalResponse = "";

  delay( 500);
  /*b:

    SIM800.println("ATE0");
    delay(500);

    if (!CheckResponse(maxResponseTime, "OK\r\n")) {
      Serial.println("EchoIni Fail -> " + totalResponse);
      delay(1000);

      if (errcount >= maxerrcount - 2) {
        Reset("gsm setup");
        return;
      }
      goto b;
    }

      Serial.println("EchoIni Succes ");


    totalResponse = "";

    delay(1500);

    c:
    SIM800.println("AT+CPIN?");
    delay(500);

    if (!CheckResponse(maxResponseTime, "+CPIN: READY\r\n\r\nOK\r\n" ) ) {
      Serial.println("Sim NotFound -> "  + totalResponse);

      if (errcount >= maxerrcount - 2) {
        Reset("gsm setup");
        return;
      }

      delay(1000);
      goto c;

    }
    Serial.println("Sim Found -> ");

    totalResponse = "";

    delay(1500);
    d:
    SIM800.println(F("AT+CIPSHUT"));
    delay(1000);

    if (!CheckResponse("OK\r\n") ) {

    Serial.print(F("Clear Initial Fail -> "));
    Serial.println(totalResponse);


      if (errcount >= maxerrcount) {
        Reset( );
        return;
      }
      delay(500);
      goto d;
    }
    Serial.println(F("All Initial Clear."));


    totalResponse = "";

  */
  delay(500);
e:
  SIM800.println(F("AT+CFUN=1"));
  delay(500);

  if (!CheckResponse( "OK\r\n") ) {
    Serial.print(F("Full Fuctions Activate Fail -> "));
    Serial.println(totalResponse);

    if (errcount >= maxerrcount - 2) {
      Reset( );
      return;
    }
    delay(500);
    goto e;
  }
  Serial.println(F("Full Function Activated."));


  totalResponse = "";


  delay( 500);

  CheckSignal();

  delay( 1000);

g:

  SIM800.println(F("AT+CGATT=1"));
  delay(1000);

  if (!CheckResponse(  "OK\r\n")) {
    Serial.print(F("GPRS Attach Fail -> "));
    Serial.println(totalResponse);

    if (errcount >= maxerrcount) {
      Reset();
      return;
    }
    delay(1000);
    goto g;

  }
  Serial.println(F("GPRS is Attached."));


  totalResponse = "";

  Serial.println(F("GSMInit Completed........"));


}


/*void GPRSBlink(void) {
  if (ledState == true) {
    ledState = false;
   digitalWrite(7, LOW);

  } else {
    ledState = true;
     digitalWrite(7, HIGH);

  }


  }
*/



void GPRSConnect() {
  Serial.println(F("GPRSConnect Starting......" ) );

a:
  delay(500);
  SIM800.println(F("AT+SAPBR=3,1,\"Contype\",\"GPRS\""));


  if (!CheckResponse( "OK\r\n")) {
    Serial.print(F("Set Contype GPRS Fail -> "));
    Serial.println(totalResponse);


    if (errcount >= maxerrcount) {
      Reset( );
      return;
    }
    delay(500);
    goto a;


  }

  Serial.println(F("Set Contype GPRS Success."));


  totalResponse = "";


b:
  delay(500);
  SIM800.println("AT+SAPBR=3,1,\"APN\",\"CMNET\"");


  if (!CheckResponse(  "OK\r\n")) {
    Serial.print(F("Set APN Fail -> "));
    Serial.println(totalResponse);


    if (errcount >= maxerrcount) {
      Reset( );
      return;
    }
    delay(500);
    goto b;


  }

  Serial.println(F("Set APN Success."));


  totalResponse = "";



  delay(500);
  ConGPRS();


  delay(500);
  Serial.println(F("InitGPRS Completed..........."));


}





void CheckSignal() {
  int e = 0;
a:
  // delay(2000);
  SIM800.println(F("AT+CSQ"));
  delay(1000);
  CheckResponse( "OK\r\n");

  int i = totalResponse.indexOf(",");


  if (totalResponse.indexOf("OK\r\n") < 0 || totalResponse.substring(i - 2, i).toInt() < 15 ) {



    Serial.print(F("Signal Poor -> "  ));
    Serial.println(totalResponse);


    if (e >= maxerrcount + 2 ) {
      e = 0;
      Reset( );

    }
    delay(1000);

    e++;
    goto a;
  }


  Serial.print(F("Signal strength -> "));
  Serial.println(totalResponse);


  totalResponse = "";


}


void ConGPRS() {

a:
  delay(500);
  SIM800.println(F("AT+SAPBR=1,1"));

  delay(500);
  if (!CheckResponse( "OK\r\n")) {

    /*Timer1.detachInterrupt();
      ledState = false;
      digitalWrite(7, LOW);*/


    Serial.print(F("GPRS Can't Connect -> "));
    Serial.println(totalResponse);


    delay(2000);



    if (errcount >= maxerrcount) {
      Reset( );


    }

    if (!totalResponse.indexOf("ERROR\r\n") < 0) {
      CheckSignal();
    }



    goto a;

  }

  //Timer1.attachInterrupt(GPRSBlink);

  Serial.println(F("GPRS Connected." ));


  totalResponse = "";
}




void CheckGPRS() {
  int ERR = 0;
a:
  delay(500);
  SIM800.println(F("AT+SAPBR=2,1"));

  delay(1000);
  if (CheckResponse( "OK\r\n")) {

    Serial.println(totalResponse);
    if (totalResponse.indexOf("+SAPBR: 1,1") < 0) {

      /*Timer1.detachInterrupt();
        ledState = false;
        digitalWrite(7, LOW);*/

      ConGPRS();
    } else {
      totalResponse = "";

      //Timer1.attachInterrupt(GPRSBlink);

      Serial.println(F("GPRS Already Connected."));
      return;
    }


  } else {
    /*Timer1.detachInterrupt();
      ledState = false;
      digitalWrite(7, LOW);*/
    ERR++;
    Serial.print (F("Can't CheckGPRS -> " ));
    Serial.println( totalResponse);

    delay(1000);



    if (ERR >= maxerrcount) {
      ERR = 0;
      Reset( );

    }

    goto a;

  }
  totalResponse = "";


}



void HTTPTerminate() {
  int err = 0;
a:

  SIM800.println(F("AT+HTTPTERM"));
  delay(1000);

  if (!CheckResponse( "OK\r\n")) {
    Serial.print(F("HTTPTERM Fail -> "));
    Serial.println(totalResponse);

    if (err >= maxerrcount) {
      err = 0;
      Reset() ;

    }
    err++;
    if (errcount >= maxerrcount - 2) {
      CheckGPRS() ;

    }
    delay(500);
    goto a;



  }

  Serial.println(F("HTTPTERMINATE Success."));


  totalResponse = "";
}


void CreateRequest() {
  SIM800.listen();
  Serial.println(F("HTTP Request Starting.........."));

  int err = 0;
a:

  SIM800.println(F("AT+HTTPINIT"));
  delay(1000);

  if (!CheckResponse( "OK\r\n")) {
    Serial.print(F("HTTPINIT Fail -> "));
    Serial.println(totalResponse);

    if (err >= maxerrcount) {
      err = 0;
      Reset() ;

    }
    err++;

    if (errcount >= maxerrcount - 2) {
      CheckGPRS() ;

    }


    delay(500);
    goto a;



  }

  Serial.println(F("HTTPINIT Success."));


  totalResponse = "";
  /*

    b:
    delay(500);
    SIM800.println(F("AT+HTTPPARA=\"CID\",1"));
    delay(1000);

    if (!CheckResponse(  "OK\r\n")) {
     Serial.print(F("HTTPPARA CID Fail -> "));
    Serial.println(totalResponse);




       if (errcount >= maxerrcount) {
         CheckGPRS() ;
        goto a;
      }
      goto b;



    }

     Serial.println(F("HTTPPARA Success."));

    totalResponse = "";


    c:
    delay(500);
    SIM800.println(F("AT+HTTPPARA=\"URL\",\"sltclasith.000webhostapp.com/receive.php\""));

    delay(1000);
    if (!CheckResponse( "OK\r\n")) {
      Serial.print(F("HTTP URL Fail -> "));
    Serial.println(totalResponse);



      if (errcount >= maxerrcount) {
       CheckGPRS();
        goto a;
      }

      goto c;



    }

    Serial.println(F("HTTP URL Success."));


    totalResponse = "";


    d:
    delay(500);
    SIM800.println(F("AT+HTTPPARA=\"CONTENT\",\"application/json\""));
    delay(500);

    if (!CheckResponse(  "OK\r\n")) {
    Serial.print(F("HTTP JSON Fail -> "));
    Serial.println(totalResponse);



      delay(1000);

      if (errcount >= maxerrcount) {
        CheckGPRS();
        goto a;
      }

      goto d;



    }

    Serial.println(F("HTTP Json Success."));


    totalResponse = "";
  */

  Serial.print(F("Request Completed :------- "));
  Serial.println(freeMemory());

  Serial.println(F("HTTP Request Complete......" ));
}








void SendData(String lat, String lon, String Speed ) {


  Serial.println(F("Sending Strating..........."));

  Serial.print(F("Sending Starting --------- : "));
  Serial.println(freeMemory());

  int err = 0;
  int err1 = 0;

a:

  SIM800.println("AT+HTTPPARA=\"URL\", \"" + URL + "/Upload.php?id=" + DEVICEID + "&lat=" + lat + "&lon=" + lon + "&route=" + Route + "&speed=" + Speed + "\"");

  //  delay(3000);

  if (!CheckResponse( "OK\r\n")) {
    Serial.print(F("Data Sending Fail -> "));
    Serial.println(totalResponse);

    if (err >= maxerrcount) {
      err = 0;
      Reset();
    }
    err++;

    if (errcount >= maxerrcount - 3) {
      // HTTPTerminate();
      CreateRequest();
    }
    goto a;






  }

  Serial.println(F("Data Sending..."));


  totalResponse = "";

  err = 0;

  //delay(1000);






b:

  SIM800.println(F("AT+HTTPACTION=0"));
  //delay(2000);

  if (!CheckResponse( "+HTTPACTION: 0,200")) {
    Serial.print(F("Data Submit Fail -> "));
    Serial.println( totalResponse);


    delay(2000);


    if (err1 >= maxerrcount) {
      err1 = 0;
      Reset();
    }
    err1++;


    if (errcount >= maxerrcount - 3) {
      HTTPTerminate();

      CreateRequest();
      goto a;

    }

    goto b;






  }

  Serial.println(F("Data Submit Success"));
  totalResponse = "";

  err1 = 0;
  //delay(500);
  SIM800.println(F("AT+HTTPREAD"));
  //delay(2000);

  Serial.println(F("Reading#####.."));

  if (CheckResponse("OK") && totalResponse.indexOf('N') > 0) {

    Request(1);
    // Serial.println("blub on");

  } else {
    //Serial.println("blub off");
    Request(0);
  }

  Serial.println(totalResponse);





  Serial.print(F("Memory free        ----------: "));
  Serial.println(freeMemory());

  totalResponse = "";

}

void Beep() {
  Serial.print(F("in beep......................"));
  for (int i = 0; i < 2; i++) {
    digitalWrite(Bzz, HIGH);
    delay(200);
    digitalWrite(Bzz, LOW);
    delay(200);
    digitalWrite(Bzz, HIGH);
    delay(400);
    digitalWrite(Bzz, LOW);
    delay(200);
  }

}

void Request(int i) {


  if (i == 1) {
    lastReq = millis();
    digitalWrite(ReqLed, HIGH);
    Beep();
  } else if (millis() - lastReq > 50000 && i == 0) {
    digitalWrite(ReqLed, LOW);
  }
}

