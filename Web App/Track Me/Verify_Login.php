<?php session_start() ?>
<?php include_once("DB.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                   /* print_r($_SERVER['HTTP_HOST']);  */

          $a= trim($_POST['UserID']);

                $b= trim($_POST['password']);




      $check= new Database();

    $query = "SELECT * FROM user WHERE UserID = '$a' AND Password = '$b'";

   $result = $check->Query($query) ;


      if ($result) {

      if ( mysqli_num_rows($result)==1) {

          $msg['isvalid']="valid";

          $row=mysqli_fetch_array($result);

        /*  print_r($row) ;  */
            $_SESSION["Name"]=$row['Last'];
            $_SESSION["NameF"]=$row['First'];
            $_SESSION["UserID"]=$a;
            $_SESSION["phone"]=$row['Cont_No'];

            $msg['url'] =  'View.php';







      /*     header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/Reserve.php");
           die;
*/

        } else{
            $msg['isvalid']="invalid";
        }



       echo json_encode($msg) ;


} else {

    $errno=$check->geterrno();
      $msg['errno'] = $errno;

    echo json_encode($msg) ;


}




    }

      /* */
   exit();



?>