<?php

include_once 'bootstrap.php';
##################### the variables
$spId = "2340110000470";
$serviceId = "234012000003187";
$ts = date('YmdHis');
$OA = isset($_REQUEST['endUserIdentifier']) ? $_REQUEST['endUserIdentifier'] : '2347032700097' ;  //mobile no of the service originator
$FA = isset($_REQUEST['endUserIdentifier']) ? $_REQUEST['endUserIdentifier'] : '2347032700097' ; //mobile no of the charged party
$password ="Huawei123";
$passwordhash = $spId . $password . $ts  ;
$spPassword= md5($passwordhash);
$url = "http://41.206.4.219:8310/AmountChargingService/services/AmountCharging";
$host="41.206.4.219";
$description="charge";
$currency="NGN";
$amount= isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '1000'; // billing amount in nigerian kobo
$code="10090";
$endUserIdentifier= isset($_REQUEST['endUserIdentifier']) ? $_REQUEST['endUserIdentifier'] : '2347032700097' ; //mobile no of the user
$referenceCode= $ts. $endUserIdentifier;


        
// connecting to the oracle database
function db_connect() {
    $desc = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 397970-vm2.db1.localhost.co.uk)(PORT = 1521))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = mydb.397970-vm2.db1.localhost.co.uk)))";
    $conn = oci_connect('v2ngw', 'Fri1007', "$desc");
    return $conn;
}

function billme($spId,$serviceId, $ts, $OA, $FA, $password,$spPassword,
$host, $description, $currency, $amount, $code,$endUserIdentifier, $referenceCode) 
  {
    $bind = array(
     
  'SPID' =>  $spId,
  'SERVICEID' =>  $serviceId, 
  'TS' =>  $ts, 
  'OA' =>  $OA, 
  'FA' =>  $FA, 
  'PASSWORD' =>  $password,
  'SPPASSWORD' =>  $spPassword,
  'HOST' =>  $host, 
  'DESCRIPTION' =>  $description,
  'CURRENCY' =>  $currency, 
  'AMOUNT' =>  $amount, 
  'CODE' =>  $code,
  'ENDUSERIDENTIFIER' =>  $endUserIdentifier, 
  'REFERENCECODE' =>  $referenceCode,     
   
        );
     
    $msg = db_execute("insert mtn_billing (spId,serviceId,ts,OA,FA,password,spPassword,
host,description,currency,amount,code,endUserIdentifier,referenceCode) values (:spId,:serviceId,:ts,:OA,:FA,:password,:spPassword,
:host, :description, :currency,:amount,:code,:endUserIdentifier,:referenceCode)", $bind);
    return $msg;
} 


// $recvtime = isset($_REQUEST['recvtime']) ? $_REQUEST['recvtime'] : date('Y-m-d h');

######################
$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:loc="http://www.csapi.org/schema/parlayx/payment/amount_charging/v2_1/local">
  <soapenv:Header>  
      <RequestSOAPHeader xmlns="http://www.huawei.com.cn/schema/common/v2_1">  
        <spId>' . $spId . '</spId>
        <spPassword>'.$spPassword.'</spPassword>
         <timeStamp>'.$ts.'</timeStamp>  
             <OA>'.$OA.'</OA>
             <FA>'.$FA.'</FA> 
         <serviceId>'. $serviceId . '</serviceId>
     </RequestSOAPHeader>  
  </soapenv:Header>  
  <soapenv:Body>  
   <loc:chargeAmount>  
   <loc:endUserIdentifier>'.$endUserIdentifier.'</loc:endUserIdentifier> 
   <loc:charge>
     <description>'.$description.'</description>
               <currency>'.$currency.'</currency>
               <amount>'.$amount.'</amount>
               <code>'.$code.'</code>
       </loc:charge>
<loc:referenceCode>'.$referenceCode.'</loc:referenceCode>
   </loc:chargeAmount>   
  </soapenv:Body>  
  </soapenv:Envelope> ';

$headers = array(
    "POST  HTTP/1.1",
    "Host: $host",
    "Content-type: application/soap+xml; charset=\"utf-8\"",
    "SOAPAction: \"\"",
    "Content-length: " . strlen($xml)
);



$soap_do = curl_init();
curl_setopt($soap_do, CURLOPT_URL, $url);
curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($soap_do, CURLOPT_POST, true);
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $headers);
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $xml);
//curl_setopt($soap_do, CURLOPT_USERPWD, $username . ":" . $password);

$result = curl_exec($soap_do);
$err = curl_error($soap_do);
//log_action($result, 'messages.log');
print_r($result);

return billme($spId,$serviceId, $ts, $OA, $FA, $password, $spPassword, $host, $description, $currency, $amount, $code, $endUserIdentifier, $referenceCode);

?>

