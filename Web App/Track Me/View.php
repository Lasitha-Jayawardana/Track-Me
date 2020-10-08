<?php session_start() ?>
<?php
    if (empty($_SESSION["UserID"]) or empty($_SESSION["NameF"])){
        session_unset();
 session_destroy  ();
    header('location:index.html');

       }

       ?>
 <!DOCTYPE HTML>

<html>

<head>
    <title>Track Me</title>

     <meta name="viewport" content="width=device-width, initial-scale=1">
     	<link rel="stylesheet" href="css/b.css">
        <link rel="stylesheet" href="fontawesome-free-5.3.1-web/css/all.css">
        <link href="dist/macOSNotif.min.css" rel="stylesheet"/>

    <style>

      /* Customize the label (the container) */
.contain {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  margin-left: 35px;
      line-height: 20px;
}

/* Hide the browser's default checkbox */
.contain input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.contain:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.contain input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.contain input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.contain .checkmark:after {
  left: 6px;
    top: 3px;
    width: 6px;
    height: 12px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
/* ............................... */
       .radiomark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.contain:hover input ~ .radiomark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.contain input:checked ~ .radiomark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.radiomark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.contain input:checked ~ .radiomark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.contain .radiomark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;

}

      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 90%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .floating-panel {
        position: absolute;
        bottom :10px;
        z-index: 5;
        background-color: #fff;

        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;


      }

      #topbar{
         width: 100%;
        line-height: 28px;
        padding: 5px;
        color: aliceblue;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
