<?php
$id = $_GET['id']??'ahys';
$n = [
   "ahsh" => '0',//安徽生活频道
   "ahys" => '1',//安徽影视频道
   "ahzy" => '2',//安徽综艺频道
   "hnzh" => '3',//淮南新闻综合
   "pxzh" => '4',//萧县综合频道
   "thzh" => '5',//太湖新闻综合
   "gdsh" => '6',//广德生活频道
   "gdxw" => '7',//广德新闻信合
   "mcxw" => '8',//蒙城新闻
   "yxxw" => '9',//岳西新闻综合
   ];

$url = "https://wyedit.ahwanyun.cn/api/system/cmsLiveStream/list?appId=123&liveReleaseStatus=2&source=2&clientType=ios&clientVersion=1.0.2&liveType=4";
$m3u8 = json_decode(file_get_contents($url),1)['rows'][$n[$id]]['liveM3u8Url'];
header('Location:'.$m3u8);
?>