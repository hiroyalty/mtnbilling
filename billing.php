<?php

include_once 'billfunc.php';
##################### the variables
$spid = "2340110000470";
$serviceid = "234012000003187";
$ts = date('YmdHis');
$servicename = isset($_REQUEST['servicename']) ? $_REQUEST['servicename'] : 'brilla';  //name of service
$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : 'cyv5456456y56e4754tyfe453';  //mobile no of the service originator
$oa = isset($_REQUEST['enduseridentifier']) ? $_REQUEST['enduseridentifier'] : '2347032700097';  //mobile no of the service originator
$fa = isset($_REQUEST['enduseridentifier']) ? $_REQUEST['enduseridentifier'] : '2347032700097'; //mobile no of the charged party
$password = "Huawei123";
$passwordhash = $spid . $password . $ts;
$sppassword = md5($passwordhash);
$url = "http://41.206.4.219:8310/AmountChargingService/services/AmountCharging";
$host = "41.206.4.219";
$description = "charge";
$currency = "NGN";
$amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '1000'; // billing amount in nigerian kobo
$code = "10090";
$enduseridentifier = isset($_REQUEST['enduseridentifier']) ? $_REQUEST['enduseridentifier'] : '2347032700097'; //mobile no of the user
$referencecode = $ts . $enduseridentifier;


// $recvtime = isset($_REQUEST['recvtime']) ? $_REQUEST['recvtime'] : date('Y-m-d h');
######################
$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:loc="http://www.csapi.org/schema/parlayx/payment/amount_charging/v2_1/local">
  <soapenv:Header>  
      <RequestSOAPHeader xmlns="http://www.huawei.com.cn/schema/common/v2_1">  
        <spId>' . $spid . '</spId>
        <spPassword>' . $sppassword . '</spPassword>
         <timeStamp>' . $ts . '</timeStamp>  
             <OA>' . $oa . '</OA>
             <FA>' . $fa . '</FA> 
         <serviceId>' . $serviceid . '</serviceId>
     </RequestSOAPHeader>  
  </soapenv:Header>  
  <soapenv:Body>  
   <loc:chargeAmount>  
   <loc:endUserIdentifier>' . $enduseridentifier . '</loc:endUserIdentifier> 
   <loc:charge>
     <description>' . $description . '</description>
               <currency>' . $currency . '</currency>
               <amount>' . $amount . '</amount>
               <code>' . $code . '</code>
       </loc:charge>
<loc:referenceCode>' . $referencecode . '</loc:referenceCode>
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
$response = curl_exec($soap_do);
$err = curl_error($soap_do);
//log_action($result, 'messages.log');

//$tokresult = db_query("select token_id FROM token_checker where token_id = $token"); 
//$tokrow = oci_fetch_object($tokresult);

$response = '';
if ($amount > 10){
    echo "ERR101:INVALID AMOUNT";
}
elseif($amount == NULL){
    echo "ERR102:MISSING AMOUNT";
}
elseif($servicename == NULL){
    echo "ERR103:MISSING SERVICENAME";
}
elseif($enduseridentifier == NULL){
    echo "ERR104:MISSING MSISDN";
}
elseif(preg_match('/^[0-9]{0,12}$/',$enduseridentifier)){
   
    if (preg_match('/^[0-9]{0,12}$/', $enduseridentifier)) {
} else {
    echo "ERR104:INVALID MSISDN";
}
} 
elseif($token == NULL){
    echo "ERR105:MISSING TOKEN";
}
elseif($token != $tokrow){
    echo "ERR106:INVALID TOKEN";
}
elseif (strpos(strtolower($response), 'chargeamountresponse') !== false) {
    $browser = 'success';
} else {
    $browser = 'failure';
}

$status = $result;
//var_dump($msge);
$statusdesc = '';

if (strpos(strtolower($status), 'chargeamountresponse') !== false) {
    $statusdesc = 'success';
} else {
    $statusdesc = 'failure';
}
$msge = billme($spid, $serviceid, $ts, $servicename, $token, $oa, $fa, $password, $sppassword, $host, $description, $currency, $amount, $code, $enduseridentifier, $referencecode, $status, $statusdesc);

print_r($browser);
