<?php
function get_web_page($url)
{
    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(

        CURLOPT_CUSTOMREQUEST => "GET", //set request type post or get
        CURLOPT_POST => false, //set to GET
        CURLOPT_USERAGENT => $user_agent, //set user agent
        CURLOPT_COOKIEFILE => "cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR => "cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "", // handle all encodings
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 2, // timeout on connect
        CURLOPT_TIMEOUT => 30, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);
    curl_close($ch);

    $header['errno'] = $err;
    $header['errmsg'] = $errmsg;
    $header['content'] = $content;
    return $header;
}

function get_ver($page)
{

    if (strpos($page, '<td class="lgnBL">') !== false || strpos($page, 'Copyright (c) 2006 Microsoft Corporation') !== false || strpos($page, '<td class="lgnBM">') !== false)
    {
        return 'old exchange';
    }
    elseif (strpos($page, '<div class="sidebar">') !== false || strpos($page, 'Copyright (c) 2011 Microsoft Corporation') !== false || strpos($page, 'Default to mouse class') !== false)
    {
        return 'new exchange';
    }
    elseif (preg_grep('/^Copyright \(c\) ([0-9]{4}) Microsoft Corporation/', array(
        $page
    )))
    {
        return 'other exchange';
    }
    else
    {
        return 'not exchange';
    }

}

function getmxser($domain)
{
    $hosts = array();
    getmxrr($domain, $hosts);
    return $hosts[0];
}

function check_owa($url)
{

    $result = get_web_page("https://mail.$url");

    if (get_ver($result['content']) == 'not exchange')
    {
        $result = get_web_page("https://autodiscover.$url");
        if (get_ver($result['content']) == 'not exchange')
        {
            $result = get_web_page("https://webmail.$url");
            if (get_ver($result['content']) == 'not exchange')
            {
                $result = get_web_page("https://owa.$url");
                if (get_ver($result['content']) == 'not exchange')
                {
                    $result = get_web_page("https://qmail7.$url");
                    if (get_ver($result['content']) == 'not exchange')
                    {
                        $result = get_web_page("https://" . getmxser($url));
                        return get_ver($result['content']);
                    }
                    else
                    {
                        return get_ver($result['content']);
                    }

                }
                else
                {
                    return get_ver($result['content']);
                }

            }
            else
            {
                return get_ver($result['content']);
            }

        }
        else
        {
            return get_ver($result['content']);
        }

    }
    else
    {
        return get_ver($result['content']);
    }
}

//echo check_owa("du.ae");
