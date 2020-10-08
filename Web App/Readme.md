# Web Application


## Guid Lines

	1. Setup your Database using "db.sql" file.
	
	2. Set Database details in "DBInit.php" file.
	
		$servername = "<ex : localhost>";
		$username = "<ex : id7253588_lasitha>";
		$password = "<psw>";
		$dbname = "<ex : id7253588_car_park>";
		
  	3. Enter Username & Password for SMS gateway in "SMSGATEWAY.php"
	
		$session=createSession('','<username>','<password>',''); 
		
	4. Log in to https://console.cloud.google.com/apis/credentials?authuser=0&project=track-me-277117&supportedpurview=project & get one year $300 free credit.
	Then Enable Java Script API & Direction API. Then get the API key.
  	
	5. Apply API key in "View.php" file.
		    
		src="https://maps.googleapis.com/maps/api/js?key=<APIKey>&callback=initMap">
	
## Tips
	Track Me is Currently Hosted in https://parking-project.000webhostapp.com
