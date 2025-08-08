<?php
$id=$_GET['id'];
$n = [
    "zhonghe" => 1375684745699328,//广州综合
    "xinwen" => 1375684808818688,//广州新闻
    "yingshi" => 1375684963975168,//广州影视
    "fazhi" => 1375684882210816,//广州法治
    "nanguodushi" => 1375684847116288,//广州南国都市
    ];
$data = json_decode(file_get_contents("https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1"))->data;//id=31-36
$count = count($data);
for($i=0;$i<$count;$i++){
if($data[$i]->stationNumber == $n[$id]){
$playurl = $data[$i]->httpUrl;
break;
}}
header("Location: {$playurl}",true,302);
?>