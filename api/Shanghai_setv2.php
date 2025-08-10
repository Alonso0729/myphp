<?php
error_reporting(0);
$ts = $_GET['ts']??'';
if ($ts){
        $data = get_curl($ts);
        header('Content-Type: video/MP2T');
} else {
        #url貌似不会变，变化的话就用下面注释部分
        $url = 'https://live2018.setv.sh.cn/setv/programme10_ud.m3u8?auth_key=4794048378-0-0-06e60dd476e18047abd3be7701993ee8';
/*
        date_default_timezone_set("PRC");
        $time = str_replace('.','',microtime(true));
        $url1 = "https://www.setv.sh.cn/static/tvshow/overview.json?t={$time}";
        $url = json_decode(file_get_contents($url1))->data->liveLink;
*/
        $data = get_curl($url);
        #如果你的站点部署了SSL证书，则将下面第一个http加上s
        $data = str_replace('live2018',"http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']."?ts=https://live2018.setv.sh.cn/setv/live2018",$data);
        header("Content-Type: application/vnd.apple.mpegurl");
        header("Content-Disposition: inline; filename=index.m3u8");
}
echo $data;

function get_curl($url){
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt($ch, CURLOPT_REFERER, 'https://www.setv.sh.cn/');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
}
?>