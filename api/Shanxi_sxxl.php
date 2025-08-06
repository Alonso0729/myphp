<?php
error_reporting(0);
$n = [
"sxws" => "q8RVWgs",//山西卫视
"sxjj" => "4j01KWX",//山西经济
"sxys" => "Md571Kv",//山西影视
"sxshfz" => "p4y5do9",//山西社会与法治
"sxwtsh" => "Y00Xezi",//山西文体生活
"sxhh" => "lce1mC4",//山西黄河
'ty1' => 'customa', //太原新闻综合
'dt1' => 'customb', //大同新闻综合
'yq1' => 'customc', //阳泉新闻综合
'cz1' => 'customd', //长治新闻综合
'jc1' => 'custome', //晋城新闻综合
'sz1' => 'customf', //朔州新闻综合
'jz1' => 'customg', //晋中综合
'yc1' => 'customh', //运城新闻综合
'xz1' => 'customi', //忻州综合
'lf1' => 'customj', //临汾新闻综合
'll1' => 'customk', //吕梁新闻综合
];
$id = isset($_GET['id'])?$_GET['id']:'sxws';
$url = "https://dyhhplus.sxrtv.com/apiv4.5/api/m3u8_notoken?channelid=".$n[$id];
$playurl = json_decode(file_get_contents($url),1)['data']['address'];
header("location:".$playurl);
//echo $playurl;
?>