<?php
 include("DBInit.php");
 $conn = new mysqli($servername, $username, $password, $dbname);
 $sql = "SELECT * FROM location WHERE ID = 1;";

 $result = $conn->query($sql);
   // $data = [];
 if ($result) {



        while ($row = mysqli_fetch_assoc($result)) {

            $ID="ID";
           // $ID="ID" .$row['ID'] ;







        $Lat=$row['Latitude'] ;
        $Lon=$row['Longitude'] ;
        $Time=$row['Time'] ;
        $Speed =  $row['Speed'] ;
        $Route =  $row['Route'] ;
        $nonsequential = array("Latitude"=>$Lat,"Longitude"=>$Lon, "Time"=>$Time,"Speed"=>$Speed,"Route"=>$Route);
          $data[$ID]=$nonsequential;


        }


 }





$conn->close();


    $s = json_encode($data) ;

       echo "data:{$s}\n\n";





flush();





?>