<?php
//需国外服务器，香港的也不行
//https://iptv.cc/forum.php?mod=viewthread&tid=5788&highlight=%E9%BA%92%E9%BA%9F
if (need_m3u8($m3u8_url, $ts_url)) {
    $c = send_request($m3u8_url, $ct);
    $c = replace_ts_urls($m3u8_url, $c);
} else {
    $c = send_request($ts_url, $ct);
}
echo_content($ct, $c);



function need_m3u8(&$m3u8_url, &$ts_url)
{
    $q = $_SERVER['QUERY_STRING'];
    $r = stripos($q, '.m3u8') !== false;
    if ($r)
        $m3u8_url = $q;
    else
        $ts_url = $q;
    return $r;
}

function send_request($url, &$content_type)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
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

function echo_content($content_type, $content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: $content_type");
    echo $content;
}
?>