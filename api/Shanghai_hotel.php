<?php
error_reporting(0);
$n = [
   'cctv1' => 'cctv1', //CCTV1综合
   'cctv2' => 'cctv2', //CCTV2财经
   'cctv3' => 'cctv3', //CCTV3综艺
   'cctv4' => 'cctv4', //CCTV4中文国际
   'cctv5' => 'cctv5', //CCTV5体育
   'cctv6' => 'cctv6', //CCTV6电影
   'cctv7' => 'cctv7', //CCTV7国防军事
   'cctv8' => 'cctv8', //CCTV8电视剧
   'cctv10' => 'cctv10', //CCTV10科教
   'cctv11' => 'cctv11', //CCTV11戏曲 
   'cctv12' => 'cctv12', //CCTV12社会与法
   'cctv13' => 'cctv13', //CCTV13新闻
   'cctv14' => 'cctv14', //CCTV14少儿
   'cctv15' => 'cctv15', //CCTV15音乐
   'cgtn' => 'custom1', //CGTN

   'bjws' => 'bjws', //北京卫视
   'dfws' => 'dfws', //东方卫视
   'tjws' => 'tjws', //天津卫视
   'cqws' => 'cqws', //重庆卫视
   'hljws' => 'hljws', //黑龙江卫视
   'jlws' => 'jlws', //吉林卫视
   'lnws' => 'lnws', //辽宁卫视
   'gsws' => 'gsws', //甘肃卫视
   'qhws' => 'qhws', //青海卫视
   'sxws' => 'shxws', //陕西卫视
   'hbws' => 'hebeiws', //河北卫视
   'sxiws' => 'sxws', //山西卫视
   'sdws' => 'sdws', //山东卫视
   'ahws' => 'ahws', //安徽卫视
   'hubws' => 'hbws', //湖北卫视
   'hunws' => 'hunanws', //湖南卫视
   'jxws' => 'jxws', //江西卫视
   'jsws' => 'jsws', //江苏卫视
   'zjws' => 'zjws', //浙江卫视
   'dnws' => 'dnws', //东南卫视
   'gdws' => 'gdws', //广东卫视
   'szws' => 'szws', //深圳卫视
   'gxws' => 'gxws', //广西卫视
   'ynws' => 'ynws', //云南卫视  
   'scws' => 'scws', //四川卫视
   'hinws' => 'custom11', //海南卫视
   
   'shxwzh' => 'custom2', //上海新闻综合
   'shds' => 'custom3', //上海都市
   'dfys' => 'custom5', //东方影视
   'dycj' => 'custom6', //第一财经
   'wxty' => 'custom7', //五星体育
   ];

$id = isset($_GET['id'])?$_GET['id']:'cctv1';   
$ip = "218.78.176.241";
$tokenurl = "http://{$ip}:8008/HSAndroidLogin.ecgi?ty=json&mac_address1=xx:xx:xx:xx:xx:xx&opentype=0&hotel_id=1&room=101&net_account=10000100001";
$token = json_decode(get($tokenurl))->Token;
//频道列表:"http://{$ip}:8008/getplaylist.ecgi?ty=json&usercode=10000100001&grpcode=10000&token=".$token

get("http://{$ip}:8008/ualive?cid={$n[$id]}&token=".$token);

$m3u8 = "http://{$ip}:5003/{$n[$id]}.m3u8?token=".$token;
$burl = dirname($m3u8)."/";
header('Content-Type: application/vnd.apple.mpegurl');
print_r(preg_replace("/(.*?.ts)/i","$burl$1",get($m3u8)));

function get($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
     }
?>