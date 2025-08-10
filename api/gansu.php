<?php
$id = isset($_GET['id']) ? $_GET['id'] : "gsws";
$n = [
 "gsws"=>"甘肃卫视",
 "jjpd"=>"经济频道",
 "whys"=>"文化影视",
 "ggyj"=>"公共应急",
 "sepd"=>"少儿频道",
 "dspd"=>"都市频道",
 "ydds"=>"移动电视"
];

$url = "https://www.gstv.com.cn/zxc.jhtml";
@$data = file_get_contents($url);
preg_match("/data-url='(.*?)'>".$n[$id]."<\/a>/i", $data,$m3u8);
$url = $m3u8[1];
header('Location: '.$url);
/*
甘肃卫视
经济频道
文化影视
公共应急
少儿频道
都市频道
移动电视
       
       静态源
       https://hls.gstv.com.cn/49048r/6e1sy2.m3u8   甘肃卫视
       https://hls.gstv.com.cn/49048r/10iv1j.m3u8   经济频道
       https://hls.gstv.com.cn/49048r/w1l6d5.m3u8   文化影视
       https://hls.gstv.com.cn/49048r/3t5xyc.m3u8   公共应急
       https://hls.gstv.com.cn/49048r/922k96.m3u8   少儿频道
       https://hls.gstv.com.cn/49048r/l54391.m3u8   都市频道
       https://hls.gstv.com.cn/49048r/y72q36.m3u8   移动电视
       
       */