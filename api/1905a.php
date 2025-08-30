<?php

//代理m3u8和ts
//1080p-环球经典(1905国外),1905.php?id=LIVE8J4LTCXPI7QJ5
//1080p-流金岁月(1905国内),1905.php?id=LIVENCOI8M4RGOOJ9
//1080p-CCTV6,1905.php?id=LIVE28XN12SUI4GWG

if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, null, null, $ct);
    $c = replace_ts_urls($u, $c);
} else {
    $c = send_request($ts_url, null, null, $ct);
}
echo_content($ct, $c);



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
    $r = load_from_cache($id);
    if ($r)
        return $r;

    $r = get_m3u8_url_from_web($id);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $t = time();

    $pwd = md5("1905pc{$t}m1905");
    $u = "https://liveauth.1905.com/index.php/get?atime=$t&appid=1905pc&pwd=$pwd";
    $c = send_request($u);
    $j = json_decode($c);
    $auth = $j->data->token;

    $u = 'https://liveauth.1905.com/index.php/geturl';
    $h = [
        'Authorization: ' . $auth,
        'Content-Type: application/json',
    ];
    $d = json_encode([
        'cid' => '999999',
        'streamname' => $id,
        'uuid' => '69cdfdd8-9451-41f7-9348-f2cc67edbba3',
        'playerid' => '741755197239894',
        'nonce' => $t,
        'expiretime' => $t + 600,
        'page' => 'https://m.1905.com/m/cctv6/gzh/',
    ]);
    $c = send_request($u, $h, $d);
    $j = json_decode($c);
    return $j->data->url;
}

function save_to_cache($id, $m3u8_url)
{
    $a['m3u8_url'] = $m3u8_url;
    array_to_file($a, "1905_cache/$id.txt");
}

function load_from_cache($id)
{
    $cacheFile = "1905_cache/$id.txt";
    $expireSeconds = 60 * 60;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        return $a['m3u8_url'];
    } else {
        return '';
    }
}

function file_to_array($filename, &$array) {
    $array = [];
    if (file_exists($filename)) {
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_SH)) { // 共享锁，允许其他进程读但禁止写
            $data = file_get_contents($filename);
            $array = unserialize($data);
            flock($handle, LOCK_UN);
        }
        fclose($handle);
    }
    return true;
}

function array_to_file($array, $filename) {
    $dir = dirname($filename);
    if (!is_dir($dir) && is_writable(dirname($dir))) {
        if (!@mkdir($dir, 0755, true))
            return false;
    }
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function send_request($url, $header = null, $post_data = null, &$content_type = null)
{
    $ch = curl_init();
    $o = [
        CURLOPT_URL => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
    ];
    if ($header !== null) {
        $o[CURLOPT_HTTPHEADER] = $header;
    }
    if ($post_data !== null) {
        $o[CURLOPT_POST] = true;
        $o[CURLOPT_POSTFIELDS] = $post_data;
    }
    curl_setopt_array($ch, $o);
    $r = curl_exec($ch);
    if ($r === false) {
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        echo 'url: '.$url.PHP_EOL;
        echo '【错误】' . $errno . ': ' . $error;
        curl_close($ch);
        exit;
    }
    if (func_num_args() > 3)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $r;
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