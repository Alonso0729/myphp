<?php

$id = $_GET["id"] ?? "cctv1hd";
$channel_list = array(
    'cctv1hd' => 'http://live1.hrtn.net/live/cctv1hd_3000.m3u8',
    'cctv2hd' => 'http://live1.hrtn.net/live/cctv2hd_3000.m3u8',
    'cctv3hd' => 'http://live1.hrtn.net/live/cctv3hd_3000.m3u8',
    'cctv4' => 'http://live1.hrtn.net/live/cctv4_1000.m3u8',
    'cctv5hd' => 'http://live1.hrtn.net/live/cctv5hd_3000.m3u8',
    'cctv5jhd' => 'http://live1.hrtn.net/live/cctv5jhd_3000.m3u8', //CCTV-5+
    'cctv6hd' => 'http://live1.hrtn.net/live/cctv6hd_3000.m3u8',
    'cctv7hd' => 'http://live1.hrtn.net/live/cctv7hd_3000.m3u8',
    'cctv8hd' => 'http://live1.hrtn.net/live/cctv8hd_3000.m3u8',
    'cctv9hd' => 'http://live1.hrtn.net/live/cctv9hd_3000.m3u8',
    'cctv10hd' => 'http://live1.hrtn.net/live/cctv10hd_3000.m3u8',
    'cctv11' => 'http://live1.hrtn.net/live/cctv11_1000.m3u8',
    'cctv12hd' => 'http://live1.hrtn.net/live/cctv12hd_3000.m3u8',
    'cctv13' => 'http://live1.hrtn.net/live/cctv13_1000.m3u8',
    'cctv14hd' => 'http://live1.hrtn.net/live/cctv17hd_3000.m3u8',
    'cctv15' => 'http://live1.hrtn.net/live/cctv14_1000.m3u8',
    'cctv16hd' => 'http://live1.hrtn.net/live/cctv16hd_3000.m3u8',
    'cctv17hd' => 'http://live1.hrtn.net/live/cctv14hd_3000.m3u8',
    'hbwshd' => 'http://live1.hrtn.net/live/hbwshd_3000.m3u8',
    'hnwshd' => 'http://live1.hrtn.net/live/hnwshd_3000.m3u8',
    'dfwshd' => 'http://live1.hrtn.net/live/dfwshd_3000.m3u8',
    'zjwshd' => 'http://live1.hrtn.net/live/zjwshd_3000.m3u8',
    'bjwshd' => 'http://live1.hrtn.net/live/bjwshd_3000.m3u8',
    'jswshd' => 'http://live1.hrtn.net/live/jswshd_3000.m3u8',
    'ahwshd' => 'http://live1.hrtn.net/live/ahwshd_3000.m3u8',
    'sdwdhd' => 'http://live1.hrtn.net/live/sdwdhd_3000.m3u8',
    'henwshd' => 'http://live1.hrtn.net/live/henwshd_3000.m3u8',
    'scwshd' => 'http://live1.hrtn.net/live/scwshd_3000.m3u8',
    'hebws' => 'http://live1.hrtn.net/live/hebws_1000.m3u8',
    'jlwshd' => 'http://live1.hrtn.net/live/jlwshd_3000.m3u8',
    'hljwshd' => 'http://live1.hrtn.net/live/hljwshd_3000.m3u8',
    'lnwshd' => 'http://live1.hrtn.net/live/lnwshd_3000.m3u8',
    'gdwshd' => 'http://live1.hrtn.net/live/gdwshd_3000.m3u8',
    'szwshd' => 'http://live1.hrtn.net/live/szwshd_3000.m3u8',
    'cqwshd' => 'http://live1.hrtn.net/live/cqwshd_3000.m3u8',
    'dnwshd' => 'http://live1.hrtn.net/live/dnwshd_3000.m3u8',
    'gxwshd' => 'http://live1.hrtn.net/live/gxwshd_3000.m3u8',
    'gzwshd' => 'http://live1.hrtn.net/live/gzwshd_3000.m3u8',
    'jxwshd' => 'http://live1.hrtn.net/live/jxwshd_3000.m3u8',
    'tjwshd' => 'http://live1.hrtn.net/live/tjwshd_3000.m3u8',
    'ynws' => 'http://live1.hrtn.net/live/ynws_1000.m3u8',
    'sxws' => 'http://live1.hrtn.net/live/sxws_1000.m3u8',
    'shxws' => 'http://live1.hrtn.net/live/shxws_1000.m3u8',
    'gsws' => 'http://live1.hrtn.net/live/gsws_1000.m3u8',
    'nxws' => 'http://live1.hrtn.net/live/nxws_1000.m3u8',
    'nmws' => 'http://live1.hrtn.net/live/nmws_1000.m3u8',
    'qhws' => 'http://live1.hrtn.net/live/qhws_1000.m3u8',
    'hainwshd' => 'http://live1.hrtn.net/live/hainwshd_3000.m3u8',
    'xjws' => 'http://live1.hrtn.net/live/xjws_1000.m3u8',
    'xzws' => 'http://live1.hrtn.net/live/xzws_1000.m3u8',
    'btws' => 'http://live1.hrtn.net/live/btws_1000.m3u8',
    'hbzhhd' => 'http://live1.hrtn.net/live/hbzhhd_3000.m3u8', //湖北综合
    'hbjsjd' => 'http://live1.hrtn.net/live/hbjsjd_3000.m3u8', //湖北经视
    'hbgghd' => 'http://live1.hrtn.net/live/hbgghd_3000.m3u8', //湖北公共新闻
    'hbjy' => 'http://live1.hrtn.net/live/hbjy_1000.m3u8', //湖北教育
    'hbsh' => 'http://live1.hrtn.net/live/hbsh_1000.m3u8', //湖北生活
    'hbys' => 'http://live1.hrtn.net/live/hbys_1000.m3u8', //湖北影视
    'zgjy1hd' => 'http://live1.hrtn.net/live/zgjy1hd_3000.m3u8',
    'zgjy4' => 'http://live1.hrtn.net/live/zgjy4_1000.m3u8',
    'jjkt' => 'http://live1.hrtn.net/live/jjkt_1000.m3u8',
    'jiajkt' => 'http://live1.hrtn.net/live/jiajkt_1000.m3u8',
    'dajs' => 'http://live1.hrtn.net/live/dajs_1000.m3u8',
    'jyjs' => 'http://live1.hrtn.net/live/jyjs_1000.m3u8', //武汉新闻综合
    'cctvnews' => 'http://live1.hrtn.net/live/cctvnews_1000.m3u8',
    'jtlc' => 'http://live1.hrtn.net/live/jtlc_1000.m3u8',
    'kkse' => 'http://live1.hrtn.net/live/kkse_1000.m3u8',
    'shjs' => 'http://live1.hrtn.net/live/shjs_1000.m3u8', //劲爆体育
    'zgqx' => 'http://live1.hrtn.net/live/zgqx_1000.m3u8', //中国天气
    'sdjyws' => 'http://live1.hrtn.net/live/sdjyws_1000.m3u8', //山东教育卫视
);
function generate_auth_url($url, $timestamp_ms)
{
    $expiration_ts = (int)($timestamp_ms / 1000) + 172800;

    $path = parse_url($url, PHP_URL_PATH);
    if (empty($path)) {
        $path = "/";
    }

    try {
        $random_uuid_hex = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        $random_uuid_hex = md5(uniqid((string)rand(), true));
    }


    $salt = "kK6QfSCS2X";


    $string_to_sign = "{$path}-{$expiration_ts}-{$random_uuid_hex}-0-{$salt}";

    $md5_hash = md5($string_to_sign);


    $auth_key = "{$expiration_ts}-{$random_uuid_hex}-0-{$md5_hash}";

    $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
    $final_url = $url . $separator . 'auth_key=' . $auth_key;

    return $final_url;
}


$input_url = $channel_list[$id];

$current_timestamp_ms = (int)(microtime(true) * 1000);
$new_authed_url = generate_auth_url($input_url, $current_timestamp_ms);


header('location:' . $new_authed_url);

?>