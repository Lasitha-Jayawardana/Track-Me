<?php

/* This php file contains the methods which access the ESMS web services
*Author: Izzath Dilshana
*
*
*=================sample code for sending SMS===========================================
*
*$session=createSession('','username','password','');
*sendMessages($session,'alias','message text',array('71xxxxxxx','71xxxxxxx'),1); // 1 for promotional messages, 0 for normal message
*closeSession($session);
*
*=======================================================================================
*
*
*==============sample code for retrieving SMS===========================================
*
*$session=createSession('','username','password','');
*getMessagesFromShortCode($session,"shortcode");
*getMessagesFromLongNumber($session,"longnum");
*closeSession($session);
*
*=======================================================================================
**/

//====================================ESMS WEB SERVCIES START	================================



//create soap client
function getClient()
{

    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient("http://smeapps.mobitel.lk:8585/EnterpriseSMSV3/EnterpriseSMSWS?wsdl");

    return $client;

}


//serviceTest
function serviceTest($id,$username,$password,$customer)
{

    $client = getClient();

    $user = new stdClass();
    $user->id = '';
    $user->username = $username;
    $user->password = $password;
    $user->customer = '';

    $serviceTest = new stdClass();
    $serviceTest->arg0 = $user;

    return $client->serviceTest($serviceTest);

}

//create session
function createSession($id,$username,$password,$customer)
{

    $client = getClient();

    $user = new stdClass();
    $user->id = $id;
    $user->username = $username;
    $user->password = $password;
    $user->customer = $customer;

    $createSession = new stdClass();
    $createSession->user = $user;

    $createSessionResponse = new stdClass();
    $createSessionResponse = $client->createSession($createSession);

    return $createSessionResponse->return;

}



//send SMS to recipients
function sendMessages($session,$alias,$message,$recipients,$messageType)
{
    $client=getClient();

    $smsMessage= new stdClass();
    $smsMessage->message=$message;
    $smsMessage->messageId="";
    $smsMessage->recipients=$recipients;
    $smsMessage->retries="";
    $smsMessage->sender=$alias;
    $smsMessage->messageType=$messageType;
    $smsMessage->sequenceNum="";
    $smsMessage->status="";
    $smsMessage->time="";
    $smsMessage->type="";
    $smsMessage->user="";

    $sendMessages = new stdClass();
    $sendMessages->session = $session;
    $sendMessages->smsMessage = $smsMessage;

    $sendMessagesResponse = new stdClass();
    $sendMessagesResponse = $client->sendMessages($sendMessages);

    return $sendMessagesResponse->return;
}

//send Unicoded SMS to recipients


//send Campaign SMS to recipients


//renew session



//close session
function closeSession($session)
{

    $client = getClient();

    $closeSession = new stdClass();
    $closeSession->session = $session;

    $client->closeSession($closeSession);

}

//retrieve messages from shortcode


//retrieve delivery report
function getDeliveryReports($session,$alias)
{

    $client = getClient();

    $getDeliveryReports = new stdClass();
    $getDeliveryReports->session = $session;
    $getDeliveryReports->alias = $alias;

    $getDeliveryReportsResponse = new stdClass();
    $getDeliveryReportsResponse->return = "";
    $getDeliveryReportsResponse = $client->getDeliveryReports($getDeliveryReports);

    if(property_exists($getDeliveryReportsResponse,'return'))
    return $getDeliveryReportsResponse->return;

    else return NULL;

}






//==================================ESMS WEB SERVICE END=============================================================



?>
