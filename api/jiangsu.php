<?php

$id = "jsws_live";
//"江苏卫视",jsws_live
//"江苏城市",jscs_live
//"江苏公共新闻"jsxw_live
//"江苏综艺"jszy_live
//"江苏影视"jsys_live
//"江苏体育休闲",jsxx_live
//"江苏教育"jsjy_live
//"江苏国际"jsgj_live
//"优漫卡通",ymkt_live

$key = "RhETI5NGhocnM3bW04aDZYN0NQR2pabks=ZWO";
$ts = dechex(floor(time())+180);

$url = "https://litchi-play-encrypted-site.jstv.com/live/{$id}.m3u8?txSecret=" . md5(base64_decode(substr($key, 6, -3)) . $id . $ts) . "&txTime={$ts}";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_REFERER, "https://live.jstv.com/");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch);

if(!curl_errno($ch)) {
    header('Content-Type: application/vnd.apple.mpegurl');
        $lines = explode(PHP_EOL, $result);
    $modifiedResult = '';

    foreach ($lines as $line) {
        if (strpos(ltrim($line), $id) === 0) {
            $line = 'https://litchi-play-encrypted-site.jstv.com/live/'.$line;
        }
        $modifiedResult .= $line . PHP_EOL;
    }

    echo $modifiedResult;

}

curl_close($ch);

?>