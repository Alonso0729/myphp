<?php
/*
广州综合,id=zhonghe
广州新闻,id=xinwen
广州竞赛,id=jingsai
广州影视,id=yingshi
广州法治,id=fazhi
广州南国都市,id=shenghuo
*/

$id=$_GET['id'];
$url='https://gzbn.gztv.com:7443/plus-cloud-manage-app/liveChannel/queryLiveChannelList?type=1';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
$data=curl_exec($ch);
curl_close($ch);

$re=json_decode($data,true);
for ($i=0; $i<=5; $i++)
{
  if ($id =='zhonghe'){
     if ( $re['data'][$i]['name']=='综合频道'){
         header('content-type:application/x-mpegURL');
         header('location:'.$re['data'][$i]['httpUrl']);
     }}
   if ($id =='xinwen'){
     if ( $re['data'][$i]['name']=='新闻频道'){
         header('content-type:application/x-mpegURL');
         header('location:'.$re['data'][$i]['httpUrl']);
     }}  
   if ($id =='fazhi'){
     if ( $re['data'][$i]['name']=='法治频道'){
         header('location:'.$re['data'][$i]['httpUrl']);
     }}
   if ($id =='jingsai'){
     if ( $re['data'][$i]['name']=='竞赛频道'){
         header('content-type:application/x-mpegURL');
         header('location:'.$re['data'][$i]['httpUrl']);
     }}  
   if ($id =='yingshi'){
     if ( $re['data'][$i]['name']=='影视频道'){
         header('content-type:application/x-mpegURL');
         header('location:'.$re['data'][$i]['httpUrl']);
     }}  
   if ($id =='shenghuo'){
     if ( $re['data'][$i]['name']=='4K南国都市频道'){
         header('content-type:application/x-mpegURL');
         header('location:'.$re['data'][$i]['httpUrl']);
     }}      
}
?>
