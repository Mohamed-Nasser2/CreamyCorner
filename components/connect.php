<?php

$dsn = 'mysql:host=localhost;dbname=icecreamshop';
$user = 'root';
$pass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo 'Failed To Connect' . $e->getMessage();
}

function unique_id(){
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charLength = strlen($chars);
    $randomString = '';
    
    for($i=0 ; $i < 20 ; $i++){
        $randomString.=$chars[mt_rand(0 , $charLength - 1)];
    }
    return $randomString;
}

?>