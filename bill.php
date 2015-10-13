<?php

include_once 'bootstrap.php';
##################### the variables
$spId = "2340110000470";
$serviceId = "234012000003187";
$ts = date('YmdHis');
$OA = "2347065386159";
$FA = "2347065386159";
$password ="Huawei123";
$passwordhash = $spId . $password . $ts  ;
$spPassword= md5($passwordhash);
$url = "http://41.206.4.219:8310/AmountChargingService/services/AmountCharging";
$host="41.206.4.219";
$description="charge";
$currency="NGN";
$amount="10";
$code="10090";
$endUserIdentifier="2347065386159";
$referenceCode= $ts. $endUserIdentifier;

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
?>
