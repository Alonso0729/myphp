<?php
if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct, $hc);
    if ($hc == 200)
        $c = replace_ts_urls($u, $c);
} else {
    $c = send_request($ts_url, $ct, $hc);
}
echo_content($hc, $ct, $c);



function need_m3u8(&$id, &$ts_url)
{
    $q = $_SERVER['QUERY_STRING'];
    $r = stripos($q, 'id=') === 0;
    if ($r)
        $id = $_GET['id'];
    else
        $ts_url = $q;
    return $r;
}

function get_m3u8_url($id)
{
    $seed = 'tvata nginx auth module';
    $uri = "/$id/playlist.m3u8";
    $tid = "mc42afe745533";
    $ct = strval(intval(time()/150));
    $tsum = md5($seed.$uri.$tid.$ct);
    $q = "tid=$tid&ct=$ct&tsum=$tsum";
//    $h = 'http://50.7.234.10:8278';
    $h = 'http://198.16.100.90:8278';
    $r = "$h$uri?$q";
    return $r;
}

function send_request($url, &$content_type, &$http_code)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ip = '127.0.0.1';
    $header = array(
        'User-Agent: Mozilla/5.0',
        "CLIENT-IP: ".$ip,
        "X-FORWARDED-FOR: ".$ip,
        'Server: TVA Streaming Server v2023 r230628',
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $res = curl_exec($ch);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    //$m3u8_content = ltrim($m3u8_content, "\xEF\xBB\xBF");
    $dest_ts_path = dirname($m3u8_url)."/";
    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $self_part = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($self_part, $dest_ts_path) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            return "$self_part?$ts";
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($http_code, $content_type, $content)
{
    http_response_code($http_code);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}