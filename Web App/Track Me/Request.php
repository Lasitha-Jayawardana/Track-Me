<?php
    header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include("DBInit.php");
$conn = new mysqli($servername, $username, $password, $dbname);  
date_default_timezone_set("Asia/Colombo");





 $time = date("Y-m-d H:i:s");



 $msg['radius']= 0;
if ($_GET['type'] == "State"){
    $UID =$_GET['UID'];

   $sql = "SELECT * FROM sms RIGHT JOIN request ON sms.UserID = request.UserID WHERE request.UserID = '$UID';";

   $result= $conn->query($sql);

  if ($conn->affected_rows > 0) {


          $msg['text']="Cancel Request";

          $row=mysqli_fetch_array($result);

        /*  print_r($row) ;  */
            $msg['lat']= $row['Latitude'];
           $msg['lon']= $row['Longitude'];
              $msg['radius']= $row['Radius'];


} else {
    $msg['text']="Send Request";

}
$conn->close();

    echo json_encode($msg) ;



} else if ($_GET['type'] == "Cancel Request") {
         $UID =$_GET['UID'];

$sql = "DELETE From request WHERE UserID = '$UID';";
 //must be delete sms table
$conn->query($sql);

//if ($conn->affected_rows > 0) {
    $msg['text']="Send Request";
//} else {
 //   echo "Cancel Request";
//}

$conn->close();
   echo json_encode($msg) ;



} else if ($_GET['type'] == "Send Request") {

         $lat= round(floatval($_GET['lat']),5);
            $lon=round(floatval($_GET['lon']),5);
     $UID =$_GET['UID'];
     $mode =$_GET['mode'];
      $phone = $_GET['phone'];


       $sql =  "REPLACE INTO request(Latitude,Longitude,Time,UserID,ReqMode)
     VALUES('$lat','$lon','$time','$UID','$mode');";


       if (intval($_GET['sms'])>0){





       $Radius = (round(floatval($_GET['radius']),5));
       $G_Distance=  round(floatval(($Radius*$Radius)/10000),5);
        $Radius = $Radius*1000;
             $sql .=  "REPLACE INTO sms(Latitude,Longitude,Radius,UserID,G_Distance,phone,ReqMode)
     VALUES('$lat','$lon','$Radius','$UID','$G_Distance','$phone','$mode');";

   }


 

if ($conn->multi_query($sql)) {

    $msg['text']= "Cancel Request";
    if (isset($Radius)){
        $msg['radius']= $Radius;
    }

} else {

     $msg['text']= "Send Request";
}

$conn->close();


       echo json_encode($msg) ;
}











?>