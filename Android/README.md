# Android Application

This Android app is designed to easily open the Track Me web app link using the web browser on the mobile phone.

## Guid Lines

	1. Open in Android Studio.
	
	2. Change following url in MainActivity.java file as the Track Me web url.
	
 		Intent browsIntent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://sltctrackme.000webhostapp.com"));