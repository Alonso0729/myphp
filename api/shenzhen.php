<?php
//https://sztv-live.cutv.com/info.json  //获取频道
error_reporting(0);
$header = [
          "Referer:https://www.sztv.com.cn/",
       ];
$ts = $_GET['ts'];
if(!$ts){
$id = isset($_GET['id'])?$_GET['id']:'szws';
$n = [
    //tv
    'szws' => ['AxeFRth','http://sztv-live.cutv.com'], //深圳卫视
    'szyl' => ['1q4iPng','http://sztv-live.cutv.com'], //深圳娱乐
    'szse' => ['1SIQj6s','http://sztv-live.cutv.com'], //深圳少儿
    'szgg' => ['2q76Sw2','http://sztv-live.cutv.com'], //深圳公共
    'szcjsh' => ['3vlcoxP','http://sztv-live.cutv.com'], //深圳财经
    'szdsj' => ['4azbkoY','http://sztv-live.cutv.com'], //深圳电视剧
    'yhgw' => ['BJ5u5k2','http://sztv-live.cutv.com'], //宜和购物
    'szds' => ['ZwxzUXr','http://sztv-live.cutv.com'], //深圳都市
    'szgj' => ['sztvgjpd','http://sztv-live.cutv.com'], //深圳国际
    'szyd' => ['wDF6KJ3','http://sztv-live.cutv.com'], //深圳移动
        //gb
    'ba1' => ['Qr45J1U','http://sztv-live.cutv.com'], //宝安fm1043
    'fy' => ['bPHSw12','http://sztv-live.cutv.com'], //飞扬971
    'ba2' => ['g0c7BL1','http://sztv-live.cutv.com'], //宝安fm905
    'xf' => ['ms3M6DA','http://sztv-live.cutv.com'], //先锋898
    'sj' => ['sf4orL8','http://sztv-live.cutv.com'], //私家车广播
    'kl' => ['171m21B','http://sztv-live.cutv.com'], //快乐106.2

];
  $pid = $n[$id][0];
  $http_prefix = $n[$id][1];
  $t = time();
  $m='/500/';
  if(strlen($id) < 4)$m='/64/';
  $token = md5($t . $pid . 'cutvLiveStream|Dream2017');
  $bstrURL = "http://cls2.cutv.com/getCutvHlsLiveKey?t=" . $t . "&token=" . $token . "&id=" . $pid."";//&at=1
  $dynamic_id = get_data($bstrURL,$header);
  $playurl = $http_prefix . '/' . $pid . $m. $dynamic_id . '.m3u8';
  $burl = dirname($playurl)."/";
  $host = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']; //如果你网站协议头是https，将http换成https;
  $playurl_data = get_data($playurl,$header);
  header('Content-Type: application/vnd.apple.mpegurl');
  header("Content-Disposition: attachment; filename=index.m3u8");
  print_r(preg_replace("/(.*?.ts)/i", $host."?ts=$burl$1",$playurl_data));
  }else{
  $data = get_data($ts,$header);
  header('Content-Type: video/MP2T');
  header("Content-Disposition: attachment; filename=".time().".ts");
  print_r($data);die;
  }

function get_data($url,$header){
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
   curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
}
?>