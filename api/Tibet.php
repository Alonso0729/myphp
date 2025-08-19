<?php

$id = $_GET['id'];//0,1,2
//$id = 0;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,  "http://api.vtibet.cn/xizangmobileinf/rest/xz/cardgroups");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'json=%7B%22cardgroups%22%3A%22LIVECAST%22%2C%22paging%22%3A%7B%22page_no%22%3A%221%22%2C%22page_size%22%3A%22100%22%7D%2C%22version%22%3A%221.0.0%22%7D');
$r = curl_exec($ch);
curl_close($ch);

$j = json_decode($r);
$playurl = $j->cardgroups[1]->cards[$id]->video->url_hd;
header('Access-Control-Allow-Origin: *');
header('Location: ' . $playurl);
?>