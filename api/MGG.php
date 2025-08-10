<?php
header("Content-type:text/html;charset=utf-8");//文件类型
error_reporting(0);
function Cf($url){
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_TIMEOUT,30);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0(WindowsNT10.0;Win64;x64)AppleWebKit/605.1.15(KHTML,likeGecko)Version/yuanhsSafari/605.1.15');
$c=@curl_exec($ch);
curl_close($ch);
return $c;
}
$id=$_REQUEST ["id"]??"265183188";
$a=@Cf("http://aikanvod.miguvideo.com/video/p/live.jsp?user=guest&channel=$id");
preg_match('|\<source\ src\="(.*)"|isU', $a, $aa);//正则
$url=str_replace('&time','&time',$aa[1]);
die($url);