<?php
error_reporting(0);
$id = $_GET['id'] ?? 'hzzh';
$n = [
    'hzzh' => 16, //杭州综合
    'hzmz' => 17, //西湖明珠
    'hzsh' => 18, //杭州生活
    'hzys' => 21, //杭州影视
    'hzqsty' => 20, //青少体育
    'hzds' => 22, //杭州导视
    'fyxwzh' => 32, //富阳新闻综合
    ];
$url = 'https://mapi.hoolo.tv/api/v1/channel_detail.php?channel_id='.$n[$id];
if($id=='fyxwzh'){
   $live = json_decode(get($url),1)[0]['channel_stream'][0]['m3u8'];
   } else {
     $live = json_decode(get($url),1)[0]['channel_stream'][1]['m3u8'];
     };
$host = parse_url($live)['host'];
$php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$burl = "https://{$host}";
$ts = $_GET['ts'];
if(empty($ts)) {
   $p = get($live);
   header('Content-Type: application/vnd.apple.mpegurl');
   print_r(preg_replace("/(.*?.ts)/i", $php."?ts=$burl$1",$p));
   } else {
     $data = get($ts);
     header('Content-Type: video/MP2T');
     echo $data;
     }

function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'https://tv.hoolo.tv/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>