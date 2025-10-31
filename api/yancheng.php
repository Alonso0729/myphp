<?php

/*
盐城新闻综合,yancheng.php?id=24
盐城法制生活,yancheng.php?id=25
盐城公共,yancheng.php?id=26
*/

$id = isset($_GET['id']) ? $_GET['id'] : null;
if ($id !== null) {
    $u = "https://mapiyc.0515yc.cn/api/v1/channel.php?channel_id=".$id;
    $c = get($u, false);
    $j = json_decode($c, true);
    $m3u8 = $j[0]['m3u8'];
    $c = get($m3u8, true);
    if ($c[0] === '#') {
        preg_match('/^(?!#).+$/m', $c, $m);
        $m3u8 = dirname($m3u8).'/'.$m[0];
    }
    $l = $_SERVER['PHP_SELF'].'?m3u8='.rawurlencode($m3u8);
    header('Access-Control-Allow-Origin: *');
    header('Location: '.$l);
    die;
}

$m3u8 = isset($_GET['m3u8']) ? $_GET['m3u8'] : null;
if ($m3u8 !== null) {
    $c = get($m3u8, true);
    $a = explode('/', $m3u8);
    $a = array_slice($a, 0, 3);
    $p = implode('/', $a);
    $r = $_SERVER['PHP_SELF'].'?ts=';
    $c = preg_replace_callback('/^(?!#)/m', function($m) use($p, $r) {
        return $r.rawurlencode($p.$m[0]);
    }, $c);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/vnd.apple.mpegurl');
    echo $c;
    die;
}

$ts = isset($_GET['ts']) ? $_GET['ts'] : null;
if ($ts !== null) {
    $c = get($ts, true);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: video/MP2T');
    echo $c;
    die;
}



function get($url, $need_referrer){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if ($need_referrer)
        curl_setopt($ch, CURLOPT_REFERER, 'http://www.0515yc.cn/');
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}