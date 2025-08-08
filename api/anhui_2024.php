<?php
error_reporting(0);
$ts = $_GET['ts'];
if(!$ts) {
   $id = $_GET['id']??'ahtv';///$id=ahtv,jjshtv,ggtv,nykjtv,ystv,zytytv,gjtv

   $baseUrl = "https://lives.ahsx.ahtv.cn/live/{$id}.m3u8";        
   $md5Hash = md5("MT3ZdPwb7fh4YntkH7m6" .$id . dechex(time()));  
   $baseUrl .= "?txSecret=" . $md5Hash . '&txTime=' . dechex(time());  

   $host="https://lives.ahsx.ahtv.cn/live/";
   $php = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
   header('Content-Type: application/vnd.apple.mpegurl');
   print_r(preg_replace("/(.*?.ts)/i", $php."?ts=$host$1",get(trim($baseUrl))));
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
   curl_setopt($ch, CURLOPT_HTTPHEADER,["Referer: https://console.ahsx.ahtv.cn/"]);
   $output = curl_exec($ch);
   curl_close($ch);
   return $output;
   }
?>