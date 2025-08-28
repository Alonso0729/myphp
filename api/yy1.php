<?php
error_reporting(0);
$rid = $_GET['id'];
$bstrURL = 'http://interface.yy.com/hls/new/get/'.$rid.'/'.$rid.'/1200?source=wapyy&callback=jsonp3';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $bstrURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36");
curl_setopt($ch, CURLOPT_REFERER,'http://www.yy.com/');
$data = curl_exec($ch);
curl_close($ch);
preg_match('/"hls":"(.*?)"/',$data,$result);
header('location:'.$result[1]);
?>