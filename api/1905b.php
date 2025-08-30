<?php
main();

/*
//跳转
720p-环球经典(1905国外),1905.php?t=1&id=LIVE8J4LTCXPI7QJ5_261
720p-流金岁月(1905国内),1905.php?t=1&id=LIVENCOI8M4RGOOJ9_261
360p-CCTV6,1905.php?t=1&id=LIVEEAD6BWVAZZIAM_261

//代理m3u8
1080p-环球经典(1905国外),1905.php?t=2&id=LIVE8J4LTCXPI7QJ5
1080p-流金岁月(1905国内),1905.php?t=2&id=LIVENCOI8M4RGOOJ9

//代理m3u8和ts
1080p-CCTV6,1905.php?t=3&id=LIVEI56PNI726KA7A&ts_ref=https://www.1905.com/cctv6/live/?index3
*/

function main() {
    $type = get_param('t');
    switch ($type) {
        case 1://跳转
            locate_to_m3u8($type);
            break;
        case 2://代理m3u8
            request_m3u8($type);
            break;
        case 3://代理m3u8和ts
            request_m3u8_and_ts($type);
            break;
    }
}

function get_param($name) {
    return isset($_GET[$name]) ? $_GET[$name] : null;
}

function locate_to_m3u8($type)
{
    $u = get_m3u8_url($type);
    locate($u['url']);
}

function request_m3u8($type, $need_request_ts = false, $ts_ref = null)
{
    $u = get_m3u8_url($type);
    $h = !empty($u['auth']) ? ['Authorization: '.$u['auth']] : null;
    $c = send_request($u['url'], $h, null, $ct);
    $c = replace_ts_urls($type, $u['url'], $c, $need_request_ts, $ts_ref);
    echo_content($ct, $c);
}

function request_m3u8_and_ts($type)
{
    if (need_m3u8($ts_url, $ts_ref)) {
        request_m3u8($type, true, $ts_ref);
    } else {
        $h = isset($ts_ref) ? ['Referer: '.$ts_ref] : null;
        $c = send_request($ts_url, $h, null, $ct);
        echo_content($ct, $c);
    }
}

function need_m3u8(&$ts_url, &$ts_ref)
{
    $ts_url = get_param('ts');
    $ts_ref = get_param('ts_ref');
    return !isset($ts_url);
}

function get_m3u8_url($type) {
    $id = get_param('id');
    $r = load_from_cache($id);
    if ($r)
        return $r;

    $r = get_m3u8_url_from_web($id);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $t = time();
    $expiretime = $t + 600;

    $u = 'https://profile-bj.m1905.com/mvod/generateurl.php';
    $p = [
        "cid" => 999998,
        "expiretime" => $expiretime,
        "nonce" => $t,
        "page" => 'https://m.1905.com/m/cctv6/gzh/',
        "playerid" => "074319297444734",
        "streamname" => $id,
        "uuid" => "60675e22-e238-4442-9495-39e72ce48fa2",
    ];
    $auth = hash('sha1', http_build_query($p).'.372db4f7dc5b21506401c64d66d5f55763d68438');
    $h = [
        'Authorization: ' . $auth,
        'Content-Type: application/json',
    ];
    $p["appid"] = "Tgt7vZAT";
    $d = json_encode($p);
    $c = send_request($u, $h, $d);
    $j = json_decode($c);

    date_default_timezone_set('Asia/Shanghai');
    $key = date('Y-m-d', $expiretime).'zCm614';
    $d = openssl_decrypt($j->data, 'AES-128-CBC', $key, 0, 'ZCCTV6_M1905LIVE');
    $j = json_decode($d, true);

    $q = 'hd';
    $l = $j['quality'][$q]['host'].$j['path'][$q]['uri'].$j['sign'][$q]['hashuri'];
    $auth = isset($j['sign'][$q]['token']) ? $j['sign'][$q]['token'] : '';
    $r['url'] = $l;
    $r['auth'] = $auth;
    return $r;
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

function locate($url) {
    header('Access-Control-Allow-Origin: *');
    header('Location: '.$url);
}

function save_to_cache($id, $m3u8)
{
    array_to_file($m3u8, "1905_2_cache/$id.txt");
}

function load_from_cache($id)
{
    $cacheFile = "1905_2_cache/$id.txt";
    $expireSeconds = 600;
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $expireSeconds)) {
        file_to_array($cacheFile, $a);
        return $a;
    } else {
        return null;
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

function replace_ts_urls($type, $m3u8_url, $m3u8_content, $need_request_ts, $ts_ref)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    if ($need_request_ts) {
        $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $self_part = "$protocol://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $q = 't=' . $type;
        if ($ts_ref)
            $q .= '&ts_ref=' . urlencode($ts_ref);
    } else {
        $self_part = null;
        $q = null;
    }
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($q, $self_part, $dest_ts_path) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            if ($self_part) {
                $ts = urlencode($ts);
                if ($q)
                    return "$self_part?$q&ts=$ts";
                else
                    return "$self_part?ts=$ts";
            } else
                return $ts;
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