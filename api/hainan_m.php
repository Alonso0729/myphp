<?php
$ids = array(
"3"=>"hnws",
"4"=>"jjpd",
"5"=>"xwpd",
"6"=>"ggpd",
"7"=>"ssws",
"8"=>"wlpd",
"9"=>"sepd",
"11"=>"xwgb",
"19"=>"lywsgq",//stream1.hnntv.cn海南卫视高清，需要自己单独改$m3u8="http://stream3.hnntv.cn/".$ids[$id]."/playlist".$u[1];
); 
//id=7三沙卫视,19海南卫视高清,3卫视标清,4经济频道,5新闻频道,6公共频道,8海南文旅,,9海南少儿,11新闻广播
$id=$_GET["id"];
$url="http://tv.hnntv.cn/m2o/player/program_xml.php?channel_id=".$id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$re = curl_exec($ch);
curl_close($ch);
preg_match('|playlist(.*?)"|i',$re,$u);
$m3u8="http://stream3.hnntv.cn/".$ids[$id]."/playlist".$u[1];
header('Location:'.$m3u8);
?>