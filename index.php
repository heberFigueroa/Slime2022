<?php
//error_reporting(0);
include('engine/encrypt.php');
//blocker
include 'ip_in_range2.php';

function RandNumber($randstr)
{
    $char = 'abcdefghijklmnopqrstuvwxyz';
    $str  = '';
    for ($i = 0;
        $i < $randstr;
        $i++) {
        $pos = rand(0, strlen($char) - 1);
        $str .= $char[$pos];
    }
    return $str;

}

$ips = explode("\n", file_get_contents('ips.txt'));
$realip = doDecrypt($_POST['ip']);

foreach ($ips as $ip)
{
    if (ip_in_range($realip, $ip))
    {
	    echo "yes ";
	    echo "<br>";
	    echo $ip;
	    echo "<br>";
	    echo $realip;
	    
		//header('location: '. RandNumber(9));
        exit;
    }
}

