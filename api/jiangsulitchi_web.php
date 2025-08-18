<?php
if(!isset($_GET['ts'])) {
    $id = $_GET['id'];
    $txTime = dechex(floor(time())+180);
    $txSecret = md5("HCPMPKxQNrKAyjzR67JG".$id.$txTime);
    $url = "https://litchi-play-encrypted-site.jstv.com/applive/$id.m3u8?txSecret=$txSecret&txTime=$txTime";
    $burl = dirname($url)."/";
    $php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/vnd.apple.mpegurl');
    print_r(preg_replace("/(.*?\.ts)/i",$php."?ts=$burl$1",getData($url)));
} else {
    $data = getData($_GET['ts']);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: video/MP2T');
    echo $data;
}

function getData($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_REFERER, 'https://live.jstv.com/');
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}