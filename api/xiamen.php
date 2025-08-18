<?php
if (need_m3u8($id, $ts_url)) {
    $u = get_m3u8_url($id);
    $c = send_request($u, $ct);
    $c = replace_ts_urls($u, $c);
} else {
    $c = send_request($ts_url, $ct);
}
echo_content($ct, $c);



function need_m3u8(&$id, &$ts_url)
{
    $q = $_SERVER['QUERY_STRING'];
    $r = stripos($q, 'id=') === 0;
    if ($r) {
        $id = $_GET['id'];
        // 将频道名称转换为数字ID
        $id = convert_channel_name_to_id($id);
    } else {
        $ts_url = $q;
    }
    return $r;
}

// 频道名称到ID的映射函数
function convert_channel_name_to_id($channel_name) {
    // 频道映射表
    $channel_map = [
        'xmws' => '84',    // 厦门卫视
        'xmtv1' => '16',   // 厦视一套
        'xmtv2' => '17',   // 厦视二套
        'xmtv4' => '52'    // 厦门电视台移动电视
    ];

    // 如果是频道名称，转换为ID；如果是数字ID，直接返回
    if (isset($channel_map[$channel_name])) {
        return $channel_map[$channel_name];
    } else {
        // 如果不在映射表中，检查是否为数字ID
        if (is_numeric($channel_name)) {
            return $channel_name;
        } else {
            // 未知频道名称，返回默认值或抛出错误
            return '84'; // 默认返回厦门卫视
        }
    }
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
    $u = "https://mapi1.kxm.xmtv.cn/api/v1/channel.php?channel_id=".$id;
    $c = send_request($u);
    $j = json_decode($c);
    return $j[0] -> channel_stream[0] -> m3u8;
}

function save_to_cache($id, $url)
{
    $a[$id]['expire_time'] = time() + 60 * 60;
    $a[$id]['url'] = $url;
    array_to_file($a, 'xmtv_cache.txt');
}

function load_from_cache($id)
{
    file_to_array('xmtv_cache.txt', $a);
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

function send_request($url, &$content_type = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_REFERER, 'https://seexm2024.kxm.xmtv.cn/');
    $res = curl_exec($ch);
    if (func_num_args() > 1)
        $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $res;
}

function replace_ts_urls($m3u8_url, $m3u8_content)
{
    $dest_ts_path = dirname($m3u8_url)."/";
    $dest_ts_domain_path = implode("/", array_slice(explode("/", $m3u8_url), 0, 3));
    $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    $self_part = "$protocol://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return preg_replace_callback("/^((?!#).+)$/im",
        function ($matches) use ($self_part, $dest_ts_path, $dest_ts_domain_path) {
            if (!is_absolute_url($matches[1]))
                if ($matches[1][0] == '/')
                    $ts = $dest_ts_domain_path.$matches[1];
                else
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