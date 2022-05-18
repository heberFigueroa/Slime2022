<?php
function get_page_content($url, $params, $custom_headers, $referer, &$last_effective_url, &$error,$Hash) {
    $s = curl_init();
    if (!$s) {
        $error = 'curl_init error';
        return FALSE;
    }
    curl_setopt($s, CURLOPT_URL, $url);
    if (!empty($custom_headers)) {
        curl_setopt($s, CURLOPT_HTTPHEADER, $custom_headers);
    }
    curl_setopt($s, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($s, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($s, CURLOPT_HEADER, 0);
    curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($s, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36");
    if (!empty($params)) {
        curl_setopt($s, CURLOPT_POST, TRUE);
        if (is_array($params)) {
            curl_setopt($s, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            curl_setopt($s, CURLOPT_POSTFIELDS, $params);
        }
    }
    curl_setopt($s, CURLOPT_REFERER, $referer);
    if (!file_exists("tmp")) {
        mkdir("tmp", 0777, true);
    }
    $tmpfname = dirname(__FILE__) . "/tmp/" .$Hash. ".txt";
    curl_setopt($s, CURLOPT_COOKIEJAR, $tmpfname);
    curl_setopt($s, CURLOPT_COOKIEFILE, $tmpfname);
    $ret = curl_exec($s);
    $errno = curl_errno($s);
    if ($errno != 0) {
        $error = 'Curl error: ' . curl_error($s);
        $ret = FALSE;
    }
    $last_effective_url = curl_getinfo($s, CURLINFO_EFFECTIVE_URL);
    curl_close($s);
    return $ret;
}