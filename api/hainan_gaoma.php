<?php
$id=$_GET["id"];
/*
id=3海南卫视标清,4海南经济,5海南新闻,6海南公共,7三沙卫视,8海南文旅,,9海南少儿,11新闻广播,19海南卫视高清
*/
//$url="http://tv.hnntv.cn/m2o/channel/channel_info.php?channel_id='.$id.'";
$url="http://tv.hnntv.cn/m2o/channel/channel_info.php?channel_id=$id";
$contents = file_get_contents($url); 
preg_match('/"m3u8":"(.*?)"/',$contents,$m);
$m3u8 = stripslashes($m[1]);
header('location:'.urldecode($m3u8));
?>