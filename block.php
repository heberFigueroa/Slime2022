<?php
error_reporting(0);
ini_set('display_errors', 0);

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



//echo ip_in_range("80.76.201.37", "80.76.201.32/27");

/* $ips = array(
"real",
"77.74.178.0/23",
"77.74.176.0/23",
"185.85.15.0/24",
"82.202.185.0/24",
"185.54.222.0/24",
	);
 */

foreach ($ips as $ip)
{
    if (ip_in_range($_SERVER['REMOTE_ADDR'], $ip))
    {
	//	echo $ip."-yes<br>";
        //header('location: https://www.etsy.com/');
		header('location: '. RandNumber(9));
        exit;
    }
}