font-size: 1rem;

      }

      .succ{

    background-image: linear-gradient(to bottom,#00A300 0,#339266 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFE85B54', endColorstr='#FFB22520', GradientType=0);
    border-color: #00A300;
    box-shadow: inset 0 1px 0 rgba(242,164,162,.6), 0 1px 2px rgba(0,0,0,.05);


    }
       .unsucc{

    background-image: linear-gradient(to bottom,#CC0000 0,#910F0B 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#CC0000', endColorstr='#A30000', GradientType=0);
    border-color: #00A300;
    box-shadow: inset 0 1px 0 rgba(242,164,162,.6), 0 1px 2px rgba(0,0,0,.05);

    }
    .boxshadow{
    border: 1px solid #E3E9ED;
    border-radius: 3px;

    box-shadow: 1px 1px 3px rgba(11,34,57,0.2);

}
  .map-icon-label .map-icon {
    font-size: 24px;
    color: #FFFFFF;
    line-height: 48px;
    text-align: center;
    white-space: nowrap;

}
.macOSNotif_Outer{
   width: 316px;

 }
 .macOSNotif_Container{
    width: 299px;

 }
  .macOSNotif_Text {
     width: 191px;

  }
    .drpdwn{
       background:transparent;border:none;line-height: 28px;
        padding: 5px;
        color: aliceblue;
        font-family: -apple-system,BlinkMacSystemFont,; font-size: inherit;"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
font-size: 1rem;

  }
  .option{

      color: black;
  }
      .floating-button {
  height: 40px;
  border: solid 3px #CCCCCC;
  background: #5bb3c1;
  width: 100px;
  line-height: 32px;
  -webkit-transform: rotate(-90deg);
  font-weight: 600;
  color: white;
  transform: rotate(-90deg);
  -ms-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
  text-align: center;
  font-size: 17px;
  position: fixed;
  left: -40px;

  font-family: "Roboto", helvetica, arial, sans-serif;
  z-index: 999;
}
    </style>

</head>

<body onload="Request('State');">
   <div >
  <button onclick="window.location.href = 'Hint.html';"  class="floating-button" style=" top: 200px;" >Support</button>
  <button onclick="window.location.href = 'Report.php';" class="floating-button" style=" top: 300px;" >Feedback</button>
  <button onclick="window.location.href = 'About Us.php';" class="floating-button" style=" top: 400px;">About Us</button>

  </div>
 <div id="topbar" class="unsucc"><span id="toptext">Sync Delay  </span>
        <select id="ShuttleNum" onchange="ShuttleSelect()" class="drpdwn">
     <option  selected="selected" class="option" value="SSE1.php">Shuttle 1 </option>
                                <option class="option" value="SSE2.php">Shuttle 2 </option>
                                 <option class="option" value="SSE3.php">Shuttle 3 </option>  \
                                 </select><span style="float: right;padding-right: 6px;

" id="sync"> : Disconnected</span></div>






   <div id="map"></div>

    <button id="mylocation" class="boxshadow floating-panel" style="font-size: smaller;right: 10px; z-index: 19999998;background-color: #880e4f !important;
color: #fff !important;box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);

margin: .175rem;

border: 0;

border-radius: .725rem;font-family: Roboto,sans-serif;fo" onclick="getLocation();">My Location</button>

   <button id="request" class="boxshadow floating-panel" style="font-size: smaller;left: 10px;z-index: 19999998;background-color: #880e4f !important;
color: #fff !important;box-shadow: 0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);

margin: .175rem;

border: 0;

border-radius: .725rem;font-family: Roboto,sans-serif;visibility: hidden; " onclick="Request('');">Send Request</button>

                                                                                        <!--Request('')-->


    <!-- model -->
   <div class="modal fade" style="z-index: 19999999;" id="sendReq" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
       <h4 class="modal-title custom_align" id="Heading">Send Request</h4> <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
           <span class="fas fa-times" aria-hidden="true"></span></button>

      </div>
          <div class="modal-body">

          Make Sure that Your Location is near less than 400m to route & also sure that Marker is shown the Correct Position!.


             <div style="margin-bottom: 10px;margin-top: 10px;">
                 <label class="contain">Shuttle 1
  <input type="radio" checked="checked" name="reqMode" value="1" onclick="ReqMode=1;">
  <span class="radiomark"></span>
</label>
<label class="contain">Shuttle 2
  <input type="radio" name="reqMode" value="2" onclick="ReqMode=2;">
  <span class="radiomark"></span>
</label>
<label class="contain">Shuttle 3
  <input type="radio"  name="reqMode" value="3" onclick="ReqMode=3;">
  <span class="radiomark"></span>
</label>
<label class="contain">First Arrival
  <input type="radio" name="reqMode" value="4" onclick="ReqMode=4;">
  <span class="radiomark"></span>
</label>
             </div>

                                             <hr style="width: 100%; height: 2px">
         <div>
          <label id="smsAlert" style="margin-left: 1px;" class="contain">SMS Alert
  <input id="smsCheckBox" type="checkbox">
  <span class="checkmark"></span>
</label>
<div id="smsInfo" style="display: none;">
<span style="margin-left: 10px;">Before <input id="radius" name="" min=1 style="margin-left: 5px;
    width: 40px;
    height: 20px;" type="number" step=""> KM</span>

       <div style="margin-bottom: 8px; margin-top: 8px; display: block;" class="alert alert-warning" id=""><span class="fas fa-exclamation-triangle"></span>
        &nbsp;Shuttle reach to above KM from your location,it will inform you by SMS.
       </div>
     </div>
        </div>





      </div>
        <div class="modal-footer ">
        <button onclick="Request('Yes')" type="button" class="btn btn-success" ><span class="fas fa-check"></span>&nbsp;Yes</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"><span class="fas fa-times"></span>&nbsp;No</button>
      </div>

        </div>
    <!-- /.modal-content -->
  </div>
      <!-- /.modal-dialog -->
    </div>





    <script src="js/jq.js" ></script>
     <script src="dist/macOSNotif.min.js"></script>
   <script src="js/b.js"></script>
<script>
 $("#smsInfo").css("display", "none") ;
        $(document).ready(function(){

        $("#smsAlert").click(function(){

            if($("#smsCheckBox").is(":checked")){

               $("#smsInfo").css("display", "block") ;
                 smsEnable=1;
            }

            else if($("#smsCheckBox").is(":not(:checked)")){

                 $("#smsInfo").css("display", "none") ;
                  smsEnable=0;

            }

        });

    });





  function showmodel(){
 $("#sendReq").modal();

}




 var SSEScript="SSE1.php";
 var SMSnotifi=0;
 var infowindow=null;
var MyLocation = null;
var BusLocation =[null];
 var circle = null;
  var mylat=null;
var mylon=null;
 var watchID=null;
 var ReqMode=1;
 var smsEnable=0;
  var source=null;
  var Route=1;
  var directionsService;
  var directionsDisplay  ;
  var b =false;
function initMap() {
    ShuttleSelect();
  var TempLocation = {lat: 6.83929, lng: 80.092948};

    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer(
    {

    suppressMarkers: true
    }
  );


  map = new google.maps.Map(document.getElementById('map'),
    {
    zoom: 15,
    center: TempLocation,

          zoomControl: true,
          zoomControlOptions: {
              position: google.maps.ControlPosition.RIGHT_CENTER
          },
          scaleControl: true,
          streetViewControl: true,
          streetViewControlOptions: {
              position: google.maps.ControlPosition.LEFT_TOP
          },
          fullscreenControl: true
    }
  );

  directionsDisplay.setMap(map);
  //calculateAndDisplayRoute(directionsService, directionsDisplay);

}




function calculateAndDisplayRoute(directionsService, directionsDisplay) {
          var waypts;
          var origin;
          var destination;
       console.log("in caculatedis") ;
       console.log(Route) ;
     switch (Route){
         case 1:
                       //console.log("in calculate display 1") ;
         begin ="Sri Lanka Technological Campus (SLTC), Ingiriya Road, Padukka";
         over = "Trace Expert City, AC19, Colombo 01000";
         waypts =[
    {
    location: "6th Mile Post Bus Stop, High Level Road, Colombo 00500",
    stopover: true
    }
  ];
    console.log("in waypoint 1");
         break;
          case 2:

          begin ="Sri Lanka Technological Campus (SLTC), Ingiriya Road, Padukka";
         over = "Trace Expert City, AC19, Colombo 01000";
         waypts =[
    {
     location: "6th Mile Post Bus Stop, High Level Road, Colombo 00500",
    stopover: true
    }
  ];
  console.log("in waypoint 2") ;
   break;
         case 3:

          begin ="Sri Lanka Technological Campus (SLTC), Ingiriya Road, Padukka";
         over = "Trace Expert City, AC19, Colombo 01000";
              waypts =[
    {
    location: "Malabe Junction Clock Tower, B263, Malabe",
    stopover: true
    },
     {
    location: "Kolonnawa",
    stopover: true
    }
  ];
  console.log("in waypoint 3") ;
         break;

     }





  directionsService.route(
    {
    origin: begin,
    destination: over,
    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: "DRIVING"
    }, function(response, status) {
      if (status === 'OK') {
        directionsDisplay.setDirections(response);

      } else {
        window.alert('Directions request failed due to ' + status);
      }
    }
  );
}






function getLocation() {

  if (navigator.geolocation) {

      var option={enableHighAccuracy: true};
    watchID =  navigator.geolocation.watchPosition(showPosition,onError,option);




  } else {
    //x.innerHTML = "Geolocation is not supported by this browser.";
    alert("Geolocation is not supported by this browser.");
  }
}


function onError(error){

}

function Circle(radius,cpoint){


//var newPoint = {lat: mylat, lng: mylon};
circle = new google.maps.Circle({
    center: cpoint,
    radius: radius,
    map: map,
    fillColor: "#0000FF",
    fillOpacity: 0.1,
    strokeColor: "#FFFFFF",
    strokeOpacity: 1,
    strokeWeight: 2
   } );
}

function ShuttleSelect(){
    obj = document.getElementById("ShuttleNum");
             console.log("shuttle obj select") ;
     console.log(obj.selectedIndex);
    // Route  = obj.selectedIndex + 1;
      console.log(obj.options[obj.selectedIndex].value);
      SSEScript =  obj.options[obj.selectedIndex].value;
      source.close();
       SSE();
       b=true;
 }

function showPosition(position) {
         console.log(position);

  mylat=position.coords.latitude;
  mylon=position.coords.longitude;




  var newPoint = {lat: mylat, lng: mylon};



  if (MyLocation) {

    // Marker already created - Move it

    MyLocation.setPosition(newPoint);
   //  circle.setCenter(newPoint);



         document.getElementById("request").style.visibility = "visible";

  }
  else {


    // Marker does not exist - Create it
    MyLocation = new google.maps.Marker(
      {
      position: newPoint,
      map: map,
      draggable:true,
      title: 'My Position',
      icon: {
        url: "http://maps.google.com/mapfiles/ms/micons/man.png"
        }

        /*   icon: {
           url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/library_maps.png"
     }

     */
      }
    );



   /*  circle = new google.maps.Circle({
    center:newPoint,
    radius: 500,
    map: map,
    fillColor: "#0000FF",
    fillOpacity: 0.1,
    strokeColor: "#FFFFFF",
    strokeOpacity: 1,
    strokeWeight: 2
   } );      */



     google.maps.event.addListener(MyLocation, 'dragend', function (evt) {
    mylat = evt.latLng.lat();
    mylon =  evt.latLng.lng();

       // var Point = {lat: mylat, lng: mylon};
    //circle.setCenter(Point);

   navigator.geolocation.clearWatch(watchID);



});

    MyLocation.setAnimation(google.maps.Animation.BOUNCE);
    map.setCenter(newPoint);
//Circle();
  }

}




 function SSE(){

if(typeof(EventSource) !== "undefined") {


    source = new EventSource(SSEScript);
  source.onmessage = function(event) {

    //console.log(event.data) ;


    var obj = JSON.parse(event.data);

    //console.log(getMinutesBetweenDates(new Date(obj.ID1.Time),new Date())) ; .ID1.Longitude
    //console.log(obj) ;
    var diff = getMinutesBetweenDates(new Date(obj.ID.Time), new Date());




    if (diff>1.5) {
        if (b){
            switch (SSEScript){
                case "SSE1.php":
                   Route = 1;

                break;
                case "SSE2.php":
                    Route = 2;

                break;
                case "SSE3.php":
                    Route = 3;

                break;

            }
             calculateAndDisplayRoute(directionsService, directionsDisplay);
              b=false;
            }
      var element = document.getElementById("topbar");
      element.classList.remove("succ");
      element.classList.add("unsucc");

      document.getElementById("toptext").innerHTML = "";
      document.getElementById("sync").innerHTML = "Disconnected";

        if (infowindow){infowindow.close();}
    } else {
             if (Route != parseInt(obj.ID.Route,10)) {
          Route =  parseInt(obj.ID.Route,10);
          console.log("route ID") ;
          console.log(Route) ;

           calculateAndDisplayRoute(directionsService, directionsDisplay);
    }
      var element = document.getElementById("topbar");
      element.classList.remove("unsucc");
      element.classList.add("succ");
      document.getElementById("toptext").innerHTML = "Sync Delay  ";
      document.getElementById("sync").innerHTML = Math.round(diff*60) + "S";
      if (infowindow){infowindow.setContent("Speed : " + obj.ID.Speed + " Km/h");}

    }

        var Blat=  parseFloat(obj.ID.Latitude) ;
        var Blon =  parseFloat(obj.ID.Longitude)  ;

    var newPoint = {lat: Blat, lng: Blon};
   // console.log(newPoint) ;
    SetMarker(0, newPoint);
    /* switch SSEScript{
         case "SSE1.php":
               SetMarker(1, newPoint);
         break;
         case "SSE2.php":
               SetMarker(2, newPoint);
         break;
         case "SSE3.php":
               SetMarker(3, newPoint);
         break;
     }*/

    if (circle && SMSnotifi==0){
   SMSnotification(Blat,Blon);
    }



  };



} else {

  alert ("Auto Sync Fail.");


}
  }
 function SMSnotification(Blat,Blon){
                   var rad = parseFloat((Blat-mylat)*(Blat-mylat) + (Blon - mylon)*(Blon - mylon));
                   var Cradius = parseFloat(circle.getRadius()/100000) ;
                  // console.log(Cradius);

                   if (rad <= parseFloat(Cradius*Cradius)){
                      SMSnotifi=1;
                   macOSNotif({sounds:true,
          title:'Track Me',
    subtitle:'Shuttle is reach to ' + Cradius*100 + ' KM from your location.',
      btn1Dismiss: true,
    btn2Text:null
    });
                   }
               // console.log(rad);
                //console.log(Cradius);
                //console.log(Cradius*Cradius/100.0);
                //console.log();
                //console.log();

            }


function getMinutesBetweenDates(startDate, endDate) {
  var diff =endDate.getTime() - startDate.getTime() ;
  if (diff < 0 ){
    return 0;
  }else{
     return (diff / 60000);
  }

}



function SetMarker(index, Point) {


  if (BusLocation[index]) {
    // Marker already created - Move it
    BusLocation[index].setPosition(Point);
  }
  else {

       infowindow = new google.maps.InfoWindow({
          content: "Speed : --",
           pixelOffset: new google.maps.Size(0, -20)
        });
    // Marker does not exist - Create it
    BusLocation[index] = new google.maps.Marker(
      {
      position: Point,
      map: map,
      title: 'Shuttle Position',
      icon: {
        url: "Image/bus.png"
        }


      }
    );

     infowindow.open(map, BusLocation[index]);

    BusLocation[index].addListener('click', function() {
          infowindow.open(map, BusLocation[index]);
        });

    BusLocation[index].setAnimation(google.maps.Animation.BOUNCE);
    map.setCenter(Point);

  }


}





 function  Request(type) {

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

var s=JSON.parse(this.responseText);



      document.getElementById("request").innerHTML = s.text;

      if (s.text == "Cancel Request"){


                     if(!MyLocation){
                           var pos={latitude:  parseFloat(s.lat), longitude:  parseFloat(s.lon)};
                      var newpoint = {coords: pos};
                      console.log(newpoint) ;
                        showPosition(newpoint);


                     }

console.log(s);

  var cpoint = {lat: mylat, lng: mylon};


var r = parseFloat(s.radius);


                        if (r>0){




Circle(r,cpoint);
                        }
                        document.getElementById("mylocation").style.visibility = "hidden";
                        document.getElementById("request").style.visibility = "visible";



           MyLocation.setDraggable(false);

      }else{
          if (circle){
              circle.setMap(null);
               SMSnotifi=0;
               circle=null;
          }
           document.getElementById("mylocation").style.visibility = "visible";
           if (MyLocation){ MyLocation.setDraggable(true);}


      }

    }
  };


if (type=="State"){



                xhttp.open("GET", "Request.php?type=State&UID=<?php echo $_SESSION["UserID"]; ?>" , true);
                 xhttp.send();

 } else if(document.getElementById("request").innerHTML=="Cancel Request"){


             navigator.geolocation.clearWatch(watchID);

                    var UID= "<?php echo $_SESSION["UserID"]; ?>" ;
       xhttp.open("GET", "Request.php?UID=" + UID + "&type=Cancel Request", true);
        xhttp.send();



 } else if(type == "Yes"){
     $("#sendReq").modal('hide');
         //if (confirm("Make Sure that Your Location is near less than 400m to Root & also Marker is shown Correct Position!.")) {

   navigator.geolocation.clearWatch(watchID);


       xhttp.open("GET", "Request.php?lat=" + mylat + "&lon=" + mylon + "&type=" + document.getElementById("request").innerHTML + "&UID=<?php echo $_SESSION["UserID"]; ?>&mode=" + ReqMode + "&sms=" + smsEnable + "&radius=" + document.getElementById("radius").value  + "&phone=<?php echo $_SESSION["phone"]; ?>", true);
       xhttp.send();

     //  }

 } else{

      showmodel();
 }




     }

 SSE();


</script>

 <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCNaimmQUOu1U-kWaWHj97vX8LrFpUAUQs&callback=initMap">
    </script>
</body>
</html>
