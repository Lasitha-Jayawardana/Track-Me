<?php

   include_once("ESMSWS.php");
    function SendSMS($body,$num){


       $session=createSession('','esmsusr_1o6r','3cikhtr','');
       //echo "fef";
  sendMessages($session,'SLT CAMPUS',$body,array($num),1); // 1 for promotional messages, 0 for normal message
 //echo "fmmmmmmef";
//echo $e;
closeSession($session);
    }




?>