<?php
$id=$_GET[id];//id扫了13个分别为2.3.5.6.7.8.9.10.11.12.13.16.20,可能后面还有//
$client = md5(rand().time());
$token = md5($client."com.hoge.android.wuhan");
$url = "http://mobile.appwuhan.com/zswh6/channel_detail.php?appid=16&appkey=rFUm5PYocCj6e1h0m03t3WarVJcMV98c&client_id_android=".$client."&device_token=".$token."&_member_id=&version=5.6.0&app_version=5.6.0&app_version=5.6.0&package_name=com.hoge.android.wuhan&system_version=7.1.1&phone_models=XT1799-2&channel_id=".$id."&ad_group=mobile";
$time = explode(".",microtime(true));
$time = $time[0].$time[1];
$random = $time.substr(md5(time()),0,6);
$sign = base64_encode(hash('sha1',"c9e1074f5b3f9fc8ea15d152add07294&S1M1MXczMFhPQXNPZXc0RU1vVWdwV2NRTU9JMmhHMFI=&5.6.0&".$random,''));
$header = array(
"User-Agent: m2oSmartCity_104 1.0.0",
"X-API-TIMESTAMP: $random",
"X-API-SIGNATURE: $sign",
"X-API-VERSION: 5.6.0",
"X-AUTH-TYPE: sha1",
"X-API-KEY: c9e1074f5b3f9fc8ea15d152add07294",
"Host: mobile.appwuhan.com",
"Connection: Keep-Alive",
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$result = curl_exec($ch);
curl_close($ch);
$playurl = json_decode($result);
header('Location:'.$playurl[0]->m3u8);
?>