<?php
include("DBInit.php");
/*if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} */

date_default_timezone_set("Asia/Colombo");


$date = date_create(date("Y-m-d H:i:s"));

$time =date_format(date_sub($date, date_interval_create_from_date_string('44 seconds')),"Y-m-d H:i:s");  //this line is add because of 000webhost new server has +48 seconds with default time zone.

$lat = floatval($_GET['lat']);
$lon = floatval($_GET['lon']);
$id = intval($_GET["id"]);
$speed = $_GET['speed'];
$route = $_GET['route'];


$extime = date_sub($date, date_interval_create_from_date_string('60 minutes'));

$extime = date_format($extime, "Y-m-d H:i:s");

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "DELETE From request WHERE Time <= '$extime';";
 //echo "delete from expire :";
 $conn->query($sql);
//print_r($conn->affected_rows) ;

$conn->close();



$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "UPDATE location SET Speed='".$speed."',Route=" . $route . ",Latitude=" . $lat . ", Longitude=" . $lon . ", Time='" . $time . "' WHERE ID=" . $id . ";";
// echo "update :";
 $conn->query($sql) ;
//print_r($conn->affected_rows);

$conn->close();

// echo $extime;
// echo "     " ;

$latd = $lat - 0.005;
$latu = $lat + 0.005;
$lond = $lon - 0.005;
$lonu = $lon + 0.005;

$conn = new mysqli($servername, $username, $password, $dbname);

/* if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

echo " <br>    " ;
print_r($latu) ;
echo "   <br>  " ;
print_r($latd) ;
echo "   <br>  " ;
print_r($lonu) ;
echo "   <br>  " ;
print_r($lond) ;

 */
 //echo "a";

$sql = "SELECT phone,UserID,Radius,ReqMode From sms WHERE ((" . $lon . " -Longitude)*(" . $lon . " -Longitude) + (" . $lat . " -Latitude)* (" . $lat . " -Latitude)) <= G_Distance;";

$result = $conn->query($sql);
 //echo "select sms :";
// print_r($result);
if ($result) {

  include_once("SMSGATEWAY.php");
  $Arry=[];

while($row = mysqli_fetch_assoc($result)){

      if ((4 == intval($row['ReqMode'])) || ($id == intval($row['ReqMode']))){
      $R=intval($row['Radius']);


      $body="Shuttle ";
      $body .= $id;
    $body  .= " is reach to ";
    $body  .= intval($R/1000);
    $body  .= " KM from You.";

    $phone = $row['phone'];
     //echo "c.....";
     SendSMS($body,$phone);

    array_push($Arry,$row['UserID']);
     }
 //

  }
    //echo "d......";
  $conn->close();

  $ID = implode("','", $Arry);
$sql = "DELETE From sms WHERE UserID IN ('".$ID."');";
 $conn = new mysqli($servername, $username, $password, $dbname);
  //echo "delete from sms :";
  $conn->query($sql);
//print_r($conn->affected_rows);

 //echo "e";

}





 $conn->close();


 $conn = new mysqli($servername, $username, $password, $dbname);

 $sql = "DELETE From request WHERE (Latitude BETWEEN " . $latd . " AND " . $latu . ") AND (Longitude BETWEEN " . $lond . " AND " . $lonu . ") AND ((ReqMode = " . $id. ") OR (ReqMode = 4));";
     //must be include sms table's userid row to delete

     // echo "delete because requet :";
      $conn->query($sql);
//print_r($conn->affected_rows);


if ($conn->affected_rows > 0) {
    echo "ON";
} else {
    echo "OFF";
}

 $conn->close();



//

?>
