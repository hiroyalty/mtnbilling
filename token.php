
<?php

include_once 'billfunc.php';

    $timestamp = date(YmdHis);
    $length = 20;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
  
 $token_id = $randomString ;

 echo $token_id;
 echo '<br />';
 echo $timestamp;

$mg = tok($token_id,$timestamp);
echo '<br />';
var_dump($mg);



