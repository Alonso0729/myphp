<?php
if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct, $u);
    $c = replace_ts_urls($u, $c);
} else {
    $c = send_request($ts_url, $ct);
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

    $p = get_m3u8_url_from_web($id);
    $r = get_actual_m3u8_url($p);

    save_to_cache($id, $r);
    return $r;
}

function get_m3u8_url_from_web($id) {
    $u = "http://aikanvod.miguvideo.com/video/p/live.jsp?user=guest&channel=$id&isEncrypt=1";
    $u .= '&appVersion=927&channalNo=2';
    $u .= '&categoryid=8062ec8cb4a601dd5746125d96279559a3016164f1e415c164025379e8acc8ac';
    $c = file_get_contents($u);
    preg_match('/source src="(.*?)"/', $c, $m);
    $p = $m[1];
    if ($p == '') {
        $cookies = [];
        foreach ($http_response_header as $h)
            if (preg_match('/Set-Cookie:\s*(.*?);/i', $h, $m) === 1)
                $cookies[] = $m[1];
        $cookieHeader = implode('; ', $cookies);
        $doc = new DOMDocument();
        @$doc->loadHTML($c);
        $k = '';
        $maxLen = 0;
        foreach ($doc->getElementsByTagName('input') as $input) {
            if ($input->getAttribute('id') === 'posterPicAll')
                continue;
            $inputValue = $input->getAttribute('value');
            if ($inputValue && $maxLen < strlen($inputValue)) {
                $maxLen = strlen($inputValue);
                $k = $inputValue;
            }
        }
        $channel = $doc->getElementById('channel')->getAttribute('value');
        $channel = rawurlencode(encryptPwd($channel, $k));
        $channelcode = $doc->getElementById('channelcode')->getAttribute('channelcode');
        $channelcode = rawurlencode(encryptPwd($channelcode, $k));
        $zjChannelOfOutStatus = $doc->getElementById('zjChannelOfOutStatus')->getAttribute('value');
        $zjChannelOfOutStatus = rawurlencode($zjChannelOfOutStatus);
        $u2 = "http://aikanvod.miguvideo.com/video/p/live.jsp?vt=9&type=1&user=guest&channel=$channel&isEncrypt=1&channelcode=$channelcode";
        $u2 .= "&zjChannelOfOutStatus=$zjChannelOfOutStatus";
        $c = file_get_contents($u2, false, stream_context_create(array(
            'http' => array(
                'header'  =>
                    "Referer: $u\r\n".
                    "epgsession: \r\n".
                    "location: \r\n".
                    "Cookie: $cookieHeader\r\n".
                    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36\r\n"
            )
        )));
        $j = json_decode($c);
        $p = $j->liveUrl;
    }
    return $p;
}

function encryptPwd($txt, $publicKey) {
    // 格式化公钥（确保包含PEM头尾）
    if (strpos($publicKey, '-----BEGIN PUBLIC KEY-----') === false) {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($publicKey, 64, "\n") .
            "-----END PUBLIC KEY-----";
    }
    // 加载公钥
    $keyResource = openssl_pkey_get_public($publicKey);
    // 加密数据
    $encrypted = '';
    openssl_public_encrypt($txt, $encrypted, $keyResource);
    if (PHP_VERSION_ID < 80000)
        openssl_pkey_free($keyResource);
    // 返回Base64编码结果
    return base64_encode($encrypted);
}

function get_actual_m3u8_url($index_m3u8_url) {
    $c = send_request($index_m3u8_url, $ct, $r);
    if (stripos($c, '#EXT-X-STREAM-INF:') !== false) {
        $a = explode("\n", $c);
        foreach ($a as $i) {
            $i = trim($i);
            if (strpos($i, '#') !== 0) {
                if (is_absolute_url($i))
                    $r = $i;
                else
                    $r = dirname($r)."/".$i;
                break;
            }
        }
    }
    return $r;
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60;
    $a[$id]['url'] = $url;
    array_to_file($a, 'mgak_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('mgak_cache.txt', $a);
    if (isset($a) && isset($a[$id]) && $a[$id]['expire_time'] > time())
        return $a[$id]['url'];
    else
        return '';
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
    $data = serialize($array);
    file_put_contents($filename, $data, LOCK_EX);
    return true;
}

function send_request($url, &$content_type = null, &$final_url = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $res = curl_exec($ch);
    if (func_num_args() > 1)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if (func_num_args() > 2)
        $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
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