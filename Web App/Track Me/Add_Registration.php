<?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        /*print_r($_POST);*/

        //if(isset($_POST['regsubmit'])){




              $a= trim($_POST['NIC']);
              $b=trim($_POST['First'] ) ;

              $c=trim($_POST['Last']) ;
              $d= trim($_POST['Password']);
              $e=trim($_POST['Email']);
              $f= trim($_POST['Cont_No'] );
              $g= trim($_POST['Address'] );

              $h= trim($_POST['UserID']);
              $i=trim($_POST['I_No'] ) ;
              $j= trim($_POST['user'] ) ;


            $query = "INSERT INTO user(NIC,First,Last,Password,Email,Cont_No,Address,UserID,Index_No,User_Type)
    VALUES('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j');
            ";




      include("DB.php");
      $add = new Database();

   $result = $add->Query($query) ;
         // }
    if ($result) {

       $msg['success']='Registration successfully';
      echo json_encode($msg) ;

} else {
    $errno=$add->geterrno();
     $msg['errno']=$errno;

    echo json_encode($msg) ;

}


    }


   exit();


?>