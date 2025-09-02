<?php

/*
武汉新闻综合频道,wh.php?id=20
武汉电视剧频道,wh.php?id=5
武汉科技生活频道,wh.php?id=6
武汉文体频道,wh.php?id=8
*/

$id = $_GET['id'];
$u = get_m3u8_url($id);
$c = send_request($u);
$c = replace_ts_urls($u, $c);
echo_content($c);



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
    return getChannelData($id);
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60 * 6;
    $a[$id]['url'] = $url;
    array_to_file($a, 'wh_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('wh_cache.txt', $a);
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

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($dest_ts_path) {
            if (!is_absolute_url($matches[1]))
                $ts = $dest_ts_path.$matches[1];
            else
                $ts = $matches[1];
            return $ts;
        },
        $m3u8_content
    );
}

function is_absolute_url($url) {
    return stripos($url, 'http:') === 0 || stripos($url, 'https:') === 0;
}

function echo_content($content)
{
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/vnd.apple.mpegurl");
    echo $content;
}



function getChannelData($channel_id)
{
    $url = ConfigureUtils_getUrl("http://mobile.appwuhan.com/zswh6/channel_detail.php");
    $url .= "&channel_id=$channel_id";

    $header = Util_getRequestHeader();

    $r = send_request($url, $header);
    $j = json_decode($r);
    return $j[0]->m3u8;
}

function Util_getRequestHeader()
{
    $k = 'c9e1074f5b3f9fc8ea15d152add07294';
    $salt = 'S1M1MXczMFhPQXNPZXc0RU1vVWdwV2NRTU9JMmhHMFI=';
    $v = Util_getVersionName();
    $t = Util_getRandomData(6);
    $s = base64_encode(hash('sha1', "$k&$salt&$v&$t", false));

    $r = [];
    $r[] = 'User-Agent: m2oSmartCity_104 1.0.0';
    $r[] = 'X-API-VERSION: '.$v;
    $r[] = 'X-API-SIGNATURE: '.$s;
    $r[] = 'X-API-KEY: '.$k;
    $r[] = 'X-API-TIMESTAMP: '.$t;
    $r[] = 'X-AUTH-TYPE: sha1';
    $r[] = 'Content-Type: application/x-www-form-urlencoded';
    return $r;
}

function Util_getRandomData($i) {
    $currentTimeMillis = intval(microtime(true) * 1000);
    $sb = '';
    for ($i2 = 0; $i2 < $i; $i2++) {
        $strArr = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z",
            "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"
        ];
        $random = mt_rand(0, count($strArr) - 1);
        $sb .= $strArr[$random];
    }
    return $currentTimeMillis . $sb;
}

function ConfigureUtils_getUrl($str, $cid = '')
{
    $Variable_ANDROID_KEY = 'rFUm5PYocCj6e1h0m03t3WarVJcMV98c';
    $Variable_SETTING_USER_TOKEN = '';
    $Variable_GETUI_INSTALLATIONID = $cid;
    $Variable_SETTING_USER_ID = '';
    $Variable_APP_VERSION_NAME = Util_getVersionName();
    $Build_VERSION_RELEASE = 12;
    $Build_MODEL = build_phoneModel();

    $str2 = '';
    if (empty($str)) {
        return "";
    }
    $sb = '';
    $sb .= $str;
    if (empty($sb)) {
        return "";
    }
    if (strpos($sb, "appid=") === false && strpos($sb, "appkey=") === false) {
        if (strpos($sb, "?") !== false) {
            if (substr($sb, -1) == "?") {
                $str2 = "";
            } else {
                $str2 = "&appid=" . Variable_ANDROID_ID();
            }
            $sb .= $str2;
        } else {
            $sb .= "?appid=" . Variable_ANDROID_ID();
        }
        $sb .= "&appkey=" . $Variable_ANDROID_KEY;
    }
    if (!empty($Variable_SETTING_USER_TOKEN)) {
        $sb .= "&access_token=" . $Variable_SETTING_USER_TOKEN;
    }
    if (!empty($Variable_GETUI_INSTALLATIONID)) {
        $sb .= "&client_id_android=" . $Variable_GETUI_INSTALLATIONID;
    }
    $sb .= "&device_token=" . Util_getDeviceToken();
    $sb .= "&_member_id=" . $Variable_SETTING_USER_ID;
    $sb .= "&version=" . $Variable_APP_VERSION_NAME;
    $sb .= "&app_version=" . $Variable_APP_VERSION_NAME;
    $sb .= "&app_version=" . Util_getVersionName();
    $sb .= "&package_name=" . build_packageName();
    $sb .= "&system_version=" . $Build_VERSION_RELEASE;
    $sb .= "&phone_models=" . $Build_MODEL;
    return str_replace(" ", "", $sb);
}

function Variable_ANDROID_ID() {
    return 16;
}

function Util_getVersionName()
{
    return '6.2.8';
}

function Util_getDeviceToken()
{
    static $token = null;
    if (is_null($token)) {
        $s = build_androidID();
        $token = md5($s.build_packageName());
    }
    return $token;
}

function build_phoneModel()
{
    return 'SM-A5560';
}

function build_androidID()
{
    $e = '9774d56d682e549c';
    while (true) {
        $r = bin2hex(openssl_random_pseudo_bytes(8));
        if ($r !== $e)
            break;
    }
    return $r;
}

function build_packageName() {
    return 'com.hoge.android.wuhan';
}

function send_request($url, $header = null, $post_body = null) {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
    );
    if ($header !== null) {
        $options[CURLOPT_HTTPHEADER] = $header;
    }
    if ($post_body !== null) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $post_body;
    }
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}