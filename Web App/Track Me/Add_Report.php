<?php

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        /*print_r($_POST);*/

        //if(isset($_POST['regsubmit'])){




              $b= trim($_POST['feedback']);
              $a=trim($_POST['UserID'] ) ;



            $query = "INSERT INTO report(UserID,Feedback)
    VALUES('$a','$b');
            ";




      include("DB.php");
      $add = new Database();

   $result = $add->Query($query) ;
         // }
    if ($result) {

       $msg['success']='Submitted successfully';
      echo json_encode($msg) ;

} else {
    $errno=$add->geterrno();
     $msg['errno']=$errno;

    echo json_encode($msg) ;

}


    }


   exit();


?>