<?php

// connecting to the oracle database

function db_connect() {
    $desc = "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 397970-vm2.db1.localhost.co.uk)(PORT = 1521))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME = mydb.397970-vm2.db1.localhost.co.uk)))";
    $conn = oci_connect('v2ngw', 'Fri1007', "$desc");
    return $conn;
}

// parsing parameters into the database
function db_execute($sql, $bind = null) {
    $conn = db_connect();
    $res = array();
    $s = oci_parse($conn, $sql);
    if ($bind != null) {
        foreach ($bind as $key => $value) {
            oci_bind_by_name($s, ":".$key, htmlentities($value,ENT_QUOTES));
        }
    }
    $res = oci_execute($s);
    return $res;
}

function db_query($sql, $bind = null) {
    $c = db_connect();
    $res = array();
    $s = oci_parse($c, $sql);
    if ($bind != null) {
        foreach ($bind as $key => $value) {
            oci_bind_by_name($s, ":".$key, $value);
        }
    }
    oci_execute($s);
    #oci_fetch_all($s, $res);
    while($row = oci_fetch_object($s)){
        $res[]=$row;
    }
    return $res;
}

function tok($token_id,$timestamp) {
    $bind = array(
        'TOKEN_ID' => $token_id,
       'TIMESTAMP' => $timestamp,
    );
  $tokmsg = db_execute("insert into token_checker (token_id,timestamp) values (:token_id,:timestamp)", $bind);
return $tokmsg;
    
    }
    
function billme($spid, $serviceid, $ts, $servicename, $token, $oa, $fa, $password, $sppassword, $host, $description, $currency, $amount, $code, $enduseridentifier, $referencecode,$status,$statusdesc) {
    $bind = array(
        'SPID' => (int)$spid,
        'SERVICEID' => (int)$serviceid,
        'TS' => $ts,
        'SERVICENAME' => $servicename, 
        'TOKEN' => $token,
        'OA' => (int)$oa,
        'FA' => (int)$fa,
        'PASSWORD' => $password,
        'SPPASSWORD' => $sppassword,
        'HOST' => $host,
        'DESCRIPTION' => $description,
        'CURRENCY' => $currency,
        'AMOUNT' => (int)$amount,
        'CODE' => (int)$code,
        'ENDUSERIDENTIFIER' => (int)$enduseridentifier,
        'REFERENCECODE' => $referencecode,
        'STATUS' => $status,
        'STATUSDESC' => $statusdesc,
        
    );

        $msg = db_execute("insert into mtn_billing (spid,serviceid,ts,servicename,token,oa,fa,password,sppassword,host,description,currency,amount,code,enduseridentifier,referencecode,status,statusdesc) values (:spid,:serviceid,:ts,:servicename,:token,:oa,:fa,:password,:sppassword,:host, :description, :currency,:amount,:code,:enduseridentifier,:referencecode,:status,:statusdesc)", $bind);
        return $msg;

    
}
/*function gentoken($tokenid) {
    $bind = array(
       
        'TOKENID' => $tokenid,
   );


    // var_dump($bind);
  // $token_q = db_query("select * from token where token_id = '".$pin_box."' and  used = '0'");
   // $token_q = db_query("select * from token_checker");
    //if (@db_num_rows($token_q) > 0) {
        $tokmsg = db_execute("insert into token_checker (tokenid) values (:tokenid)", $bind);
        return $tokmsg;
   // } else {
       // echo "<h3>Invalid Pin....!!</h3>Please get a valid pin</p>";
    
} */

?>