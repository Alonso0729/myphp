<?php
if (isset($_SERVER['QUERY_STRING'])) goto ts;
$c = get('.m3u8');
$self = basename(__FILE__);
$c = preg_replace('/tvzh_720p-(\d+)\.ts/', $self . '?\1', $c);
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/vnd.apple.mpegurl');
echo $c;
die;

ts:
$ts = '-' . $_SERVER['QUERY_STRING'] . '.ts';
$c = get($ts);
header('Access-Control-Allow-Origin: *');
header('Content-Type: video/mp2t');
echo $c;
die;



function get($name) {
    $u = 'https://live3f.lijiang.cn/live/tvzh_720p' . $name;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $u,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => array(
            'referer: https://www.lijiang.cn/',
        )
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}