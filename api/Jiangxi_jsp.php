<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'jxws';
$n = [
    'jxws' => 'tv_jxtv1.m3u8',//江西卫视
    'jxds' => 'tv_jxtv2.m3u8',//江西都市
    'jxjs' => 'tv_jxtv3_hd.m3u8',//江西经视高清
    'jxys' => 'tv_jxtv4.m3u8',//江西影视
    'jxgg' => 'tv_jxtv5.m3u8',//江西公共
    'jxse' => 'tv_jxtv6.m3u8',//江西少儿
    'jxxw' => 'tv_jxtv7.m3u8',//江西新闻
    'jxyd' => 'tv_jxtv8.m3u8',//江西移动
    'fsgw' => 'tv_fsgw.m3u8',//江西风尚购物
    'jxtc' => 'tv_taoci.m3u8',//江西陶瓷
    ];
$t = time();
$data = "t={$t}&stream={$n[$id]}&uuid=0310c3096064";
$ch = curl_init("https://cdnauth.jxgdw.com/liveauth/pc?".$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["origin: https://www.jxntv.cn",]);
curl_setopt($ch, CURLOPT_REFERER, "https://www.jxntv.cn/");
$d = curl_exec($ch);
curl_close($ch);
$json = json_decode($d, 1);
$t = $json['t'];
$token = $json['token'];
$m3u8 = "https://yun-live.jxtvcn.com.cn/live-jxtv/{$n[$id]}?source=pc&token={$token}&t={$t}&uuid=0310c3096064";
$burl = "https://yun-live.jxtvcn.com.cn/live-jxtv/";
$php = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$ts = $_GET['ts'];
if(empty($ts)) {
     header('Content-Type: application/vnd.apple.mpegurl');
     // 使用回调函数对每个匹配的URL进行编码
     $content = get($m3u8);
     $content = preg_replace_callback("/(.*?\.ts[^\s]*)/i", function($matches) use ($php, $burl) {
         return $php . "?ts=" . urlencode($burl . $matches[1]);
     }, $content);
     print_r($content);
     } else {
       // 解码ts参数
       $data = get(urldecode($ts));
       header('Content-Type: video/MP2T');
       echo $data;
       }

function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_REFERER, 'https://www.jxntv.cn/');
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }

?>