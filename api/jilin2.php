<?php
error_reporting(0);
$id = isset($_GET['id'])?$_GET['id']:'jlws';
$n = [
   'jlws' => 351,//吉林卫视
   'jlds' => 317,//吉林都市
   'jlys' => 352,//吉林影视
   'jlsh' => 354,//吉林生活
   'jlxc' => 356,//吉林乡村
   'jlgg' => 358,//吉林公共
   'jlzy' => 362,//吉林综艺文化
   'dbxq' => 319,//吉林东北戏曲
   ];
$d = json_decode(file_get_contents('http://mapi.plus.jlntv.cn/api/open/jlrm/channel_tv.php'),1);
foreach($d as $v){
   if($n[$id]==$v['id'])
     $m3u8 = $v['m3u8'];
   }
header('location:'.$m3u8);
//echo $m3u8;
?>