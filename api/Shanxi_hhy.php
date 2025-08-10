<?php
//太原,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811850.html[/url]
///阳泉,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811848.html[/url]
//长治,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811847.html[/url]
//晋城,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811846.html[/url]
//朔州,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811845.html[/url]
//晋中,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811844.html[/url]
//运城,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811843.html[/url]
//忻州,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811842.html[/url]
//临汾,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811841.html[/url]
//吕梁,[url]https://apphhplushttps.sxrtv.com//a/b/a/live_wap_811840.html[/url]
$id = isset($_GET['id'])?$_GET['id']:'ty1';
$n = [
     'ty1' => 'a', //太原新闻综合
     'dt1' => 'b', //大同新闻综合x
     'yq1' => 'c', //阳泉新闻综合
     'cz1' => 'd', //长治新闻综合
     'jc1' => 'e', //晋城新闻综合
     'sz1' => 'f', //朔州新闻综合
     'jz1' => 'g', //晋中综合
     'yc1' => 'h', //运城新闻综合
     'xz1' => 'i', //忻州综合
     'lf1' => 'j', //临汾新闻综合
     'll1' => 'k', //吕梁新闻综合
     ];
$url = "https://dyhhplus.sxrtv.com/apiv4.5/api/m3u8_notoken?channelid=custom{$n[$id]}&site=53";
$videoUrl = json_decode(file_get_contents($url),1)['data']['address'];
header("location:".$videoUrl);
//echo $videoUrl;
?>