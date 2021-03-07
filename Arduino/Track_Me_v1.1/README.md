# Source Code for Arduino

	This code is designed to send hardware device location information and other information to a web server and get server information to the device.

## Guid Lines

	1. Set Arduino IDE as folows.
        Board : Arduino Nano
        Processer : ATmega328
	
	2. Change following setting in source code before uploading.
	
        // Set the settings before uploading
        #define DEFAULTROUTE 1 //This initialize default route for the device
        #define DEVICEID 1 //This initialize ID for the device
        static const String URL =  "http://sltctrackme.000webhostapp.com"; //This initialize URL of Web Application